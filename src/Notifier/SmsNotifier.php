<?php

declare(strict_types=1);

namespace App\Notifier;

use App\Exceptions\Notification\ChatNotificationException;
use App\Service\Sms\SmsService;
use Symfony\Component\Notifier\Exception\TransportExceptionInterface;

class SmsNotifier implements NotifierInterface
{
    public function __construct(
        private readonly SmsService $smsService
    ) {
    }

    public function notify(): void
    {

        try {
            $this->smsService->lowTemperature();
        } catch (TransportExceptionInterface $e) {
            throw new ChatNotificationException('Fail to send notification via email', $e->getCode(), $e);
        }
    }

    public function supports(): bool
    {
        return true;
    }
}
