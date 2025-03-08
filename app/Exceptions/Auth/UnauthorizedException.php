<?php

namespace App\Exceptions\Auth;

use App\Traits\Responses\HttpResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UnauthorizedException extends Exception
{

    use HttpResponseTrait;
    protected $message = 'You are not allowed to make this action!';

    /**
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        return $this->error(
            data: $this,
            message: $this->getMessage(),
            status: 'UNAUTHORIZED',
            code: Response::HTTP_UNAUTHORIZED
        );
    }
}
