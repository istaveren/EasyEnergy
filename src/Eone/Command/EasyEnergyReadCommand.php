<?php

namespace Eone\Command;

use Eone\EasyEnergyPrice;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\HttpClient;

class EasyEnergyReadCommand extends Command
{
    public const DATE_INPUT_NAME = 'date';

    protected static $defaultName = 'easyEnergy:read';

    protected function configure(): void
    {
        $this->addArgument('date', InputArgument::OPTIONAL, 'The day to read');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $success = Command::FAILURE;
        $date = new \DateTimeImmutable($input->getArgument(self::DATE_INPUT_NAME) ? $input->getArgument(self::DATE_INPUT_NAME) : 'now');
        $easyEnergy = new EasyEnergyPrice(HttpClient::create());
        $timezone = new \DateTimeZone('Europe/Amsterdam');
        if ($easyEnergy->read($date)) {
            $table = new Table($output);
            $table->setHeaders(['Date time', 'Price']);
            $fmt = \NumberFormatter::create('nl_NL', \NumberFormatter::CURRENCY);
            foreach ($easyEnergy->getPrices() as $hour) {
                $time = new \DateTime($hour["Timestamp"]);
                $time->setTimezone($timezone); // Render the time in timezone Amsterdam
                $table->addRow([ $time->format('Y-m-d H:i:s'), numfmt_format_currency($fmt, $hour["TariffUsage"], 'EUR') ]);
            }
            $table->render();

            $success = Command::SUCCESS;
        } else {
            $output->writeln('<error>Read failed.</error>');
            $output->writeln('<info>'.$easyEnergy->getErrorMessage().'</info>');
        }

        return $success;
    }
}
