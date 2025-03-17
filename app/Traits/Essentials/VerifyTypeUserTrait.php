<?php

namespace App\Traits\Essentials;
use Illuminate\Support\Facades\Auth;

use App\Enums\Users\RoleEnum;

trait VerifyTypeUserTrait
{

    /**
     * Verify Admin
     *
     * @return bool
     */
    public function verifyAdmin(): bool
    {
        // return true;
        $privilege = Auth::user()->role;
        return $privilege == RoleEnum::ADMIN->name;
    }


    /**
     * Verify if authenticated
     * user is blocked
     *
     * @return bool
     */
    public function verifyBlockedStatus(): bool
    {
        return Auth::user()->is_blocked;
    }

    /**
     * Verify if authenticated
     * user is blocked
     *
     * @return bool
     */
    public function verifyActiveStatus(): bool
    {
        return Auth::user()->is_active;
    }


    /**
     * Verify if authenticated
     * user is blocked
     *
     * @return bool
     */
    public function verifyLoginStatus(): bool
    {
        return Auth::user()->is_login;
    }
}
