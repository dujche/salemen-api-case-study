<?php

declare(strict_types=1);

namespace SaleTest\Service;

use DateTime;
use Dujche\MezzioHelperLib\Exception\DuplicateRecordException;
use Laminas\Db\Adapter\Exception\InvalidQueryException;
use Laminas\Db\ResultSet\HydratingResultSet;
use PHPUnit\Framework\TestCase;
use Sale\Entity\SaleEntity;
use Sale\Service\SaleService;
use Sale\Service\TotalService;
use Sale\Table\SaleTable;
use SaleTest\Table\SaleTableTest;

class SaleServiceTest extends TestCase
{
    public function testAddFailsOnTableWithDuplicateRecord(): void
    {
        $this->expectException(DuplicateRecordException::class);

        $saleEntity = new SaleEntity();

        $tableMock = $this->createMock(SaleTable::class);
        $tableMock->expects($this->once())->method('add')
            ->with($saleEntity)->willThrowException(new InvalidQueryException('foo'));
        $tableMock->expects($this->never())->method('getLastInsertValue');

        $service = new SaleService($tableMock, $this->createMock(TotalService::class));
        $service->add($saleEntity);
    }

    public function testAddSucceedsOnTable(): void
    {
        $saleEntity = new SaleEntity();

        $tableMock = $this->createMock(SaleTable::class);
        $tableMock->expects($this->once())->method('add')->with($saleEntity)->willReturn(true);

        $totalService = $this->createMock(TotalService::class);
        $totalService->expects($this->once())->method('add')->with($saleEntity)->willReturn(true);

        $service = new SaleService($tableMock, $totalService);
        $this->assertTrue($service->add($saleEntity));
    }

    public function testGetAllNoResults(): void
    {
        $tableMock = $this->createMock(SaleTable::class);
        $tableMock->expects($this->once())->method('getAll')->with()->willReturn(null);

        $service = new SaleService($tableMock, $this->createMock(TotalService::class));
        $this->assertSame([], $service->getAll());
    }

    public function testGetAllWithResults(): void
    {
        $saleEntity = SaleTableTest::getMockSaleEntity();

        $resultSetMock = $this->createMock(HydratingResultSet::class);
        $resultSetMock->expects($this->exactly(2))->method('valid')
            ->willReturnOnConsecutiveCalls(true, false);

        $resultSetMock->expects($this->once())->method('current')
            ->willReturn($saleEntity);

        $tableMock = $this->createMock(SaleTable::class);
        $tableMock->expects($this->once())->method('getAll')->with()->willReturn($resultSetMock);

        $service = new SaleService($tableMock, $this->createMock(TotalService::class));

        $this->assertSame(
            [
                $saleEntity
            ],
            $service->getAll()
        );
    }

    public function testGetAllBySellerIdNoResults(): void
    {
        $tableMock = $this->createMock(SaleTable::class);
        $tableMock->expects($this->once())->method('getAllBySellerId')
            ->with(10)->willReturn(null);

        $service = new SaleService($tableMock, $this->createMock(TotalService::class));
        $this->assertSame([], $service->getAllBySellerId(10));
    }

    public function testGetAllByYearNoResults(): void
    {
        $tableMock = $this->createMock(SaleTable::class);
        $tableMock->expects($this->once())->method('getAllByYear')
            ->with(2020)->willReturn(null);

        $service = new SaleService($tableMock, $this->createMock(TotalService::class));
        $this->assertSame([], $service->getAllByYear(2020));
    }
}
