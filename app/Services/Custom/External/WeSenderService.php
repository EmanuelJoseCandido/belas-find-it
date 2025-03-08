<?php

namespace App\Services\Custom\External;

use Illuminate\Support\Facades\Http;

class WeSenderService
{
    /**
     * Send the message
     *
     * @param  array $destinations
     * @param  string $message
     * @return void
     */
    public function sendMessage(array $destinations, string $message)
    {
        return Http::post('https://api.wesender.co.ao/envio/apikey', [
            'ApiKey'     => config('default.wesender_app_key'),
            'Destino'    => $destinations,
            'Mensagem'   => $message,
            'CEspeciais' => 'false'
        ]);
    }
}
