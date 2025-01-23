<?php

declare(strict_types=1);

namespace App\Service\Chat;

use Symfony\Component\Notifier\ChatterInterface;
use Symfony\Component\Notifier\Exception\TransportExceptionInterface;
use Symfony\Component\Notifier\Message\ChatMessage;

class ChatService
{
    public function __construct(
        private readonly ChatterInterface $chat,
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function lowTemperature(): void
    {
        $sms = new ChatMessage(
            'Don\'t Forget Your Jacket!'
        );
        $this->chat->send($sms);
    }
}
