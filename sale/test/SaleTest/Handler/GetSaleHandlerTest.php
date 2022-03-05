<?php

declare(strict_types=1);

namespace SaleTest\Handler;

use DateTime;
use Laminas\Diactoros\Response\JsonResponse;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Sale\Entity\SaleEntity;
use Sale\Handler\GetSaleHandler;
use Sale\Service\SaleService;

class GetSaleHandlerTest extends TestCase
{
    private SaleService $saleService;

    private GetSaleHandler $handler;

    /**
     * @return SaleEntity
     */
    public function getMockSaleEntity(): SaleEntity
    {
        $saleEntity = new SaleEntity();
        $saleEntity->setUuid('620b9dcb-d751-4d77-a7e4-d4de97bd9ef3');
        $saleEntity->setSellerId(10);
        $saleEntity->setSaleNetAmount(22.33);
        $saleEntity->setSaleGrossAmount(33.44);
        $saleEntity->setSaleTaxRatePercentage(0.11);
        $saleEntity->setSaleDate(new DateTime('2020-01-01'));
        $saleEntity->setSaleProductTotalCost(12.33);
        return $saleEntity;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->saleService = $this->createMock(SaleService::class);
        $this->handler = new GetSaleHandler($this->saleService);
    }

    public function testHandleOnGetAllRoute(): void
    {
        $this->saleService->expects($this->once())->method('getAll')->willReturn([$this->getMockSaleEntity()]);
        $result = $this->handler->handle($this->createMock(ServerRequestInterface::class));

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(
            '[{"uuid":"620b9dcb-d751-4d77-a7e4-d4de97bd9ef3","sellerId":10,"saleNetAmount":22.33,"saleGrossAmount":33.44,"saleTaxRatePercentage":0.11,"saleProductTotalCost":12.33,"saleDate":"2020-01-01"}]',
            $result->getBody()->getContents()
        );
    }

    public function testHandleOnGetAllRouteWithSellerId(): void
    {
        $this->saleService->expects($this->once())->method('getAllBySellerId')
            ->with(10)->willReturn([$this->getMockSaleEntity()]);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())->method('getQueryParams')
            ->willReturn(['sellerId' => 10]);

        $result = $this->handler->handle($request);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(
            '[{"uuid":"620b9dcb-d751-4d77-a7e4-d4de97bd9ef3","sellerId":10,"saleNetAmount":22.33,"saleGrossAmount":33.44,"saleTaxRatePercentage":0.11,"saleProductTotalCost":12.33,"saleDate":"2020-01-01"}]',
            $result->getBody()->getContents()
        );
    }
}
