<?php

declare(strict_types=1);

namespace App\HttpClient\Weather;

use App\Dto\Weather\ReportWeatherDto;
use App\Factory\WeatherHttpClientResponseFactory;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherHttpClient
{
    /**
     * array<string, string>.
     */
    private const array QUERY_PARAMETERS = [
        'unitGroup' => 'metric',
        'contentType' => 'json',
        'elements' => 'tempmax,tempmin',
    ];

    public function __construct(
        #[Autowire(service: 'weather_data.client')]
        private readonly HttpClientInterface $client,
        #[Autowire(env: 'WEATHER_API_KEY')]
        private readonly string $apiKey,
        private readonly WeatherHttpClientResponseFactory $clientResponseFactory,
    ) {
    }

    public function get(string $city): ReportWeatherDto
    {
        $response = $this->client->request(
            'GET',
            $this->generateUrl($city),
            $this->generateOptions()
        );

        return $this->clientResponseFactory->create($response);
    }

    private function generateUrl(string $city): string
    {
        $fromDateTime = new \DateTime();

        return \sprintf(
            '/VisualCrossingWebServices/rest/services/timeline/%s/%s',
            $city,
            $fromDateTime->getTimestamp()
        );
    }

    /**
     * @return array<string, array<string, string>>
     */
    private function generateOptions(): array
    {
        return [
            'query' => $this->generateQueryParameters(),
        ];
    }

    /**
     * @return array<string, string>
     */
    private function generateQueryParameters(): array
    {
        return [
            'key' => $this->apiKey,
            ...self::QUERY_PARAMETERS,
        ];
    }
}
