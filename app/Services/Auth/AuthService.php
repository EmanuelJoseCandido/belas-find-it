<?php

namespace App\Services\Auth;

use App\Exceptions\Auth\ChangePasswordDefaultInvalidException;
use App\Exceptions\Auth\ChangePasswordInvalidException;
use App\Exceptions\Auth\LoginInvalidException;
use App\Exceptions\Auth\ResetPasswordSameInvalidException;
use App\Exceptions\Auth\ResetPasswordTokenInvalidException;
use App\Exceptions\Auth\UserInactiveException;
use App\Exceptions\Auth\UserLoginUnauthorizedException;
use App\Exceptions\Auth\SessionRequestException;
use App\Models\Auth\PasswordResetModel;
use App\Models\UserModel;
use App\Traits\Essentials\VerifyTypeUserTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\TransientToken;

class AuthService
{
    use VerifyTypeUserTrait;

    /**
     * Handle user registration
     *
     * @param array $data
     * @return UserModel
     * @throws Exception
     */
    public function register(array $data): UserModel
    {
        $user = UserModel::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'email' => $data['email'],
            'gender' => $data['gender'],
            'phone' => $data['phone'],
            'password' => bcrypt($data['password']),
        ]);

        if (!$user) {
            throw new Exception("User registration failed");
        }

        return $user;
    }


    /**
     * Handling login request
     * Verify if user is blocked
     * Generate new session
     *
     * @throws SessionRequestException |LoginInvalidException | UserInactiveException | UserLoginUnauthorizedException
     */
    public function login(array $credentials, bool $remember)
    {


        if (Auth::check())
            throw new SessionRequestException();
        if (!Auth::attempt($credentials))
            throw new LoginInvalidException();

        if ($this->verifyActiveStatus()) {
            $this->logout();
            throw new UserInactiveException();
        }


        $user = Auth::user();

        Log::info('Login bem-sucedido', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => request()->ip(),
        ]);

        $tokenName = 'auth_token';
        $tokenExpiration = $remember ? now()->addMonths(1) : now()->addDay();
        $token = $user->createToken($tokenName, expiresAt: $tokenExpiration)->plainTextToken;

        $user->token = $token;

        return $user;
    }


    /**
     * me
     *
     * @return UserModel
     */
    public function me(): UserModel
    {
        $user = Auth::user();

        if (!$user instanceof UserModel) {
            throw new Exception("Authenticated user is not an instance of UserModel");
        }

        $user['token'] = $user->createToken("API TOKEN")->plainTextToken;
        return $user;
    }

    /**
     * Logout, end of session
     *
     * @return void
     */
    public function logout()
    {

        $user = request()->user();

        if ($user) {
            $token = $user->currentAccessToken();


            if ($token && !($token instanceof \Laravel\Sanctum\TransientToken)) {
                $token->delete();
            }

            request()->session()->invalidate();
            request()->session()->regenerateToken();
        }

        return true;
    }

    /**
     * Handle a forgot password attempt
     *
     * @param  string $email
     * @return null
     */
    public function forgotPassword(string $email)
    {
        $user = UserModel::where('email', $email)->firstOrFail();
        $token = Str::random(60);

        PasswordResetModel::create([
            'email' => $user->email,
            'token' => $token,
            'created_at' => now(),
        ]);

        return;
    }

    /**
     * Handle a reset password attempt
     *
     * @param  string $email
     * @param  string $password
     * @param  string $token
     * @return null
     * @throws ResetPasswordTokenInvalidException
     */
    public function resetPassword(string $email, string $password, string $token)
    {
        $passwordReset = PasswordResetModel::where('email', $email)->where('token', $token)->first();

        if (empty($passwordReset)) {
            throw new ResetPasswordTokenInvalidException();
        }

        $user = UserModel::where('email', $email)->firstOrFail();

        if (password_verify($user->password, $password)) {
            throw new ResetPasswordSameInvalidException();
        }

        $user->password = bcrypt($password);
        $user->save();

        PasswordResetModel::where('email', $email)->delete();

        return;
    }

    /**
     * Handle a change password attempt
     *
     * @param  string $oldPassword
     * @param  string $newPassword
     * @return null
     * @throws ChangePasswordInvalidException
     */
    public function changePassword(string $oldPassword, string $newPassword)
    {
        $user = UserModel::where('email', Auth::user()->email)->firstOrFail();

        if (!password_verify($oldPassword, $user->password)) {
            throw new ChangePasswordInvalidException();
        }

        if ($newPassword == config('default.password')) {
            throw new ChangePasswordDefaultInvalidException();
        }

        if (is_null($user->is_changed_password_once)) {
            $user->is_changed_password_once = true;
        }

        $user->password = bcrypt($newPassword);
        $user->save();

        return;
    }


    /**
     * Handle a verifyPassword
     *
     * @param  string $password
     * @return bool
     */
    public function verifyPassword(string $password)
    {
        $user = $this->me();

        if (!is_null($user)) {
            if (password_verify($password, $user['password'])) {
                return true;
            }
        }

        return false;
    }
}
