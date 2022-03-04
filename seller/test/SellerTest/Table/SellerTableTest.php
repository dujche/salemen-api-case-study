<?php

declare(strict_types=1);

namespace SellerTest\Table;

use DateTime;
use PHPUnit\Framework\TestCase;
use Seller\Entity\SellerEntity;
use Seller\Entity\SellerEntityHydrator;
use Seller\Table\SellerTable;

class SellerTableTest extends TestCase
{
    use TableWithHydratorTestTrait;

    public function getTableMock($adapterMock, $loggerMock): SellerTable
    {
        return $this->getMockBuilder(SellerTable::class)
            ->setConstructorArgs(
                [
                    $adapterMock,
                    new SellerEntityHydrator(),
                    $loggerMock
                ]
            )
            ->onlyMethods(['getSql'])->getMock();
    }

    public function testAdd(): void
    {
        $expectedSql = <<<TEXT
INSERT INTO `sellers` (`id`, `first_name`, `last_name`, `country`, `date_joined`) VALUES (10, John, Doe, DE, 2020-01-01 00:00:00)
TEXT;
        $sellerEntity = new SellerEntity();
        $sellerEntity->setId(10);
        $sellerEntity->setFirstName('John');
        $sellerEntity->setLastName('Doe');
        $sellerEntity->setCountry('DE');
        $sellerEntity->setDateJoined(new DateTime('2020-01-01'));

        /** @var SellerTable $table */
        $table = $this->setUpTableMock(
            $expectedSql,
            $this->setupStatementMockWithAffectedRows(1)
        );

        $this->assertTrue($table->add($sellerEntity));
    }

    public function testGetByIdNoResults(): void
    {
        $expectedSql = 'SELECT `sellers`.* FROM `sellers` WHERE `id` = 10';

        /** @var SellerTable $table */
        $table = $this->setUpTableMock($expectedSql, $this->setupStatementMockWithNoResults());

        $this->assertNull($table->getById(10));
    }

    public function testGetById(): void
    {
        $expectedSql = 'SELECT `sellers`.* FROM `sellers` WHERE `id` = 10';

        /** @var SellerTable $table */
        $table = $this->setUpTableMock($expectedSql, $this->setupStatementMockWithSingleResult(['id' => 20]));

        $this->assertInstanceOf(SellerEntity::class, $table->getById(10));
    }

    public function testGetAllNoResults(): void
    {
        $expectedSql = 'SELECT `sellers`.* FROM `sellers`';

        /** @var SellerTable $table */
        $table = $this->setUpTableMock($expectedSql, $this->setupStatementMockWithNoResults());

        $this->assertNull($table->getAll());
    }

    public function testGetAll(): void
    {
        $expectedSql = 'SELECT `sellers`.* FROM `sellers`';

        /** @var SellerTable $table */
        $table = $this->setUpTableMock($expectedSql, $this->setupStatementMockWithResults());

        $this->assertNotNull($table->getAll());
    }
}
