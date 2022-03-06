<?php

declare(strict_types=1);

namespace ParserTest\Handler;

use Dujche\MezzioHelperLib\Exception\RuntimeException;
use Laminas\Log\LoggerInterface;
use Parser\Handler\UploadHandler;
use Parser\Service\ImportService;
use ParserTest\Service\ParserServiceTest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;

class UploadHandlerTest extends TestCase
{
    private LoggerInterface $logger;

    private ServerRequestInterface $request;

    private ImportService $importService;

    private UploadHandler $handler;

    public function setUp(): void
    {
        parent::setUp();
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->importService = $this->createMock(ImportService::class);
        $this->handler = new UploadHandler($this->importService, $this->logger);
    }

    public function testHandleFailsToSave(): void
    {
        $this->expectException(RuntimeException::class);

        $requestBody = $this->createMock(StreamInterface::class);
        $requestBody->expects($this->once())->method('getContents')->willReturn(ParserServiceTest::EXAMPLE_CSV);

        $this->request->expects($this->once())->method('getBody')->willReturn($requestBody);

        $this->logger->expects($this->once())->method('err')->with('Inserting csv file content into database failed.');

        $this->handler->handle($this->request);
    }

    public function testHandleSucceeds(): void
    {
        $requestBody = $this->createMock(StreamInterface::class);
        $requestBody->expects($this->once())->method('getContents')->willReturn(ParserServiceTest::EXAMPLE_CSV);

        $this->request->expects($this->once())->method('getBody')->willReturn($requestBody);
        $this->logger->expects($this->never())->method('err');
        $this->importService->expects($this->once())->method('add')->willReturn(true);

        $response = $this->handler->handle($this->request);
        $this->assertEquals(201, $response->getStatusCode());
    }
}

