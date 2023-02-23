<?php

namespace Shipment\ShipmentMatcher\Console;

use Shipment\ShipmentMatcher\Matcher\ShipmentMatcher;
use Shipment\ShipmentMatcher\Matcher\SuitabilityScore\NameBasedSuitabilityScoreCalculation;
use Shipment\ShipmentMatcher\ValueObjects\Address;
use Shipment\ShipmentMatcher\ValueObjects\Driver;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ShipmentMatcherCommand extends Command
{
    protected static $defaultName = 'shipment-matcher';

    /**
     * Documentation for configuring Symfony console commands can be found at: https://symfony.com/doc/master/console.html
     * Documentation that specifically covers command arguments and options: https://symfony.com/doc/master/console/input.html
     */
    protected function configure()
    {
        $this->setDescription('Generates an optional assignemnt of shipments to drivers based off a calculated suitability score');
        $this
            ->addArgument('addresses-file', InputArgument::REQUIRED, 'Newline seperated list of addresses')
            ->addArgument('drivers-file', InputArgument::REQUIRED, 'Newline seperated list of Drivers')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $addressFilePath = $input->getArgument('addresses-file');
        $driversFilePath = $input->getArgument('drivers-file');

        $addresses = $this->getAddressesFromFile($addressFilePath);
        $drivers = $this->getDriversFromFile($driversFilePath);

        $shipmentMatcher = new ShipmentMatcher($drivers, $addresses);
        $results = $shipmentMatcher->matchDriversToAddresses();
        $totalScore = 0;
        foreach ($results as $result) {
            $totalScore += $result->getScore();
            $output->writeln(sprintf(
                'Driver: %s | Address: %s | Score: %s',
                $result->getDriver(),
                $result->getAddress(),
                number_format($result->getScore(), 2)
            ));
        }
        $output->writeln(sprintf('Total suitability score: %s', number_format($totalScore, 2)));
        return Command::SUCCESS;
    }

    /**
     * @return Address[]
     */
    private function getAddressesFromFile(string $filePath): array
    {
        /** @var Address[] $addresses */
        $addresses = [];
        $fn = fopen($filePath, "r");

        while (! feof($fn)) {
            $addressLine = trim(fgets($fn));
            if (0 === strlen($addressLine)) {
                continue;
            }
            $addresses[] = new Address(count($addresses), $addressLine);
        }
        fclose($fn);

        return $addresses;
    }

    /**
     * @return Driver[]
     */
    private function getDriversFromFile(string $filePath): array
    {
        $fn = fopen($filePath, "r");

        /** @var Driver[] $drivers */
        $drivers = [];
        while (! feof($fn)) {
            $driverLine = trim(fgets($fn));
            if (0 === strlen($driverLine)) {
                continue;
            }
            $drivers[] = new Driver(count($drivers), $driverLine);
        }

        fclose($fn);

        return $drivers;
    }
}
