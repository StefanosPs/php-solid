<?php

declare(strict_types=1);

namespace App\Dto\Weather;

use App\Collection\DayWeatherCollection;

readonly class ReportWeatherDto
{
    public function __construct(
        public string $resolvedAddress,
        public DayWeatherCollection $dayWeatherCollection,
    ) {
    }
}
