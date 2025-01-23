<?php

declare(strict_types=1);

namespace App\Factory;

use App\Collection\DayWeatherCollection;
use App\Dto\Weather\DayWeatherDto;
use App\Dto\Weather\ReportWeatherDto;
use Symfony\Contracts\HttpClient\ResponseInterface;

class WeatherHttpClientResponseFactory
{
    public function create(ResponseInterface $response): ReportWeatherDto
    {
        $response = $response->toArray();

        if (
            !\is_array($response['days'])
        ) {
            throw new \RuntimeException('Invalid response');
        }

        $resolvedAddress =  (\is_string($response['resolvedAddress'])) ? $response['resolvedAddress'] : '';
        $daysWeatherCollection = new DayWeatherCollection();

        foreach ($response['days'] as $day) {
            if (
                !\is_array($day)
            ) {
                continue;
            }

            if (
                !\is_float($day['tempmax'])
                || !\is_float($day['tempmin'])
            ) {
                continue;
            }

            $tempMax = $day['tempmax'];
            $tempMin = $day['tempmin'];

            $daysWeatherCollection->append(
                new DayWeatherDto(
                    tempMin: $tempMin,
                    tempMax: $tempMax
                )
            );
        }

        return new ReportWeatherDto(
            resolvedAddress: $resolvedAddress,
            dayWeatherCollection: $daysWeatherCollection
        );
    }
}
