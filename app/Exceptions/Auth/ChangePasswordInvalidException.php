<?php

namespace App\Exceptions\Auth;

use Exception;

class ChangePasswordInvalidException extends Exception
{
    protected $message = 'Old password don\'t match';

    public function render()
    {
        return response()->json([
            'error'   => class_basename($this),
            'message' => $this->getMessage(),
        ], 401);
    }
}
