<?php

namespace App\Exceptions\Auth;

use Exception;

class ChangePasswordDefaultInvalidException extends Exception
{
    protected $message = 'The new password is default system password';

    public function render()
    {
        return response()->json([
            'error'   => class_basename($this),
            'message' => $this->getMessage(),
        ], 401);
    }
}
