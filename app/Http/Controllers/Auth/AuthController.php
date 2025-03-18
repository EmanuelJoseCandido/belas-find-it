<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\Auth\LoginInvalidException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\VerifyPasswordRequest;
use App\Http\Resources\UserResource;
use App\Services\Auth\AuthService;
use App\Services\Custom\External\RecaptchaService;


class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @param  AuthService $authService
     * @param  reCAPTCHA $reCAPTCHA
     * @return void
     */
    public function __construct(protected AuthService $authService, protected RecaptchaService $reCAPTCHA)
    {
    }

    /**
     * Handle a register attempt.
     *
     * @param  RegisterRequest $registerRequest
     * @return UserResource
     * @throws LoginInvalidException
     */
    public function register(RegisterRequest $registerRequest)
    {
        $input = $registerRequest->validated();
        $user = $this->authService->register($input);
        // $this->reCAPTCHA->verifyReCAPTCHA($input['recaptcha_token'], $loginRequest->ip());

        return new UserResource($user);
    }

    /**
     * Handle a login attempt.
     *
     * @param  LoginRequest $loginRequest
     * @return UserResource
     * @throws LoginInvalidException
     */
    public function login(LoginRequest $loginRequest)
    {
        $input = $loginRequest->validated();
        $user = $this->authService->login($input);
        // $this->reCAPTCHA->verifyReCAPTCHA($input['recaptcha_token'], $loginRequest->ip());

        return new UserResource($user);
    }

    /**
     * Handle a forgot password attempt
     *
     * @param  ForgotPasswordRequest $forgotPasswordRequest
     * @return null
     */
    public function forgotPassword(ForgotPasswordRequest $forgotPasswordRequest)
    {
        $input = $forgotPasswordRequest->validated();
        // $this->reCAPTCHA->verifyReCAPTCHA($input['recaptcha_token'], $forgotPasswordRequest->ip());
        return $this->authService->forgotPassword($input['email']);
    }

    /**
     * Handle a reset password attempt
     *
     * @param  ResetPasswordRequest $resetPasswordRequest
     * @return null
     * @throws \App\Exceptions\Auth\ResetPasswordTokenInvalidException
     */
    public function resetPassword(ResetPasswordRequest $resetPasswordRequest)
    {
        $input = $resetPasswordRequest->validated();
        // $this->reCAPTCHA->verifyReCAPTCHA($input['recaptcha_token'], $resetPasswordRequest->ip());
        return $this->authService->resetPassword($input['email'], $input['password'], $input['token']);
    }

    /**
     * Handle a change password attempt
     *
     * @param ChangePasswordRequest $changePasswordRequest
     * @return null
     * @throws \App\Exceptions\Auth\ChangePasswordDefaultInvalidException|\App\Exceptions\Auth\ChangePasswordInvalidException
     */
    public function changePassword(ChangePasswordRequest $changePasswordRequest)
    {
        if (auth()->user() != null) {
            $input = $changePasswordRequest->validated();
            $this->authService->changePassword($input['old_password'], $input['password']);
        }

        return;
    }

    /**
     * Handle a logout attempt
     *
     * @return null
     */
    public function logout()
    {
        return $this->authService->logout();
    }

    /**
     * Handle a me attempt
     *
     * @return null
     */
    public function me()
    {
        $user = $this->authService->me();
        return new UserResource($user);
    }

    /**
     * Handle a verifyPassword
     *
     * @param  VerifyPasswordRequest $verifyPasswordRequest
     * @return bool
     */
    public function verifyPassword(VerifyPasswordRequest $verifyPasswordRequest)
    {
        $input = $verifyPasswordRequest->validated();
        $validate = $this->authService->verifyPassword($input['password']);
        return response()->json($validate);
    }
}
