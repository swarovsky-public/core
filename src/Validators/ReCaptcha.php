<?php

namespace Swarovsky\Core\Validators;

use GuzzleHttp\Client;

class ReCaptcha
{
    public function validate($attribute, $value, $parameters, $validator)
    {
        $client = new Client;
        $response = $client->post('https://www.google.com/recaptcha/api/siteverify',
            [
                'form_params' =>
                    [
                        'secret' => env('GOOGLE_RECAPTCHA_SECRET'),
                        'response' => $value
                    ]
            ]
        );

        try {
            $body = json_decode((string)$response->getBody(), false, 512, JSON_THROW_ON_ERROR);
        } catch (\Exception $e) {
            return false;
        }
        return $body->success;
    }
}
