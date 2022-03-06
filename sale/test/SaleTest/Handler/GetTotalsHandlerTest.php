<?php

declare(strict_types=1);

namespace SaleTest\Handler;

use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\JsonResponse;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Sale\Handler\GetTotalsHandler;
use Sale\Service\SaleService;
use Sale\Service\TotalService;
use SaleTest\Table\SaleTableTest;
use SaleTest\Table\TotalsTableTest;

class GetTotalsHandlerTest extends TestCase
{
    private SaleService $saleService;

    private TotalService $totalService;

    private GetTotalsHandler $handler;



    protected function setUp(): void
    {
        parent::setUp();

        $this->saleService = $this->createMock(SaleService::class);
        $this->totalService = $this->createMock(TotalService::class);
        $this->handler = new GetTotalsHandler($this->totalService, $this->saleService);
    }

    public function testHandleWithNoResults(): void
    {
        $this->saleService->expects($this->never())->method('getAllByYear');

        $this->totalService->expects($this->once())->method('getByYear')
            ->with(2020)->willReturn(null);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())->method('getAttribute')->with('year')
            ->willReturn(2020);

        $result = $this->handler->handle($request);

        $this->assertInstanceOf(EmptyResponse::class, $result);
        $this->assertEquals(404, $result->getStatusCode());
    }

    public function testHandle(): void
    {
        $this->saleService->expects($this->once())->method('getAllByYear')
            ->with(2020)->willReturn([SaleTableTest::getMockSaleEntity()]);

        $this->totalService->expects($this->once())->method('getByYear')
            ->with(2020)->willReturn(TotalsTableTest::getTotalsEntity());

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())->method('getAttribute')->with('year')
            ->willReturn(2020);

        $result = $this->handler->handle($request);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(
            '{"totals":{"year":2020,"numberOfRecords":11,"netAmount":22.33,"grossAmount":33.44,"taxAmount":0.11,"profit":12.33,"profitPercentage":55.22},"items":[{"uuid":"620b9dcb-d751-4d77-a7e4-d4de97bd9ef3","sellerId":10,"saleNetAmount":22.33,"saleGrossAmount":33.44,"saleTaxRatePercentage":0.11,"saleProductTotalCost":12.33,"saleDate":"2020-01-01"}]}',
            $result->getBody()->getContents()
        );
    }
}
