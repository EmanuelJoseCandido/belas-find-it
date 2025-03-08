<?php

namespace App\Services\Custom\External;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Http;

class RecaptchaService
{
    /**
     * Retorna o status do recaptcha
     *
     * @param  string $token
     * @return
     */
    public function returnStatusRecaptcha(string $token)
    {
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_KEY', '6Lc5bxcqAAAAAAtp3NYnMQh8mVHIyMonzng4NBzq'),
            'response' => $token,
        ]);

        $response = $response->json();

        return $response;
    }

    /**
     * Verifica o recaptcha
     *
     * @param  string $token
     * @return boolean
     * @throws HttpResponseException
     */
    public function checkRecaptcha(string $token)
    {
        $result = $this->returnStatusRecaptcha($token);

        if (!$result['success']) {
            $this->returnRecaptchaError('Captcha is invalid.');
        }

        if ($result['score'] < 0.5) {
            $this->returnRecaptchaError('Suspicious activity detected.');
        }

        return true;
    }

    /**
     * Retorna erro do recaptcha
     *
     * @param  string $message
     * @throws HttpResponseException
     * @return void
     */
    public function returnRecaptchaError(string $message)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => $message,
        ], 403));
    }

    /**
     * verifyReCAPTCHA
     *
     * @param  string $token
     * @return boolean
     * @throws HttpResponseException
     */
    public function verifyReCAPTCHA(string $token)
    {
        return $this->checkRecaptcha($token);

    }
}
