<?php

declare(strict_types=1);

namespace App\Notifier;

use App\Exceptions\Notification\MailerNotificationException;
use App\Service\Mailer\MailerService;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class EmailNotifier implements NotifierInterface
{
    public function __construct(
        private readonly MailerService $mailerService
    ) {
    }

    public function notify(): void
    {
        try {
            $this->mailerService->lowTemperature();
        } catch (TransportExceptionInterface $e) {
            throw new MailerNotificationException('Fail to send notification via email', $e->getCode(), $e);
        }

    }
}
