<?php

declare(strict_types=1);

namespace ContactTest\Service;

use Contact\Entity\ContactEntity;
use Contact\Service\ContactService;
use Contact\Table\ContactTable;
use DateTime;
use Dujche\MezzioHelperLib\Exception\DuplicateRecordException;
use Laminas\Db\Adapter\Exception\InvalidQueryException;
use Laminas\Db\ResultSet\HydratingResultSet;
use PHPUnit\Framework\TestCase;

class ContactServiceTest extends TestCase
{
    public function testAddFailsOnTableWithDuplicateRecord(): void
    {
        $this->expectException(DuplicateRecordException::class);

        $sellerEntity = new ContactEntity();

        $tableMock = $this->createMock(ContactTable::class);
        $tableMock->expects($this->once())->method('add')
            ->with($sellerEntity)->willThrowException(new InvalidQueryException('foo'));
        $tableMock->expects($this->never())->method('getLastInsertValue');

        $service = new ContactService($tableMock);
        $service->add($sellerEntity);
    }

    public function testAddSucceedsOnTable(): void
    {
        $sellerEntity = new ContactEntity();

        $tableMock = $this->createMock(ContactTable::class);
        $tableMock->expects($this->once())->method('add')->with($sellerEntity)->willReturn(true);

        $service = new ContactService($tableMock);
        $this->assertTrue($service->add($sellerEntity));
    }

    public function testGetAllNoResults(): void
    {
        $tableMock = $this->createMock(ContactTable::class);
        $tableMock->expects($this->once())->method('getAll')->with()->willReturn(null);

        $service = new ContactService($tableMock);
        $this->assertSame([], $service->getAll());
    }

    public function testGetAllWithResults(): void
    {
        $contactEntity = new ContactEntity();
        $contactEntity->setUuid('620b9dcb-d751-4d77-a7e4-d4de97bd9ef3');
        $contactEntity->setSellerId(10);
        $contactEntity->setFullName('John Doe');
        $contactEntity->setRegion('Alaska');
        $contactEntity->setContactType('Email');
        $contactEntity->setContactDate(new DateTime('2020-01-01'));
        $contactEntity->setContactProductTypeOfferedId(100);
        $contactEntity->setContactProductTypeOffered('bar');

        $resultSetMock = $this->createMock(HydratingResultSet::class);
        $resultSetMock->expects($this->exactly(2))->method('valid')
            ->willReturnOnConsecutiveCalls(true, false);

        $resultSetMock->expects($this->once())->method('current')
            ->willReturn($contactEntity);

        $tableMock = $this->createMock(ContactTable::class);
        $tableMock->expects($this->once())->method('getAll')->with()->willReturn($resultSetMock);

        $service = new ContactService($tableMock);

        $this->assertSame(
            [
                $contactEntity
            ],
            $service->getAll()
        );
    }

    public function testGetAllBySellerIdNoResults(): void
    {
        $tableMock = $this->createMock(ContactTable::class);
        $tableMock->expects($this->once())->method('getAllBySellerId')
            ->with(10)->willReturn(null);

        $service = new ContactService($tableMock);
        $this->assertSame([], $service->getAllBySellerId(10));
    }
}
