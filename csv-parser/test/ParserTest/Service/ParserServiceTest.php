<?php

declare(strict_types=1);

namespace ParserTest\Service;

use Laminas\Log\LoggerInterface;
use Parser\DataHandler\ParseResult;
use Parser\DataHandler\SaleDataHandler;
use Parser\DataHandler\SellerDataHandler;
use Parser\Service\ParserService;
use PHPUnit\Framework\TestCase;

class ParserServiceTest extends TestCase
{
    public const EXAMPLE_CSV = <<<TEXT
uuid;seller_id;seller_firstname;seller_lastname;date_joined;country;contact_region;contact_date;contact_customer_fullname;contact_type;contact_product_type_offered_id;contact_product_type_offered;sale_net_amount;sale_gross_amount;sale_tax_rate;sale_product_total_cost
8a419b28-267f-49c4-b652-d0433d20fef4;23;Hans;Müller;2018-08-17;DE;Thüringen;2021-12-04;Peter Grayson;Phone;122;Canned sausages;293.12;367.30;0.19;187.23
34ce1e6f-9db6-4a8b-bd64-fc87a68e6f03;21;Manfred;Schmidt;2015-12-01;DE;Bayern;2021-12-03;Stefan Herold;E-Mail;156;Spätzle;;;;
;21;Manfred;Schmidt;2015-12-01;DE;Bayern;2021-12-03;Stefan Herold;E-Mail;156;Spätzle;;;;
TEXT;

    private LoggerInterface $logger;

    private ParserService $service;

    private SellerDataHandler $sellerHandler;

    private SaleDataHandler $saleHandler;

    public function setUp(): void
    {
        parent::setUp();
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->sellerHandler = $this->createMock(SellerDataHandler::class);
        $this->saleHandler = $this->createMock(SaleDataHandler::class);
        $this->service = new ParserService(
            $this->logger,
            [$this->sellerHandler, $this->saleHandler],
            new ParseResult()
        );
    }

    public function testParseOnEmptyString()
    {
        $result = $this->service->parse('');
        $this->assertEquals(0, $result->getTotalRecords());
        $this->assertEquals(0, $result->getValidRecords());
        $this->assertEquals(0, $result->getFullyImportedRecords());
        $this->assertEquals(0, $result->getPartiallyImportedRecords());
    }

    public function testParseOnExampleCsvWithBothHandlersReturnFalse()
    {
        $result = $this->service->parse(self::EXAMPLE_CSV);
        $this->assertEquals(3, $result->getTotalRecords());
        $this->assertEquals(2, $result->getValidRecords());
        $this->assertEquals(0, $result->getFullyImportedRecords());
        $this->assertEquals(2, $result->getPartiallyImportedRecords());
    }

    public function testParseOnExampleCsvWithBothHandlersReturnTrue()
    {
        $this->sellerHandler->expects($this->exactly(2))->method('handle')->willReturn(true);
        $this->saleHandler->expects($this->exactly(2))->method('handle')->willReturn(true);

        $result = $this->service->parse(self::EXAMPLE_CSV);
        $this->assertEquals(3, $result->getTotalRecords());
        $this->assertEquals(2, $result->getValidRecords());
        $this->assertEquals(2, $result->getFullyImportedRecords());
        $this->assertEquals(0, $result->getPartiallyImportedRecords());
    }

    public function testParseOnExampleCsvWithPartialImport()
    {
        $this->sellerHandler->expects($this->exactly(2))->method('handle')->willReturn(true);
        $this->saleHandler->expects($this->exactly(2))
            ->method('handle')->willReturnOnConsecutiveCalls(true, false);

        $result = $this->service->parse(self::EXAMPLE_CSV);
        $this->assertEquals(3, $result->getTotalRecords());
        $this->assertEquals(2, $result->getValidRecords());
        $this->assertEquals(1, $result->getFullyImportedRecords());
        $this->assertEquals(1, $result->getPartiallyImportedRecords());
    }

    public function testParseOnExampleCsvWithExceptionInOneHandler()
    {
        $this->sellerHandler->expects($this->exactly(2))->method('handle')->willReturn(true);
        $this->saleHandler->expects($this->exactly(2))
            ->method('handle')->willThrowException(new \Exception('foo'));

        $this->logger->expects($this->exactly(3))->method('err')->withConsecutive(
            ['foo'],
            ['foo'],
            ['Empty uuid. Skipping row: ["","21","Manfred","Schmidt","2015-12-01","DE","Bayern","2021-12-03","Stefan Herold","E-Mail","156","Sp\u00e4tzle","","","",""]'],
        );

        $result = $this->service->parse(self::EXAMPLE_CSV);
        $this->assertEquals(3, $result->getTotalRecords());
        $this->assertEquals(2, $result->getValidRecords());
        $this->assertEquals(0, $result->getFullyImportedRecords());
        $this->assertEquals(2, $result->getPartiallyImportedRecords());
    }

}

