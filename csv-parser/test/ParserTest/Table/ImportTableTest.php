<?php

declare(strict_types=1);

namespace ParserTest\Table;

use DateTime;
use Parser\DataHandler\ParseResult;
use Parser\Entity\ImportEntity;
use Parser\Entity\ImportEntityHydrator;
use Parser\Table\ImportTable;
use PHPUnit\Framework\TestCase;

class ImportTableTest extends TestCase
{
    use TableWithHydratorTestTrait;

    /**
     * @return ImportEntity
     */
    public static function getImportEntity(): ImportEntity
    {
        $importEntity = new ImportEntity();
        $importEntity->setContent('foo');
        $importEntity->setImportedAt(new DateTime('2020-01-01'));
        return $importEntity;
    }

    public function getTableMock($adapterMock, $loggerMock): ImportTable
    {
        return $this->getMockBuilder(ImportTable::class)
            ->setConstructorArgs(
                [
                    $adapterMock,
                    new ImportEntityHydrator(),
                    $loggerMock
                ]
            )
            ->onlyMethods(['getSql'])->getMock();
    }

    public function testAdd(): void
    {
        $expectedSql = <<<TEXT
INSERT INTO `imports` (`id`, `content`, `imported_at`) VALUES (NULL, foo, 2020-01-01 00:00:00)
TEXT;
        $contactEntity = self::getImportEntity();

        /** @var ImportTable $table */
        $table = $this->setUpTableMock(
            $expectedSql,
            $this->setupStatementMockWithAffectedRows(1)
        );

        $this->assertTrue($table->add($contactEntity));
    }

    public function testGetFirstUnprocessedNoResults(): void
    {
        $expectedSql = 'SELECT `imports`.* FROM `imports` WHERE `process_started_at` IS NULL ORDER BY `id` ASC';

        /** @var ImportTable $table */
        $table = $this->setUpTableMock(
            $expectedSql,
            $this->setupStatementMockWithNoResults()
        );

        $this->assertNull($table->getFirstUnprocessed());
    }

    public function testGetFirstUnprocessed(): void
    {
        $expectedSql = 'SELECT `imports`.* FROM `imports` WHERE `process_started_at` IS NULL ORDER BY `id` ASC';

        /** @var ImportTable $table */
        $table = $this->setUpTableMock(
            $expectedSql,
            $this->setupStatementMockWithSingleResult(
                ['id' => 10, 'content' => 'foo', 'imported_at' => new DateTime('2020-01-01')]
            )
        );

        $this->assertInstanceOf(ImportEntity::class, $table->getFirstUnprocessed());
    }

    public function testMarkAsProcessing(): void
    {
        $expectedSql = <<<TEXT
UPDATE `imports` SET `process_started_at` = 2020-01-01 00:00:00 WHERE `id` = 10
TEXT;
        /** @var ImportTable $table */
        $table = $this->setUpTableMock(
            $expectedSql,
            $this->setupStatementMockWithAffectedRows(1)
        );

        $this->assertTrue($table->markAsProcessing(10, new DateTime('2020-01-01')));
    }

    public function testMarkAsProcessed(): void
    {
        $expectedSql = <<<TEXT
UPDATE `imports` SET `process_ended_at` = 2020-01-01 00:00:00, `total_records` = 0, `valid_records` = 0, `fully_imported_records` = 0, `partially_imported_records` = 0 WHERE `id` = 10
TEXT;
        /** @var ImportTable $table */
        $table = $this->setUpTableMock(
            $expectedSql,
            $this->setupStatementMockWithAffectedRows(1)
        );

        $parseResult = new ParseResult();
        $parseResult->initialize();

        $this->assertTrue($table->markAsProcessed(10, $parseResult, new DateTime('2020-01-01')));
    }
}
