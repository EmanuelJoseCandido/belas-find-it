<?php

namespace App\Services\Custom\External;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeSenderService
{
    /**
     * Send the message
     *
     * @param  string|array $destinations Número de telefone único ou array de números
     * @param  string $message
     * @return mixed
     */
    public function sendMessage($destinations, string $message)
    {
        // Converter um único número em array se necessário
        if (!is_array($destinations)) {
            $destinations = [$destinations];
        }

        try {
            $response = Http::withoutVerifying()->post('https://api.wesender.co.ao/envio/apikey', [
                'ApiKey' => config('default.wesender_app_key'),
                'Destino' => $destinations,
                'Mensagem' => $message,
                'CEspeciais' => 'true'
            ]);

            if (!$response->successful()) {
                Log::error('WeSender error: ' . $response->body());
            }

            return $response;
        } catch (\Exception $e) {
            Log::error('WeSender exception: ' . $e->getMessage());

            return false;
        }
    }
}
