<?php

declare(strict_types=1);

namespace App\Dto\Weather;

class DayWeatherDto
{
    public function __construct(
        public readonly float $tempMin,
        public readonly float $tempMax,
    ) {
    }
}
