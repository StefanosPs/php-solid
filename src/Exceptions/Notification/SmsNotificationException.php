<?php

declare(strict_types=1);

namespace App\Exceptions\Notification;

use Symfony\Component\Mailer\Exception\RuntimeException;

class SmsNotificationException extends RuntimeException implements NotificationExceptionInterface
{
}
