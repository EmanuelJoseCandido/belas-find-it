<?php

namespace App\Services\Custom\External;

use Illuminate\Support\Facades\Http;

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
        if (!is_array($destinations)) {
            $destinations = [$destinations];
        }

        return Http::post('https://api.wesender.co.ao/envio/apikey', [
            'ApiKey' => config('default.wesender_app_key'),
            'Destino' => $destinations,
            'Mensagem' => $message,
            'CEspeciais' => 'false'
        ]);
    }
}
