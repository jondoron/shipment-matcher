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

    private OutputInterface $outputInterface;

    private InputInterface $inputInterface;

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

        /** @var Address[] $addresses */
        $addresses = [];

        $fn = fopen($addressFilePath, "r");

        while (! feof($fn)) {
            $addressLine = trim(fgets($fn));
            if (0 === strlen($addressLine)) {
                continue;
            }
            $addresses[] = new Address(count($addresses), $addressLine);
        }
        fclose($fn);

        $fn = fopen($driversFilePath, "r");

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

        $shipmentMatcher = new ShipmentMatcher($drivers, $addresses);
        $results = $shipmentMatcher->matchDriversToAddresses();
        foreach ($results as $result) {
            $output->writeln(sprintf(
                'Driver: %s -- Address: -- %s -- Score: %f', $result->getDriver(), $result->getAddress(), $result->getScore()
            ));
        }
        return Command::SUCCESS;
    }
}