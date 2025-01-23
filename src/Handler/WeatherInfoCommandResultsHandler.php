<?php

declare(strict_types=1);

namespace App\Handler;

use App\Dto\Weather\ReportWeatherDto;
use App\Exceptions\Notification\NotificationExceptionInterface;
use App\Notifier\NotifierInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

class WeatherInfoCommandResultsHandler
{
    private const int LOW_TEMPERATURE_LIMIT = 10;

    /**
     * @param NotifierInterface[] $notifiers
     */
    public function __construct(
        #[AutowireIterator(NotifierInterface::class)]
        private iterable $notifiers,
        private readonly LoggerInterface $logger
    ) {
    }

    public function handle(ReportWeatherDto $dayWeatherDto): void
    {
        $minTemperature = $dayWeatherDto->dayWeatherCollection->getMinTemperature();

        if ($minTemperature < self::LOW_TEMPERATURE_LIMIT) {
            $this->lowTemperatureNotify();
        }
    }

    private function lowTemperatureNotify(): void
    {

        foreach ($this->notifiers as $notifier) {
            try {
                $notifier->notify();
            } catch (NotificationExceptionInterface $exception) {
                $this->logger->error('Fail to execute notification', ['exception' => $exception]);
                continue;
            }

        }
    }
}
