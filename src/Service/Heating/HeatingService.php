<?php

declare(strict_types=1);

namespace App\Service\Heating;

use App\Enum\HeatingStatusEnum;

class HeatingService
{
    public function turnOn(): void
    {
        // TODO turn on the heating
    }

    public function turnOff(): void
    {
        // TODO turn on the heating
    }

    public function status(): HeatingStatusEnum
    {
        return HeatingStatusEnum::OFF;
    }
}
