<?php

declare(strict_types=1);

namespace SaleTest;

use Dujche\MezzioHelperLib\Exception\ValidationException;
use Laminas\Diactoros\Response\EmptyResponse;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sale\Middleware\GetHandlerValidationMiddleware;

class GetHandlerValidationMiddlewareTest extends TestCase
{
    public function testInvalidInput(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Invalid sellerId');

        $requestMock = $this->createMock(ServerRequestInterface::class);
        $requestMock->expects($this->once())->method('getQueryParams')
            ->willReturn(['sellerId' => 'foo']);

        $handlerMock = $this->createMock(RequestHandlerInterface::class);
        $handlerMock->expects($this->never())->method('handle');

        $middleware = new GetHandlerValidationMiddleware();
        $middleware->process($requestMock, $handlerMock);
    }

    /**
     * @throws ValidationException
     */
    public function testValidInput(): void
    {
        $requestMock = $this->createMock(ServerRequestInterface::class);

        $handlerMock = $this->createMock(RequestHandlerInterface::class);
        $handlerMock->expects($this->once())->method('handle')
            ->with($requestMock)->willReturn(new EmptyResponse(200));

        $middleware = new GetHandlerValidationMiddleware();
        $response = $middleware->process($requestMock, $handlerMock);

        $this->assertInstanceOf(EmptyResponse::class, $response);
        $this->assertSame(200, $response->getStatusCode());
    }
}