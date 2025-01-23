<?php

declare(strict_types=1);

namespace App\Exceptions\Notification;

use Symfony\Component\Mailer\Exception\RuntimeException;

class MailerNotificationException extends RuntimeException implements NotificationExceptionInterface
{
}
