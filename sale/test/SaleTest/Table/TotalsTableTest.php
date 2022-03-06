<?php

declare(strict_types=1);

namespace SaleTest\Table;

use Laminas\Hydrator\ClassMethodsHydrator;
use PHPUnit\Framework\TestCase;
use Sale\Entity\TotalsEntity;
use Sale\Table\TotalsTable;

class TotalsTableTest extends TestCase
{
    use TableWithHydratorTestTrait;

    public function getTableMock($adapterMock, $loggerMock): TotalsTable
    {
        return $this->getMockBuilder(TotalsTable::class)
            ->setConstructorArgs(
                [
                    $adapterMock,
                    new ClassMethodsHydrator(),
                    $loggerMock
                ]
            )
            ->onlyMethods(['getSql'])->getMock();
    }

    public function testAdd(): void
    {
        $expectedSql = <<<TEXT
INSERT INTO `totals` (`year`, `number_of_records`, `net_amount`, `gross_amount`, `tax_amount`, `profit`) VALUES (2020, 11, 22.33, 33.44, 0.11, 12.33)
TEXT;
        $totalsEntity = static::getTotalsEntity();

        /** @var TotalsTable $table */
        $table = $this->setUpTableMock(
            $expectedSql,
            $this->setupStatementMockWithAffectedRows(1)
        );

        $this->assertTrue($table->add($totalsEntity));
    }

    public function testUpdateTotalsForYear(): void
    {
        $expectedSql = <<<TEXT
UPDATE `totals` SET `number_of_records` = 12, `net_amount` = 44.66, `gross_amount` = 66.88, `tax_amount` = 0.22, `profit` = 24.66 WHERE `year` = 2020
TEXT;
        $totalsEntity = static::getTotalsEntity();

        /** @var TotalsTable $table */
        $table = $this->setUpTableMock(
            $expectedSql,
            $this->setupStatementMockWithAffectedRows(1)
        );

        $this->assertTrue($table->updateTotalsForYear($totalsEntity, $totalsEntity));
    }

    public function testGetByYearNoResults(): void
    {
        $expectedSql = 'SELECT `totals`.* FROM `totals` WHERE `year` = 2020';

        /** @var TotalsTable $table */
        $table = $this->setUpTableMock($expectedSql, $this->setupStatementMockWithNoResults());

        $this->assertNull($table->getByYear(2020));
    }

    public function testGetByYear(): void
    {
        $expectedSql = 'SELECT `totals`.* FROM `totals` WHERE `year` = 2013';

        /** @var TotalsTable $table */
        $table = $this->setUpTableMock($expectedSql, $this->setupStatementMockWithSingleResult(['year' => 2013]));

        $this->assertInstanceOf(TotalsEntity::class, $table->getByYear(2013));
    }

    /**
     * @return TotalsEntity
     */
    public static function getTotalsEntity(): TotalsEntity
    {
        $totalsEntity = new TotalsEntity();
        $totalsEntity->setYear(2020);
        $totalsEntity->setNumberOfRecords(11);
        $totalsEntity->setNetAmount(22.33);
        $totalsEntity->setGrossAmount(33.44);
        $totalsEntity->setTaxAmount(0.11);
        $totalsEntity->setProfit(12.33);
        return $totalsEntity;
    }
}
