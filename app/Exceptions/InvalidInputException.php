<?php

namespace App\Exceptions;

use App\Traits\Responses\HttpResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class InvalidInputException extends Exception
{
    use HttpResponseTrait;
    protected $message = 'Invalid input';

    /**
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        return $this->error(
            data: $this,
            message: $this->getMessage(),
            status: 'CONFLICT',
            code: Response::HTTP_CONFLICT
        );
    }
}
