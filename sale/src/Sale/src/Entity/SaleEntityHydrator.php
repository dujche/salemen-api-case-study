<?php

declare(strict_types=1);

namespace Sale\Entity;

use Laminas\Hydrator\ClassMethodsHydrator;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;

class SaleEntityHydrator extends ClassMethodsHydrator
{
    public function __construct(bool $underscoreSeparatedKeys = true, bool $methodExistsCheck = false)
    {
        parent::__construct($underscoreSeparatedKeys, $methodExistsCheck);

        $dateTimeStrategy = new DateTimeFormatterStrategy('Y-m-d H:i:s');

        $this->addStrategy('saleDate', $dateTimeStrategy);
    }
}
