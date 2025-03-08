<?php

namespace App\Exceptions\Auth;

use App\Traits\Responses\HttpResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SessionException extends Exception
{
    use HttpResponseTrait;
    protected $message = 'Não foi possível iniciar sessão';
    protected $code;

    /**
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        return response()->json([
            'error'   => class_basename($this),
            'message' => $this->getMessage(),
        ], 401);
    }
}
