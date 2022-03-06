<?php

declare(strict_types=1);

namespace ParserTest\DataHandler;

use Laminas\Http\Client;
use Laminas\Http\Response;
use Laminas\Log\LoggerInterface;
use Parser\DataHandler\SaleDataHandler;
use ParserTest\Service\ParserServiceTest;
use PHPUnit\Framework\TestCase;

class SaleDataHandlerTest extends TestCase
{
    private LoggerInterface $logger;

    private Client $httpClient;

    private SaleDataHandler $handler;

    public function setUp(): void
    {
        parent::setUp();

        $configMock = [
            'api' => [
                'sale' => 'http://www.example.com',
            ]
        ];

        $this->logger = $this->createMock(LoggerInterface::class);
        $this->httpClient = $this->createMock(Client::class);
        $this->handler = new SaleDataHandler($this->logger, $this->httpClient, $configMock);
    }

    public function testHandle(): void
    {
        $responseMock = $this->createMock(Response::class);
        $responseMock->expects($this->exactly(2))->method('getStatusCode')->willReturn(201);

        $this->httpClient->expects($this->once())->method('send')->willReturn($responseMock);

        $this->httpClient->expects($this->once())->method('setRawBody')
            ->with('{"uuid":"8a419b28-267f-49c4-b652-d0433d20fef4","sellerId":"23","saleNetAmount":"293.12","saleGrossAmount":"367.30","saleTaxRatePercentage":"0.19","saleProductTotalCost":"187.23","saleDate":"2021-12-04"}');

        $lines = explode(PHP_EOL, ParserServiceTest::EXAMPLE_CSV);

        $this->assertTrue($this->handler->handle(explode(';', $lines[1])));
    }

    public function testHandleOnRowWithoutSaleData(): void
    {
        $this->logger->expects($this->once())->method('info')->with('No data to post. Skipping.');

        $this->httpClient->expects($this->never())->method('send');

        $this->httpClient->expects($this->never())->method('setRawBody');

        $lines = explode(PHP_EOL, ParserServiceTest::EXAMPLE_CSV);

        $this->assertTrue($this->handler->handle(explode(';', $lines[2])));
    }
}
