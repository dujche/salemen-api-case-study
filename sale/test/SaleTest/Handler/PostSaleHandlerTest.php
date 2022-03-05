<?php

declare(strict_types=1);

namespace SaleTest\Handler;

use Dujche\MezzioHelperLib\Exception\DuplicateRecordException;
use Dujche\MezzioHelperLib\Exception\RuntimeException;
use JsonException;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Log\LoggerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Sale\Handler\PostSaleHandler;
use Sale\Service\SaleService;

class PostSaleHandlerTest extends TestCase
{
    private SaleService $saleService;

    private LoggerInterface $logger;

    protected function setUp(): void
    {
        parent::setUp();

        $this->saleService = $this->createMock(SaleService::class);
        $this->logger = $this->createMock(LoggerInterface::class);
    }

    /**
     * @throws RuntimeException
     * @throws JsonException
     */
    public function testResponseOnDuplicateSeller(): void
    {
        $requestMock = $this->createMock(ServerRequestInterface::class);
        $requestMock->expects($this->once())->method('getParsedBody')
            ->willReturn([
                'uuid' => '88b5d1cf-028a-4b0c-b6a4-1824cb071bf4',
                'sellerId' => 14,
                'saleNetAmount' => 12.03,
                'saleGrossAmount' => 15.77,
                'saleTaxRatePercentage' => 0.19,
                'saleProductTotalCost' => 9.99,
                'saleDate' => '2022-01-01',
            ]);

        $this->saleService->expects($this->once())->method('add')
            ->willThrowException(new DuplicateRecordException('foo'));

        $postSellerHandler = new PostSaleHandler($this->saleService, $this->logger);
        $response = $postSellerHandler->handle($requestMock);

        self::assertInstanceOf(EmptyResponse::class, $response);
        self::assertEquals(409, $response->getStatusCode());
    }

    public function testExceptionThrownOnDatabaseProblem(): void
    {
        $this->expectException(RuntimeException::class);

        $requestMock = $this->createMock(ServerRequestInterface::class);
        $requestMock->expects($this->once())->method('getParsedBody')
            ->willReturn([
                'uuid' => '88b5d1cf-028a-4b0c-b6a4-1824cb071bf4',
                'sellerId' => 14,
                'saleNetAmount' => 12.03,
                'saleGrossAmount' => 15.77,
                'saleTaxRatePercentage' => 0.19,
                'saleProductTotalCost' => 9.99,
                'saleDate' => '2022-01-01',
            ]);

        $this->logger->expects($this->once())->method('err');

        $postSellerHandler = new PostSaleHandler($this->saleService, $this->logger);
        $postSellerHandler->handle($requestMock);
    }

    /**
     * @throws RuntimeException
     * @throws JsonException
     */
    public function testResponse(): void
    {
        $requestMock = $this->createMock(ServerRequestInterface::class);
        $requestMock->expects($this->once())->method('getParsedBody')
            ->willReturn([
                'uuid' => '88b5d1cf-028a-4b0c-b6a4-1824cb071bf4',
                'sellerId' => 14,
                'saleNetAmount' => 12.03,
                'saleGrossAmount' => 15.77,
                'saleTaxRatePercentage' => 0.19,
                'saleProductTotalCost' => 9.99,
                'saleDate' => '2022-01-01',
            ]);


        $this->saleService->expects($this->once())->method('add')
            ->willReturn(true);

        $postSellerHandler = new PostSaleHandler($this->saleService, $this->logger);
        $response = $postSellerHandler->handle($requestMock);

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals(201, $response->getStatusCode());
        self::assertEquals(
            '{"uuid":"88b5d1cf-028a-4b0c-b6a4-1824cb071bf4","sellerId":14,"saleNetAmount":12.03,"saleGrossAmount":15.77,"saleTaxRatePercentage":0.19,"saleProductTotalCost":9.99,"saleDate":"2022-01-01"}',
            $response->getBody()->getContents()
        );
    }
}
