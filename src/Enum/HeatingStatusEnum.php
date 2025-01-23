<?php

declare(strict_types=1);

namespace App\Enum;

enum HeatingStatusEnum: string
{
    case ON = 'on';
    case OFF = 'off';
}
