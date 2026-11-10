<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MpesaService
{
    /**
     * Get an OAuth access token from Daraja.
     */
    public function getAccessToken()
    {
        $url = config('services.mpesa.env') === 'sandbox'
            ? 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials'
            : 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

        $response = Http::withBasicAuth(
            config('services.mpesa.consumer_key'),
            config('services.mpesa.consumer_secret')
        )->get($url);

        $response->throw();

        return $response->json('access_token');
    }


    public function stkPush(
    string $phoneNumber,
    float $amount,
    string $accountReference,
    string $transactionDescription
) {

 // Add diagnostic logging here
    Log::info('MPESA CONFIG CHECK', [
        'environment' => config('services.mpesa.env'),
        'shortcode' => config('services.mpesa.shortcode'),
        'callback_url' => config('services.mpesa.callback_url'),
        'consumer_key_exists' => !empty(config('services.mpesa.consumer_key')),
        'consumer_secret_exists' => !empty(config('services.mpesa.consumer_secret')),
        'passkey_exists' => !empty(config('services.mpesa.passkey')),
    ]);

    $accessToken = $this->getAccessToken();

    // Normalize phone number
    $phoneNumber = preg_replace('/\D/', '', $phoneNumber);

    $phoneNumber = preg_replace('/\D/', '', $phoneNumber);

    if (str_starts_with($phoneNumber, '0')) {
        $phoneNumber = '254' . substr($phoneNumber, 1);
    }

    if (!preg_match('/^254[17][0-9]{8}$/', $phoneNumber)) {
        throw new \InvalidArgumentException(
            'Invalid Kenyan M-Pesa phone number.'
        );
    }

    // M-Pesa requires a whole-number amount
    $amount = (int) round($amount);

    $timestamp = now()->format('YmdHis');

    $password = base64_encode(
        config('services.mpesa.shortcode') .
        config('services.mpesa.passkey') .
        $timestamp
    );

    $url = config('services.mpesa.env') === 'sandbox'
        ? 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest'
        : 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

    $response = Http::withToken($accessToken)
        ->acceptJson()
        ->asJson()
        ->post($url, [
            'BusinessShortCode' => config('services.mpesa.shortcode'),
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => $amount,
            'PartyA' => $phoneNumber,
            'PartyB' => config('services.mpesa.shortcode'),
            'PhoneNumber' => $phoneNumber,
            'CallBackURL' => config('services.mpesa.callback_url'),
            'AccountReference' => $accountReference,
            'TransactionDesc' => $transactionDescription,
        ]);

    $response->throw();

    return $response->json();
}
}