<?php

declare(strict_types=1);

namespace ParserTest\Service\Factory;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Laminas\Log\LoggerInterface;
use Parser\DataHandler\ContactDataHandler;
use Parser\DataHandler\ParseResult;
use Parser\DataHandler\SaleDataHandler;
use Parser\DataHandler\SellerDataHandler;
use Parser\Service\Factory\ParserServiceFactory;
use PHPUnit\Framework\TestCase;

class ParserServiceFactoryTest extends TestCase
{
    /**
     * @throws ContainerException
     */
    public function testInvoke()
    {
        $containerMock = $this->createMock(ContainerInterface::class);
        $containerMock->expects($this->exactly(6))->method('get')
            ->willReturnOnConsecutiveCalls(
                [
                    'data-handlers' => [
                        SellerDataHandler::class,
                        ContactDataHandler::class,
                        SaleDataHandler::class
                    ]
                ],
                $this->createMock(LoggerInterface::class),
                $this->createMock(SellerDataHandler::class),
                $this->createMock(ContactDataHandler::class),
                $this->createMock(SaleDataHandler::class),
                $this->createMock(ParseResult::class)
            );

        $instance = new ParserServiceFactory();
        $instance($containerMock, '');
    }
}