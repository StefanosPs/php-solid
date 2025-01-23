<?php

declare(strict_types=1);

namespace App\Command;

use App\Collection\DayWeatherCollection;
use App\Dto\Weather\DayWeatherDto;
use App\Dto\Weather\ReportWeatherDto;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'app:weather-info',
    description: 'Get the weather info for a city',
)]
class WeatherInfoCommand extends Command
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
        private readonly MailerInterface $mailer,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('city', InputArgument::OPTIONAL, 'City')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $city = $input->getArgument('city');

        if (
            !$city
            || !\is_string($city)
        ) {
            $io->error('Please add a valid city');

            return Command::FAILURE;
        }

        $mail = (new Email())
            ->to(new Address('mother@example.com'))
            ->subject('Command started')
            ->text(\sprintf('Command started for city %s', $city))
        ;
        $this->mailer->send(
            $mail
        );

        $response = $this->client->request(
            'GET',
            $this->generateUrl($city),
            $this->generateOptions()
        );
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

        $dayWeatherDto = new ReportWeatherDto(
            resolvedAddress: $resolvedAddress,
            dayWeatherCollection: $daysWeatherCollection
        );

        $minTemperature = $dayWeatherDto->dayWeatherCollection->getMinTemperature();
        if ($minTemperature < 10) {
            $mail = (new Email())
                ->to(new Address('child@example.com'))
                ->subject('Low Temperature Alarm')
                ->text('Don\'t Forget Your Jacket!')
            ;
            $this->mailer->send(
                $mail
            );
        }

        $io->info(
            \sprintf(
                'The low temperature for city %s is %sC',
                $city,
                $minTemperature,
            )
        );

        return Command::SUCCESS;
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
