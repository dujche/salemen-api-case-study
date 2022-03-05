<?php

declare(strict_types=1);

namespace SellerTest\Service;

use DateTime;
use Dujche\MezzioHelperLib\Exception\DuplicateRecordException;
use Laminas\Db\Adapter\Exception\InvalidQueryException;
use Laminas\Db\ResultSet\HydratingResultSet;
use PHPUnit\Framework\TestCase;
use Seller\Entity\SellerEntity;
use Seller\Service\SellerService;
use Seller\Table\SellerTable;

class SellerServiceTest extends TestCase
{
    public function testAddFailsOnTableWithDuplicateRecord(): void
    {
        $this->expectException(DuplicateRecordException::class);

        $sellerEntity = new SellerEntity();

        $tableMock = $this->createMock(SellerTable::class);
        $tableMock->expects($this->once())->method('add')
            ->with($sellerEntity)->willThrowException(new InvalidQueryException('foo'));
        $tableMock->expects($this->never())->method('getLastInsertValue');

        $service = new SellerService($tableMock);
        $service->add($sellerEntity);
    }

    public function testAddSucceedsOnTable(): void
    {
        $sellerEntity = new SellerEntity();

        $tableMock = $this->createMock(SellerTable::class);
        $tableMock->expects($this->once())->method('add')->with($sellerEntity)->willReturn(true);

        $service = new SellerService($tableMock);
        $this->assertTrue($service->add($sellerEntity));
    }

    public function testGetById(): void
    {
        $tableMock = $this->createMock(SellerTable::class);
        $tableMock->expects($this->once())->method('getById')->with(10)->willReturn(null);

        $service = new SellerService($tableMock);
        $this->assertNull($service->getById(10));
    }

    public function testGetAllNoResults(): void
    {
        $tableMock = $this->createMock(SellerTable::class);
        $tableMock->expects($this->once())->method('getAll')->with()->willReturn(null);

        $service = new SellerService($tableMock);
        $this->assertSame([], $service->getAll());
    }

    public function testGetAllWithResults(): void
    {
        $sellerEntity = new SellerEntity();
        $sellerEntity->setId(10);
        $sellerEntity->setFirstName('John');
        $sellerEntity->setLastName('Doe');
        $sellerEntity->setCountry('DE');
        $sellerEntity->setDateJoined(new DateTime('2020-01-01'));

        $resultSetMock = $this->createMock(HydratingResultSet::class);
        $resultSetMock->expects($this->exactly(2))->method('valid')
            ->willReturnOnConsecutiveCalls(true, false);

        $resultSetMock->expects($this->once())->method('current')
            ->willReturn($sellerEntity);

        $tableMock = $this->createMock(SellerTable::class);
        $tableMock->expects($this->once())->method('getAll')->with()->willReturn($resultSetMock);

        $service = new SellerService($tableMock);

        $this->assertSame(
            [
                $sellerEntity
            ],
            $service->getAll()
        );
    }
}
