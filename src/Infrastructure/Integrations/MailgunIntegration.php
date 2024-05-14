<?php

namespace App\Infrastructure\Integrations;

use App\Application\Abstractions\IMailgunIntegration;
use Mailgun\Mailgun;
use Psr\Http\Client\ClientExceptionInterface;

class MailgunIntegration implements IMailgunIntegration {

    /**
     * @throws ClientExceptionInterface
     */
    public function sendMail(string $address, string $subject, string $body): void
    {
        $mg = Mailgun::create('key-example'); // For US servers

        $mg->messages()->send('example.com', [
            'from'    => 'bob@example.com',
            'to'      => 'sally@example.com',
            'subject' => 'The PHP SDK is awesome!',
            'text'    => 'It is so simple to send a message.'
        ]);
    }
}