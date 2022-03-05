<?php

declare(strict_types=1);

namespace SaleTest\Table;

use DateTime;
use PHPUnit\Framework\TestCase;
use Sale\Entity\SaleEntity;
use Sale\Entity\SaleEntityHydrator;
use Sale\Table\SaleTable;

class SaleTableTest extends TestCase
{
    use TableWithHydratorTestTrait;

    public function getTableMock($adapterMock, $loggerMock): SaleTable
    {
        return $this->getMockBuilder(SaleTable::class)
            ->setConstructorArgs(
                [
                    $adapterMock,
                    new SaleEntityHydrator(),
                    $loggerMock
                ]
            )
            ->onlyMethods(['getSql'])->getMock();
    }

    public function testAdd(): void
    {
        $expectedSql = <<<TEXT
INSERT INTO `sales` (`uuid`, `seller_id`, `sale_net_amount`, `sale_gross_amount`, `sale_tax_rate_percentage`, `sale_product_total_cost`, `sale_date`) VALUES (620b9dcb-d751-4d77-a7e4-d4de97bd9ef3, 10, 22.33, 33.44, 0.11, 12.33, 2020-01-01 00:00:00)
TEXT;
        $saleEntity = static::getMockSaleEntity();

        /** @var SaleTable $table */
        $table = $this->setUpTableMock(
            $expectedSql,
            $this->setupStatementMockWithAffectedRows(1)
        );

        $this->assertTrue($table->add($saleEntity));
    }

    public function testGetAllNoResults(): void
    {
        $expectedSql = 'SELECT `sales`.* FROM `sales`';

        /** @var SaleTable $table */
        $table = $this->setUpTableMock($expectedSql, $this->setupStatementMockWithNoResults());

        $this->assertNull($table->getAll());
    }

    public function testGetAll(): void
    {
        $expectedSql = 'SELECT `sales`.* FROM `sales`';

        /** @var SaleTable $table */
        $table = $this->setUpTableMock($expectedSql, $this->setupStatementMockWithResults());

        $this->assertNotNull($table->getAll());
    }

    public function testGetAllBySellerId(): void
    {
        $expectedSql = 'SELECT `sales`.* FROM `sales` WHERE `seller_id` = 10';

        /** @var SaleTable $table */
        $table = $this->setUpTableMock($expectedSql, $this->setupStatementMockWithResults());

        $this->assertNotNull($table->getAllBySellerId(10));
    }

    public function testGetAllByYear(): void
    {
        $expectedSql = 'SELECT `sales`.* FROM `sales` WHERE `sale_date` >= 2020-01-01 AND `sale_date` < 2021-01-01';

        /** @var SaleTable $table */
        $table = $this->setUpTableMock($expectedSql, $this->setupStatementMockWithResults());

        $this->assertNotNull($table->getAllByYear(2020));
    }

    /**
     * @return SaleEntity
     */
    public static function getMockSaleEntity(): SaleEntity
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
}
