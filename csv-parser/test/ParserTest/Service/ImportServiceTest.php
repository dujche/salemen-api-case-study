<?php

declare(strict_types=1);

namespace ParserTest\Service;

use Parser\DataHandler\ParseResult;
use Parser\Entity\ImportEntity;
use Parser\Service\ImportService;
use Parser\Table\ImportTable;
use PHPUnit\Framework\TestCase;

class ImportServiceTest extends TestCase
{
    private ImportTable $tableMock;

    private ImportService $importService;

    public function setUp(): void
    {
        parent::setUp();
        $this->tableMock = $this->createMock(ImportTable::class);
        $this->service = new ImportService($this->tableMock);
    }


    public function testAddFailsOnTable(): void
    {
        $importEntity = new ImportEntity();

        $this->tableMock->expects($this->once())->method('add')
            ->with($importEntity)->willReturn(false);
        $this->tableMock->expects($this->never())->method('getLastInsertValue');

        $this->service->add($importEntity);
    }

    public function testAddSucceedsOnTable(): void
    {
        $importEntity = new ImportEntity();

        $this->tableMock->expects($this->once())->method('add')->with($importEntity)->willReturn(true);
        $this->tableMock->expects($this->once())->method('getLastInsertValue');

        $this->assertTrue($this->service->add($importEntity));
    }

    public function testGetFirstUnprocessed(): void
    {
        $this->tableMock->expects($this->once())->method('getFirstUnprocessed');
        $this->service->getFirstUnprocessed();
    }

    public function testMarkAsProcessing(): void
    {
        $processingStartedAt = new \DateTime('2020-01-01');

        $this->tableMock->expects($this->once())->method('markAsProcessing')->with(10, $processingStartedAt);

        $this->service->markAsProcessing(10, $processingStartedAt);
    }

    public function testMarkAsProcessed(): void
    {
        $processingEndedAt = new \DateTime('2020-01-01');
        $parseResult = new ParseResult();

        $this->tableMock->expects($this->once())
            ->method('markAsProcessed')->with(10, $parseResult, $processingEndedAt);

        $this->service->markAsProcessed(10, $parseResult, $processingEndedAt);
    }
}

