<?php

namespace App\Service\WeatherInfo;

use App\Dto\Weather\ReportWeatherDto;

interface WeatherInfoServiceInterface
{
    public function get(string $city): ReportWeatherDto;
}
