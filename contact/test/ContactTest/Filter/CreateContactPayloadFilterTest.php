<?php

declare(strict_types=1);

namespace ContactTest\Filter;

use Contact\Filter\CreateContactPayloadFilter;
use PHPUnit\Framework\TestCase;

class CreateContactPayloadFilterTest extends TestCase
{
    public function testConstructor(): void
    {
        $instance = new CreateContactPayloadFilter();

        foreach (
            [
                'uuid',
                'sellerId',
                'fullName',
                'region',
                'contactType',
                'contactDate',
                'contactProductTypeOfferedId',
                'contactProductTypeOffered'
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
        $instance = new CreateContactPayloadFilter();
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
                    'fullName' => 'John Doe',
                ]
            ],
            'invalid uuid' => [
                false,
                [
                    'uuid' => 'something',
                    'sellerId' => 14,
                    'fullName' => 'John Doe',
                ]
            ],
            'valid payload' => [
                true,
                [
                    'uuid' => '88b5d1cf-028a-4b0c-b6a4-1824cb071bf4',
                    'sellerId' => 14,
                    'fullName' => 'John Doe',
                    'region' => 'Alaska',
                    'contactDate' => '2022-01-01',
                    'contactType' => 'Phone',
                    'contactProductTypeOfferedId' => 100,
                    'contactProductTypeOffered' => 'bar'
                ]
            ]
        ];
    }
}
