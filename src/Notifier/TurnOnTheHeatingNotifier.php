<?php

declare(strict_types=1);

namespace App\Notifier;

use App\Enum\HeatingStatusEnum;
use App\Service\Heating\HeatingService;

class TurnOnTheHeatingNotifier implements NotifierInterface
{
    public function __construct(
        private readonly HeatingService $heatingService
    ) {
    }

    public function notify(): void
    {
        $this->heatingService->turnOn();
    }

    public function supports(): bool
    {
        // FIXIT Object Calisthenics Rule 2: Eliminate the ELSE Keyword
        if (HeatingStatusEnum::ON === $this->heatingService->status()) {
            $retVal = false;
        } elseif (
            date('H') >= 18
            || date('H') < 8
        ) {
            $retVal = true;
        } else {
            $retVal = false;
        }

        return $retVal;
    }
}
