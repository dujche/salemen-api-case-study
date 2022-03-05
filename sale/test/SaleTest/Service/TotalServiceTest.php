<?php

declare(strict_types=1);

namespace SaleTest\Service;

use PHPUnit\Framework\TestCase;
use Sale\Entity\SaleEntity;
use Sale\Entity\TotalsEntity;
use Sale\Service\TotalService;
use Sale\Table\TotalsTable;
use SaleTest\Table\SaleTableTest;

class TotalServiceTest extends TestCase
{
    public function testAdd(): void
    {
        $saleEntity = SaleTableTest::getMockSaleEntity();

        $tableMock = $this->createMock(TotalsTable::class);
        $tableMock->expects($this->once())->method('add')->willReturn(true);

        $service = new TotalService($tableMock);
        $this->assertTrue($service->add($saleEntity));
    }

    public function testUpdate(): void
    {
        $saleEntity = SaleTableTest::getMockSaleEntity();

        $tableMock = $this->createMock(TotalsTable::class);
        $tableMock->expects($this->once())->method('getByYear')->willReturn(new TotalsEntity());
        $tableMock->expects($this->once())->method('updateTotalsForYear')->willReturn(false);

        $service = new TotalService($tableMock);
        $this->assertFalse($service->add($saleEntity));
    }

    public function testGetAllByYearNoResults(): void
    {
        $tableMock = $this->createMock(TotalsTable::class);
        $tableMock->expects($this->once())->method('getByYear')
            ->with(2020)->willReturn(null);

        $service = new TotalService($tableMock);
        $this->assertNull($service->getByYear(2020));
    }
}
