<?php

namespace App\Exceptions\Auth;

use App\Traits\Responses\HttpResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class LoginInvalidException extends Exception
{
    use HttpResponseTrait;
    protected $message = 'Email and password don\'t match';

    /**
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        return $this->error(data: $this, message: $this->getMessage(), status: 'FORBIDDEN', code: Response::HTTP_FORBIDDEN);
    }
}
