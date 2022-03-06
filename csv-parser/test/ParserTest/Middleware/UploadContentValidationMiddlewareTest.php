<?php

declare(strict_types=1);

namespace ParserTest\Middleware;

use Dujche\MezzioHelperLib\Exception\ValidationException;
use Laminas\Log\LoggerInterface;
use Parser\Middleware\UploadContentValidationMiddleware;
use Parser\Table\ImportTable;
use ParserTest\Service\ParserServiceTest;
use ParserTest\Table\ImportTableTest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Server\RequestHandlerInterface;

class UploadContentValidationMiddlewareTest extends TestCase
{
    private LoggerInterface $logger;

    private ServerRequestInterface $request;

    private RequestHandlerInterface $handler;

    private UploadContentValidationMiddleware $middleware;

    public function setUp(): void
    {
        parent::setUp();
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->handler = $this->createMock(RequestHandlerInterface::class);
        $this->middleware = new UploadContentValidationMiddleware($this->logger);
    }

    public function testOnEmptyPayload()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Invalid format of the uploaded file');

        $requestBody = $this->createMock(StreamInterface::class);

        $this->request->expects($this->once())->method('getBody')->willReturn($requestBody);
        $this->handler->expects($this->never())->method('handle');

        $this->middleware->process($this->request, $this->handler);
    }

    public function testOnInvalidPayload()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Header column is not as expected');

        $requestBody = $this->createMock(StreamInterface::class);
        $requestBody->expects($this->once())->method('getContents')->willReturn('test');

        $this->request->expects($this->once())->method('getBody')->willReturn($requestBody);
        $this->handler->expects($this->never())->method('handle');

        $this->middleware->process($this->request, $this->handler);
    }

    public function testOnValidPayload()
    {
        $requestBody = $this->createMock(StreamInterface::class);
        $requestBody->expects($this->once())->method('getContents')->willReturn(ParserServiceTest::EXAMPLE_CSV);

        $this->request->expects($this->once())->method('getBody')->willReturn($requestBody);
        $this->handler->expects($this->once())->method('handle');

        $this->middleware->process($this->request, $this->handler);
    }
}