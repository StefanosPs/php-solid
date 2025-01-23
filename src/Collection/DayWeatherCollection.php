<?php

declare(strict_types=1);

namespace App\Collection;

use App\Dto\Weather\DayWeatherDto;

/**
 * @extends \ArrayObject<int, DayWeatherDto>
 */
class DayWeatherCollection extends \ArrayObject
{
    public function getMinTemperature(): float
    {
        $min = $this->offsetGet(0)?->tempMin;

        if (null === $min) {
            throw new \InvalidArgumentException('Minimum temperature is null');
        }

        foreach ($this as $day) {
            if ($min > $day->tempMin) {
                $min = $day->tempMin;
            }
        }

        return $min;
    }
}
