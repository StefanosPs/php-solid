<?php

declare(strict_types=1);

namespace App\Command;

use App\HttpClient\Weather\WeatherHttpClient;
use App\Service\Mailer\MailerService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:weather-info',
    description: 'Get the weather info for a city',
)]
class WeatherInfoCommand extends Command
{
    public function __construct(
        private readonly WeatherHttpClient $weatherHttpClient,
        private readonly MailerService $mailerService,
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

        $dayWeatherDto = $this->weatherHttpClient->get($city);
        $minTemperature = $dayWeatherDto->dayWeatherCollection->getMinTemperature();
        if ($minTemperature < 10) {
            $this->mailerService->lowTemperature();
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
}
