<?php

declare(strict_types=1);

namespace App\Service\Sms;

use Symfony\Component\Notifier\Exception\TransportExceptionInterface;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\TexterInterface;

class SmsService
{
    public function __construct(
        private readonly TexterInterface $texter,
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function lowTemperature(): void
    {
        $sms = new SmsMessage(
            // the phone number to send the SMS message to
            'child@example.com',
            // the message
            'Do not Forget Your Jacket!'
        );
        $this->texter->send($sms);
    }
}
