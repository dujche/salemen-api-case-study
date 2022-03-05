<?php

declare(strict_types=1);

namespace ContactTest\Table;

use Contact\Entity\ContactEntity;
use Contact\Entity\ContactEntityHydrator;
use Contact\Table\ContactTable;
use DateTime;
use PHPUnit\Framework\TestCase;

class ContactTableTest extends TestCase
{
    use TableWithHydratorTestTrait;

    public function getTableMock($adapterMock, $loggerMock): ContactTable
    {
        return $this->getMockBuilder(ContactTable::class)
            ->setConstructorArgs(
                [
                    $adapterMock,
                    new ContactEntityHydrator(),
                    $loggerMock
                ]
            )
            ->onlyMethods(['getSql'])->getMock();
    }

    public function testAdd(): void
    {
        $expectedSql = <<<TEXT
INSERT INTO `contacts` (`uuid`, `seller_id`, `full_name`, `region`, `contact_date`, `contact_type`, `contact_product_type_offered_id`, `contact_product_type_offered`) VALUES (620b9dcb-d751-4d77-a7e4-d4de97bd9ef3, 10, John Doe, Alaska, 2020-01-01 00:00:00, Email, 100, bar)
TEXT;
        $contactEntity = new ContactEntity();
        $contactEntity->setUuid('620b9dcb-d751-4d77-a7e4-d4de97bd9ef3');
        $contactEntity->setSellerId(10);
        $contactEntity->setFullName('John Doe');
        $contactEntity->setRegion('Alaska');
        $contactEntity->setContactType('Email');
        $contactEntity->setContactDate(new DateTime('2020-01-01'));
        $contactEntity->setContactProductTypeOfferedId(100);
        $contactEntity->setContactProductTypeOffered('bar');

        /** @var ContactTable $table */
        $table = $this->setUpTableMock(
            $expectedSql,
            $this->setupStatementMockWithAffectedRows(1)
        );

        $this->assertTrue($table->add($contactEntity));
    }

    public function testGetAllNoResults(): void
    {
        $expectedSql = 'SELECT `contacts`.* FROM `contacts`';

        /** @var ContactTable $table */
        $table = $this->setUpTableMock($expectedSql, $this->setupStatementMockWithNoResults());

        $this->assertNull($table->getAll());
    }

    public function testGetAll(): void
    {
        $expectedSql = 'SELECT `contacts`.* FROM `contacts`';

        /** @var ContactTable $table */
        $table = $this->setUpTableMock($expectedSql, $this->setupStatementMockWithResults());

        $this->assertNotNull($table->getAll());
    }

    public function testGetAllBySellerId(): void
    {
        $expectedSql = 'SELECT `contacts`.* FROM `contacts` WHERE `seller_id` = 10';

        /** @var ContactTable $table */
        $table = $this->setUpTableMock($expectedSql, $this->setupStatementMockWithResults());

        $this->assertNotNull($table->getAllBySellerId(10));
    }
}
