<?php

declare(strict_types=1);

namespace ParserTest\DataHandler;

use Laminas\Http\Client;
use Laminas\Http\Response;
use Laminas\Log\LoggerInterface;
use Parser\DataHandler\ContactDataHandler;
use ParserTest\Service\ParserServiceTest;
use PHPUnit\Framework\TestCase;

class ContactDataHandlerTest extends TestCase
{
    private LoggerInterface $logger;

    private Client $httpClient;

    private ContactDataHandler $handler;

    public function setUp(): void
    {
        parent::setUp();

        $configMock = [
            'api' => [
                'contact' => 'http://www.example.com',
            ]
        ];

        $this->logger = $this->createMock(LoggerInterface::class);
        $this->httpClient = $this->createMock(Client::class);
        $this->handler = new ContactDataHandler($this->logger, $this->httpClient, $configMock);
    }

    public function testHandle(): void
    {
        $this->logger->expects($this->never())->method('warn');

        $responseMock = $this->createMock(Response::class);
        $responseMock->expects($this->exactly(2))->method('getStatusCode')->willReturn(201);

        $this->httpClient->expects($this->once())->method('send')->willReturn($responseMock);

        $this->httpClient->expects($this->once())->method('setRawBody')
            ->with('{"uuid":"8a419b28-267f-49c4-b652-d0433d20fef4","sellerId":"23","region":"Thüringen","contactDate":"2021-12-04","fullName":"Peter Grayson","contactType":"Phone","contactProductTypeOfferedId":"122","contactProductTypeOffered":"Canned sausages"}');

        $lines = explode(PHP_EOL, ParserServiceTest::EXAMPLE_CSV);

        $this->assertTrue($this->handler->handle(explode(';', $lines[1])));
    }
}
