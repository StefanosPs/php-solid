<?php

declare(strict_types=1);

namespace App\Service\WeatherInfo;

use App\Dto\Weather\ReportWeatherDto;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\AutowireDecorated;
use Symfony\Contracts\Cache\CacheInterface;

#[AsDecorator(decorates: WeatherInfoService::class)]
class CachedWeatherInfoService implements WeatherInfoServiceInterface
{
    public function __construct(
        #[AutowireDecorated]
        private readonly WeatherInfoServiceInterface $inner,
        private readonly CacheInterface $cache,
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function get(string $city): ReportWeatherDto
    {

        return $this->cache->get(
            \sprintf('weather.%s', $city),
            function ($item) use ($city) {
                $item->expiresAfter(30);

                return $this->inner->get($city);
            }
        );
    }
}
