<?php

require __DIR__.'/vendor/autoload.php';

use Shipment\ShipmentMatcher\CLI\ShipmentMatcherCommand;
use Symfony\Component\Console\Application;

$application =  new Application();
$application->add(new ShipmentMatcherCommand());
$application->run();
