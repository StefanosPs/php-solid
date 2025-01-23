<?php

declare(strict_types=1);

namespace App\Notifier;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag]
interface NotifierInterface
{
    public function notify(): void;
}
