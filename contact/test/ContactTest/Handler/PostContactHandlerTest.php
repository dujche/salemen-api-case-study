<?php

declare(strict_types=1);

namespace ContactTest\Handler;

use Contact\Handler\PostContactHandler;
use Contact\Service\ContactService;
use Dujche\MezzioHelperLib\Exception\DuplicateRecordException;
use Dujche\MezzioHelperLib\Exception\RuntimeException;
use JsonException;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Log\LoggerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

class PostContactHandlerTest extends TestCase
{
    private ContactService $contactService;

    private LoggerInterface $logger;

    protected function setUp(): void
    {
        parent::setUp();

        $this->contactService = $this->createMock(ContactService::class);
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
                'fullName' => 'John Doe',
                'region' => 'Alaska',
                'contactDate' => '2022-01-01',
                'contactType' => 'Phone',
                'contactProductTypeOfferedId' => 100,
                'contactProductTypeOffered' => 'bar'
            ]);

        $this->contactService->expects($this->once())->method('add')
            ->willThrowException(new DuplicateRecordException('foo'));

        $postSellerHandler = new PostContactHandler($this->contactService, $this->logger);
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
                'fullName' => 'John Doe',
                'region' => 'Alaska',
                'contactDate' => '2022-01-01',
                'contactType' => 'Phone',
                'contactProductTypeOfferedId' => 100,
                'contactProductTypeOffered' => 'bar'
            ]);

        $this->logger->expects($this->once())->method('err');

        $postSellerHandler = new PostContactHandler($this->contactService, $this->logger);
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
                'fullName' => 'John Doe',
                'region' => 'Alaska',
                'contactDate' => '2022-01-01',
                'contactType' => 'Phone',
                'contactProductTypeOfferedId' => 100,
                'contactProductTypeOffered' => 'bar'
            ]);


        $this->contactService->expects($this->once())->method('add')
            ->willReturn(true);

        $postSellerHandler = new PostContactHandler($this->contactService, $this->logger);
        $response = $postSellerHandler->handle($requestMock);

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals(201, $response->getStatusCode());
        self::assertEquals(
            '{"uuid":"88b5d1cf-028a-4b0c-b6a4-1824cb071bf4","sellerId":14,"fullName":"John Doe","region":"Alaska","contactType":"Phone","contactDate":"2022-01-01","contactProductTypeOfferedId":100,"contactProductTypeOffered":"bar"}',
            $response->getBody()->getContents()
        );
    }
}
