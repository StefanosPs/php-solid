<?php

declare(strict_types=1);

namespace App\Service\Mailer;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

readonly class MailerService
{
    public function __construct(
        private MailerInterface $mailer,
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function commandStarted(string $city): void
    {
        $mail = (new Email())
            ->to(new Address('mother@example.com'))
            ->subject('Command started')
            ->text(\sprintf('Command started for city %s', $city))
        ;
        $this->mailer->send(
            $mail
        );
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function lowTemperature(): void
    {
        $mail = (new Email())
            ->to(new Address('child@example.com'))
            ->subject('Low Temperature Alarm')
            ->text('Don\'t Forget Your Jacket!')
        ;
        $this->mailer->send(
            $mail
        );
    }
}
