<?php

declare(strict_types=1);

namespace App\Notifier;

use App\Exceptions\Notification\NotificationExceptionInterface;

interface DynamicNotifierInterface
{
    /**
     * @throws NotificationExceptionInterface
     */
    public function notify(): void;

    public function supports(): bool;
}
