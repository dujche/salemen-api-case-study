<?php

declare(strict_types=1);

namespace Seller\Filter;

use Dujche\MezzioHelperLib\Filter\CreatePayloadFilter;

class CreateSellerPayloadFilter extends CreatePayloadFilter
{
    public function __construct()
    {
        $this->addIntegerValidator('id');

        $this->addStringValidator('firstName', 50);
        $this->addStringValidator('lastName', 50);
        $this->addStringValidator('country', 2, 2);

        $this->addDateValidator('dateJoined');
    }
}
