<?php

namespace App\Mail\Transport;

use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\MessageConverter;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BrevoTransport extends AbstractTransport
{
    public function __construct(
        protected string $apiKey,
    ) {
        parent::__construct();
    }

    protected function doSend(SentMessage $message): void
    {
        $email   = MessageConverter::toEmail($message->getOriginalMessage());

        // --- From ---
        $fromAddr = $email->getFrom()[0] ?? new Address(config('mail.from.address'), config('mail.from.name'));
        $from = [
            'email' => $fromAddr->getAddress(),
            'name'  => $fromAddr->getName() ?: config('mail.from.name'),
        ];

        // --- To ---
        $to = collect($email->getTo())->map(fn (Address $a) => [
            'email' => $a->getAddress(),
            'name'  => $a->getName() ?: $a->getAddress(),
        ])->values()->toArray();

        // --- Reply-To ---
        $replyTo = collect($email->getReplyTo())->map(fn (Address $a) => [
            'email' => $a->getAddress(),
            'name'  => $a->getName() ?: $a->getAddress(),
        ])->values()->toArray();

        // --- Subject ---
        $subject = $email->getSubject() ?? '(no subject)';

        // --- Body ---
        $htmlContent = $email->getHtmlBody();
        $textContent = $email->getTextBody();

        // Build payload
        $payload = array_filter([
            'sender'      => $from,
            'to'          => $to,
            'replyTo'     => $replyTo ?: null,
            'subject'     => $subject,
            'htmlContent' => $htmlContent ?: null,
            'textContent' => $textContent ?: null,
        ]);

        $response = Http::withHeaders([
            'api-key'      => $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json',
        ])->post('https://api.brevo.com/v3/smtp/email', $payload);

        if ($response->failed()) {
            Log::error('Brevo API error', [
                'status'   => $response->status(),
                'response' => $response->json(),
                'to'       => $to,
                'subject'  => $subject,
            ]);

            throw new \RuntimeException(
                'Brevo API error (' . $response->status() . '): ' . $response->body()
            );
        }

        Log::info('Email sent via Brevo', [
            'to'      => collect($to)->pluck('email')->join(', '),
            'subject' => $subject,
            'message_id' => $response->json('messageId'),
        ]);
    }

    public function __toString(): string
    {
        return 'brevo';
    }
}