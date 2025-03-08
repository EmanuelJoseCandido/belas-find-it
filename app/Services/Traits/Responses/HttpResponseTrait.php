<?php


namespace App\Traits\Responses;


use Illuminate\Http\JsonResponse;

trait HttpResponseTrait
{
    /**
     * @param mixed $data
     * @param string|null $message
     * @param int $code
     * @return JsonResponse
     */

    protected function success(mixed $data, string $message = null, int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => 'Request was successful',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     *
     *
     *
     * @param string|null $message
     * @param int $code
     * @return JsonResponse
     */
    protected function error(array|object|string $data, string $message = null, string | int $status, int $code): JsonResponse
    {
        return response()->json([
            'error' => $data,
            'status' => $status,
            'message' => $message
        ], $code);
    }
}
