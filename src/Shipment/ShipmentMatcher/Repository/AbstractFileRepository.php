<?php

namespace Shipment\ShipmentMatcher\Repository;

use Webmozart\Assert\Assert;

abstract class AbstractFileRepository
{
    public function __construct(protected readonly string $filePath)
    {
        Assert::fileExists($this->filePath, sprintf("The filepath (%s) does not exist", $this->filePath));
    }
}
