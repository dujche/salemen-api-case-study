<?php

declare(strict_types=1);

namespace Sale\Filter;

use Dujche\MezzioHelperLib\Filter\CreatePayloadFilter;
use Laminas\Validator\Callback;

class CreateSalePayloadFilter extends CreatePayloadFilter
{
    public function __construct()
    {
        $this->addStringValidator('uuid', 36, 36);
        $this->addIntegerValidator('sellerId');

        $this->addFloatValidator('saleNetAmount');
        $this->addFloatValidator('saleGrossAmount');
        $this->addFloatValidator('saleTaxRatePercentage');
        $this->addFloatValidator('saleProductTotalCost');

        $this->addDateValidator('saleDate');
    }

    /**
     * @param string $fieldName
     * @return void
     */
    protected function addFloatValidator(string $fieldName): void
    {
        $this->add(
            [
                'name' => $fieldName,
                'required' => true,
                'validators' => [
                    [
                        'name' => Callback::class,
                        'options' => [
                            'callback' => function ($amount) {
                                return is_numeric($amount);
                            },
                            'messages' => [
                                Callback::INVALID_VALUE => 'Amount must be float',
                            ]
                        ]
                    ],
                ],
            ]
        );
    }
}
