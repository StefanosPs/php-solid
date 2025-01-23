<?php

declare(strict_types=1);

namespace App\Notifier;

use App\Exceptions\Notification\NotificationExceptionInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag]
interface NotifierInterface
{
    /**
     * @throws NotificationExceptionInterface
     */
    public function notify(): void;

    public function supports(): bool;
}
