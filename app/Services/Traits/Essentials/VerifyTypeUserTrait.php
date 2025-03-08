<?php

namespace App\Traits\Essentials;

use App\Enums\Users\Privileges;

trait VerifyTypeUserTrait
{

    /**
     * Verify Super Admin
     * @return bool
     */
    public function verifySuperAdmin(): bool
    {
        return auth()->user()->privilege == Privileges::SUPER_ADMIN->value;
    }

    /**
     * Verify Admin
     *
     * @return bool
     */
    public function verifyAdmin(): bool
    {
        return true;
        $privilege = auth()->user()->privilege;
        return $privilege == Privileges::ADMIN->name || $privilege == Privileges::SUPER_ADMIN->name;
    }


    /**
     * Verify if authenticated
     * user is blocked
     *
     * @return bool
     */
    public function verifyBlockedStatus(): bool
    {
        return auth()->user()->is_blocked;
    }

    /**
     * Verify if authenticated
     * user is blocked
     *
     * @return bool
     */
    public function verifyActiveStatus(): bool
    {
        return auth()->user()->is_active;
    }


    /**
     * Verify if authenticated
     * user is blocked
     *
     * @return bool
     */
    public function verifyLoginStatus(): bool
    {
        return auth()->user()->is_login;
    }
}
