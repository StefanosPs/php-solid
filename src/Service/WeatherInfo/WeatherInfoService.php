<?php

namespace App\Service\WeatherInfo;

use App\Dto\Weather\ReportWeatherDto;
use App\HttpClient\Weather\WeatherHttpClient;

class WeatherInfoService implements WeatherInfoServiceInterface
{

    public function __construct(
        private readonly WeatherHttpClient $httpClient,
    )
    {
    }

    public function get(string $city): ReportWeatherDto
    {
        return $this->httpClient->get($city);
    }
}
