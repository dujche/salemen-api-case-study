<?php

declare(strict_types=1);

namespace SellerTest\Handler;

use JsonException;
use Laminas\Db\Adapter\Exception\InvalidQueryException;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Log\LoggerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Seller\Entity\SellerEntity;
use Seller\Exception\RuntimeException;
use Seller\Handler\PostSellerHandler;
use Seller\Service\SellerService;

class PostSellerHandlerTest extends TestCase
{
    private SellerService $sellerService;

    private LoggerInterface $logger;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sellerService = $this->createMock(SellerService::class);
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
                'id' => 10,
                'firstName' => 'John',
                'lastName' => 'Doe',
                'country' => 'DE',
                'dateJoined' => '2020-01-01'
            ]);

        $this->sellerService->expects($this->once())->method('add')
            ->willThrowException(new InvalidQueryException('foo'));

        $postSellerHandler = new PostSellerHandler($this->sellerService, $this->logger);
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
                'id' => 10,
                'firstName' => 'John',
                'lastName' => 'Doe',
                'country' => 'DE',
                'dateJoined' => '2020-01-01'
            ]);

        $this->logger->expects($this->once())->method('err');

        $postSellerHandler = new PostSellerHandler($this->sellerService, $this->logger);
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
                'id' => 10,
                'firstName' => 'John',
                'lastName' => 'Doe',
                'country' => 'DE',
                'dateJoined' => '2020-01-01'
            ]);


        $this->sellerService->expects($this->once())->method('add')
            ->willReturn(true);

        $postSellerHandler = new PostSellerHandler($this->sellerService, $this->logger);
        $response = $postSellerHandler->handle($requestMock);

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals(201, $response->getStatusCode());
        self::assertEquals(
            '{"id":10,"firstName":"John","lastName":"Doe","country":"DE","dateJoined":"2020-01-01"}',
            $response->getBody()->getContents()
        );
    }
}
