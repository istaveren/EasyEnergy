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
    public const START_DATE_INPUT_NAME = 'start_date';
    public const END_DATE_INPUT_NAME = 'end_date';
    public const STORE_IN_DATABASE = 'save';
    public const FORMAT_FULL_DATE = 'Y-m-d H:i:s';

    protected static $defaultName = 'easyEnergy:read';

    protected function configure(): void
    {
        $this->addArgument(self::START_DATE_INPUT_NAME, InputArgument::OPTIONAL, 'The start day to read');
        $this->addArgument(self::END_DATE_INPUT_NAME, InputArgument::OPTIONAL, 'The end day to read');
        $this->addOption(self::STORE_IN_DATABASE, 's', \Symfony\Component\Console\Input\InputOption::VALUE_NONE, 'Store it in the database');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $success = Command::FAILURE;
        $timezone = new \DateTimeZone('Europe/Amsterdam');
        $startDate = new \DateTimeImmutable($input->getArgument(self::START_DATE_INPUT_NAME) ? $input->getArgument(self::START_DATE_INPUT_NAME) : 'today 00:00:00', $timezone);
        $endDate = new \DateTimeImmutable($input->getArgument(self::END_DATE_INPUT_NAME) ? $input->getArgument(self::END_DATE_INPUT_NAME) : 'tomorrow 00:00:00', $timezone);
        $easyEnergy = new EasyEnergyPrice(HttpClient::create());
        if ($easyEnergy->read($startDate, $endDate)) {
            $table = new Table($output);
            $table->setHeaders(['Date time', 'Price']);
            $fmt = \NumberFormatter::create('nl_NL', \NumberFormatter::CURRENCY);
            foreach ($easyEnergy->getPrices() as $hour) {
                $time = new \DateTime($hour["Timestamp"]);
                $time->setTimezone($timezone); // Render the time in timezone Amsterdam
                $table->addRow([ $time->format(self::FORMAT_FULL_DATE), numfmt_format_currency($fmt, $hour["TariffUsage"], 'EUR') ]);
            }
            $table->render();

            if ($input->getOption(self::STORE_IN_DATABASE)) {
                $this->store($startDate, $endDate, $easyEnergy->getPrices(), $output);
                $output->writeln('<info>Data stored in the database.</info>');
            }
            $success = Command::SUCCESS;
        } else {
            $output->writeln('<error>Read failed.</error>');
            $output->writeln('<info>'.$easyEnergy->getErrorMessage().'</info>');
        }

        return $success;
    }

    /**
     * Store the data in the database
     *
     * @param \DateTimeImmutable $start
     * @param \DateTimeImmutable $end
     * @param array $prices
     * @param OutputInterface $output
     */
    protected function store(\DateTimeImmutable $start, \DateTimeImmutable $end, array $prices, OutputInterface $output)
    {
        $pdo = new \Eone\AppPDO();
        $pdo->beginTransaction();
        try {
            \Eone\Model\PricePerHour::deleteFromTo($pdo, $start->format(self::FORMAT_FULL_DATE), $end->format(self::FORMAT_FULL_DATE));
            \Eone\Model\PricePerHour::insert($pdo, $prices);
        } catch (\PDOException $e) {
            $output->writeln("<error>PDO exception</error>");
            $output->writeln('<info>'.$e->getMessage().'</info>');
            $pdo->rollBack();
        }
        $pdo->commit();
    }
}
