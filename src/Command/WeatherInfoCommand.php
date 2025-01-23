<?php

declare(strict_types=1);

namespace App\Command;

use App\HttpClient\Weather\WeatherHttpClient;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

#[AsCommand(
    name: 'app:weather-info',
    description: 'Get the weather info for a city',
)]
class WeatherInfoCommand extends Command
{
    public function __construct(
        private readonly WeatherHttpClient $weatherHttpClient,
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

        $dayWeatherDto = $this->weatherHttpClient->get($city);
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
}
