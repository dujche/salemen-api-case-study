<?php

declare(strict_types=1);

namespace SellerTest\Filter;

use PHPUnit\Framework\TestCase;
use Seller\Filter\CreateSellerPayloadFilter;

class CreateSellerPayloadFilterTest extends TestCase
{
    public function testConstructor(): void
    {
        $instance = new CreateSellerPayloadFilter();

        $this->assertTrue($instance->has('id'));
        $this->assertTrue($instance->has('firstName'));
        $this->assertTrue($instance->has('lastName'));
        $this->assertTrue($instance->has('country'));
        $this->assertTrue($instance->has('dateJoined'));
    }

    /**
     * @dataProvider isValidDataProvider
     */
    public function testIsValid(bool $expectedResult, array $samplePayload): void
    {
        $instance = new CreateSellerPayloadFilter();
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
                    'id' => 100,
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                ]
            ],
            'country too long' => [
                false,
                [
                    'id' => 100,
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                    'country' => 'USA',
                    'dateJoined' => '2022-01-01'
                ]
            ],
            'valid payload' => [
                true,
                [
                    'id' => 100,
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                    'country' => 'DE',
                    'dateJoined' => '2022-01-01'
                ]
            ]
        ];
    }
}
