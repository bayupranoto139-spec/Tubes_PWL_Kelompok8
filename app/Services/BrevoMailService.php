<?php

namespace App\Services;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpClient\HttpClient;

class BrevoMailService
{
    private $client;
    private $apiKey;

    public function __construct()
    {
        $this->client = HttpClient::create();
        $this->apiKey = env('BREVO_API_KEY');
    }

    public function sendEmail($to, $subject, $content)
    {
        return $this->client->request('POST', 'https://api.brevo.com/v3/smtp/email', [
            'headers' => [
                'api-key' => $this->apiKey,
                'Content-Type' => 'application/json',
                'accept' => 'application/json',
            ],
            'json' => [
                'sender' => [
                    'name' => env('MAIL_FROM_NAME'),
                    'email' => env('MAIL_FROM_ADDRESS'),
                ],
                'to' => [
                    [
                        'email' => $to,
                    ]
                ],
                'subject' => $subject,
                'htmlContent' => $content,
            ]
        ]);
    }
}