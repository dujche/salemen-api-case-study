<?php

declare(strict_types=1);

namespace SellerTest\Handler;

use DateTime;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\JsonResponse;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Seller\Entity\SellerEntity;
use Seller\Handler\GetSellerHandler;
use Seller\Service\SellerService;

class GetSellerHandlerTest extends TestCase
{
    private SellerService $sellerService;

    private GetSellerHandler $handler;

    /**
     * @return SellerEntity
     */
    public function getMockSellerEntity(): SellerEntity
    {
        $sellerEntity = new SellerEntity();
        $sellerEntity->setId(10);
        $sellerEntity->setFirstName('John');
        $sellerEntity->setLastName('Doe');
        $sellerEntity->setCountry('DE');
        $sellerEntity->setDateJoined(new DateTime('2020-07-07'));
        return $sellerEntity;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->sellerService = $this->createMock(SellerService::class);
        $this->handler = new GetSellerHandler($this->sellerService);
    }

    public function testHandleOnGetAllRoute(): void
    {
        $this->sellerService->expects($this->once())->method('getAll')->willReturn([$this->getMockSellerEntity()]);
        $result = $this->handler->handle($this->createMock(ServerRequestInterface::class));

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(
            '[{"id":10,"firstName":"John","lastName":"Doe","country":"DE","dateJoined":"2020-07-07"}]',
            $result->getBody()->getContents()
        );
    }

    public function testHandleOnGetSingleRouteWithoutResult(): void
    {
        $requestMock = $this->createMock(ServerRequestInterface::class);
        $requestMock->expects($this->once())->method('getAttribute')->with('id')->willReturn(10);

        $this->sellerService->expects($this->once())->method('getById')->with(10)->willReturn(null);
        $result = $this->handler->handle($requestMock);

        $this->assertInstanceOf(EmptyResponse::class, $result);
        $this->assertEquals(404, $result->getStatusCode());
    }

    public function testHandleOnGetSingleRouteWithResult(): void
    {
        $sellerEntity = $this->getMockSellerEntity();

        $requestMock = $this->createMock(ServerRequestInterface::class);
        $requestMock->expects($this->once())->method('getAttribute')->with('id')->willReturn(10);

        $this->sellerService->expects($this->once())->method('getById')->with(10)->willReturn($sellerEntity);
        $result = $this->handler->handle($requestMock);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(
            '{"id":10,"firstName":"John","lastName":"Doe","country":"DE","dateJoined":"2020-07-07"}',
            $result->getBody()->getContents()
        );
    }
}
