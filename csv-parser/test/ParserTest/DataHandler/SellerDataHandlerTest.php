<?php

declare(strict_types=1);

namespace ParserTest\DataHandler;

use Laminas\Http\Client;
use Laminas\Http\Response;
use Laminas\Log\LoggerInterface;
use Parser\DataHandler\SellerDataHandler;
use ParserTest\Service\ParserServiceTest;
use PHPUnit\Framework\TestCase;

class SellerDataHandlerTest extends TestCase
{
    private LoggerInterface $logger;

    private Client $httpClient;

    private SellerDataHandler $handler;

    public function setUp(): void
    {
        parent::setUp();

        $configMock = [
            'api' => [
                'seller' => 'http://www.example.com',
            ]
        ];

        $this->logger = $this->createMock(LoggerInterface::class);
        $this->httpClient = $this->createMock(Client::class);
        $this->handler = new SellerDataHandler($this->logger, $this->httpClient, $configMock);
    }

    public function testHandle(): void
    {
        $this->logger->expects($this->once())->method('warn')->with('Request unsuccessful - Response received: ');

        $responseMock = $this->createMock(Response::class);
        $responseMock->expects($this->exactly(2))->method('getStatusCode')->willReturn(400);

        $this->httpClient->expects($this->once())->method('send')->willReturn($responseMock);

        $this->httpClient->expects($this->once())->method('setRawBody')
            ->with('{"id":"23","firstName":"Hans","lastName":"Müller","dateJoined":"2018-08-17","country":"DE"}');

        $lines = explode(PHP_EOL, ParserServiceTest::EXAMPLE_CSV);

        $this->assertFalse($this->handler->handle(explode(';', $lines[1])));
    }
}
