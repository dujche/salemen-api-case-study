<?php

declare(strict_types=1);

namespace Seller\Filter;

use Laminas\Filter\StringTrim;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\Date;
use Laminas\Validator\Digits;
use Laminas\Validator\StringLength;

class CreateSellerPayloadFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(
            [
                'name' => 'id',
                'required' => true,
                'filters' => [
                    [
                        'name' => StringTrim::class,
                        'options' => [],
                    ],
                ],
                'validators' => [
                    [
                        'name' => Digits::class,
                    ],
                ],
            ]
        );

        $this->addStringValidators('firstName', 50);
        $this->addStringValidators('lastName', 50);
        $this->addStringValidators('country', 2, 2);

        $this->add(
            [
                'name' => 'dateJoined',
                'required' => true,
                'filters' => [
                    [
                        'name' => StringTrim::class,
                        'options' => [],
                    ],
                ],
                'validators' => [
                    [
                        'name' => Date::class,
                    ],
                ],
            ]
        );
    }

    private function addStringValidators(string $fieldName, int $maxLength, int $minLength = 1): void
    {
        $this->add(
            [
                'name' => $fieldName,
                'required' => true,
                'filters' => [
                    [
                        'name' => StringTrim::class,
                        'options' => [],
                    ],
                ],
                'validators' => [
                    [
                        'name' => StringLength::class,
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min' => $minLength,
                            'max' => $maxLength,
                            'messages' => [
                                StringLength::TOO_LONG => $fieldName . ' must be less than '. ($maxLength + 1) .' characters long',
                                StringLength::TOO_SHORT => $fieldName . ' must be more than '. ($minLength - 1) .' characters long',
                            ]
                        ],
                    ],
                ],
            ]
        );
    }
}
