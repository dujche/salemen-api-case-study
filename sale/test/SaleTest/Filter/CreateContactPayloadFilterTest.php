<?php

declare(strict_types=1);

namespace SaleTest\Filter;

use PHPUnit\Framework\TestCase;
use Sale\Filter\CreateSalePayloadFilter;

class CreateContactPayloadFilterTest extends TestCase
{
    public function testConstructor(): void
    {
        $instance = new CreateSalePayloadFilter();

        foreach (
            [
                'uuid',
                'sellerId',
                'saleNetAmount',
                'saleGrossAmount',
                'saleTaxRatePercentage',
                'saleProductTotalCost',
                'saleDate'
            ] as $field
        ) {
            $this->assertTrue($instance->has($field));
        }
    }

    /**
     * @dataProvider isValidDataProvider
     */
    public function testIsValid(bool $expectedResult, array $samplePayload): void
    {
        $instance = new CreateSalePayloadFilter();
        $instance->setData($samplePayload);

        $this->assertSame($expectedResult, $instance->isValid());
    }

    public function isValidDataProvider(): array
    {
        return [
            'faulty payload' => [
                false,
                [
                    'foo' => 'bar'
                ]
            ],
            'incomplete payload' => [
                false,
                [
                    'uuid' => '88b5d1cf-028a-4b0c-b6a4-1824cb071bf4',
                    'sellerId' => 14,
                    'saleDate' => '2022-01-01',
                ]
            ],
            'invalid uuid' => [
                false,
                [
                    'uuid' => 'something',
                    'sellerId' => 14,
                    'saleDate' => '2022-01-01',
                ]
            ],
            'valid payload' => [
                true,
                [
                    'uuid' => '88b5d1cf-028a-4b0c-b6a4-1824cb071bf4',
                    'sellerId' => 14,
                    'saleNetAmount' => 12.03,
                    'saleGrossAmount' => 15.77,
                    'saleTaxRatePercentage' => 0.19,
                    'saleProductTotalCost' => 9.99,
                    'saleDate' => '2022-01-01',
                ]
            ]
        ];
    }
}
