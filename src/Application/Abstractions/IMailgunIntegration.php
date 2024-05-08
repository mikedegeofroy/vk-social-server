<?php

namespace App\Application\Abstractions;

interface IMailgunIntegration {
    public function sendMail(string $address, string $subject, string $body);
}