<?php

declare(strict_types=1);

namespace App\Command;

use App\Handler\WeatherInfoCommandResultsHandler;
use App\Service\Mailer\MailerService;
use App\Service\WeatherInfo\CachedWeatherInfoService;
use App\Service\WeatherInfo\WeatherInfoServiceInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[AsCommand(
    name: 'app:weather-info',
    description: 'Get the weather info for a city',
)]
class WeatherInfoCommand extends Command
{
    public function __construct(
        #[Autowire(service: CachedWeatherInfoService::class)]
        private readonly WeatherInfoServiceInterface $weatherInfo,
        private readonly MailerService $mailerService,
        private readonly WeatherInfoCommandResultsHandler $resultsHandler,
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

        $this->mailerService->commandStarted($city);

        $dayWeatherDto = $this->weatherInfo->get($city);
        $this->resultsHandler->handle($dayWeatherDto);

        $io->info(
            \sprintf(
                'The low temperature for city %s is %sC',
                $dayWeatherDto->resolvedAddress,
                $dayWeatherDto->dayWeatherCollection->getMinTemperature(),
            )
        );

        return Command::SUCCESS;
    }
}
