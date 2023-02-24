<?php

namespace Shipment\ShipmentMatcher\CLI;

use Shipment\ShipmentMatcher\Matcher\ShipmentMatcher;
use Shipment\ShipmentMatcher\Repository\FileDriverRepository;
use Shipment\ShipmentMatcher\Repository\FileShipmentDestinationRepository;
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
            ->addArgument('shipment-destinations-file', InputArgument::REQUIRED, 'Newline seperated list of shipment destinations')
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
        $shipmentDestinationRepository = new FileShipmentDestinationRepository($input->getArgument('shipment-destinations-file'));
        $driverRepository = new FileDriverRepository($input->getArgument('drivers-file'));

        $shipmentMatcher = new ShipmentMatcher(
            $driverRepository->getDrivers(), $shipmentDestinationRepository->getShipmentDestinations()
        );

        $results = $shipmentMatcher->matchDriversToAddresses();
        $totalScore = 0;
        foreach ($results as $result) {
            $totalScore += $result->getScore();
            $output->writeln(sprintf(
                'Driver: %s | ShipmentDestination: %s | Score: %s',
                $result->getDriver(),
                $result->getAddress(),
                number_format($result->getScore(), 2)
            ));
        }
        $output->writeln(sprintf('Total suitability score: %s', number_format($totalScore, 2)));
        return Command::SUCCESS;
    }
}
