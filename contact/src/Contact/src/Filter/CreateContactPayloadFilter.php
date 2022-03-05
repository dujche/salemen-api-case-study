<?php

declare(strict_types=1);

namespace Contact\Filter;

use Dujche\MezzioHelperLib\Filter\CreatePayloadFilter;

class CreateContactPayloadFilter extends CreatePayloadFilter
{
    public function __construct()
    {
        $this->addStringValidator('uuid', 36, 36);
        $this->addIntegerValidator('sellerId');

        $this->addStringValidator('fullName', 100);
        $this->addStringValidator('region', 30);
        $this->addStringValidator('contactType', 20);

        $this->addDateValidator('contactDate');

        $this->addIntegerValidator('contactProductTypeOfferedId');
        $this->addStringValidator('contactProductTypeOffered', 50);
    }
}
