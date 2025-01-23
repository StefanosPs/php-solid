<?php

declare(strict_types=1);

namespace App\Notifier;

use App\Exceptions\Notification\ChatNotificationException;
use App\Exceptions\Notification\NotificationExceptionInterface;
use App\Service\Chat\ChatService;
use Symfony\Component\Notifier\Exception\TransportExceptionInterface;

class ChatNotifier implements NotifierInterface
{
    public function __construct(
        private readonly ChatService $chatService,
    ) {
    }

    /**
     * @throws NotificationExceptionInterface
     */
    public function notify(): void
    {


        try {
            $this->chatService->lowTemperature();
        } catch (TransportExceptionInterface $e) {
            throw new ChatNotificationException('Fail to send notification via email', $e->getCode(), $e);
        }
    }
}
