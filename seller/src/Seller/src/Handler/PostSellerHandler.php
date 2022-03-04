<?php

declare(strict_types=1);

namespace Seller\Handler;

use DateTime;
use Exception;
use Laminas\Db\Adapter\Exception\InvalidQueryException;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Seller\Entity\SellerEntity;
use Seller\Exception\RuntimeException;
use Seller\Service\SellerService;

class PostSellerHandler implements RequestHandlerInterface
{
    private SellerService $sellerService;

    private LoggerInterface $logger;

    public function __construct(SellerService $sellerService, LoggerInterface $logger)
    {
        $this->sellerService = $sellerService;
        $this->logger = $logger;
    }

    /**
     * @throws RuntimeException|Exception
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $post = $request->getParsedBody();

        try {
            $saveResult = $this->performSave($post);
        } catch (InvalidQueryException $invalidQueryException) {
            $this->logger->warn($invalidQueryException->getMessage());
            return new EmptyResponse(409);
        }
        if ($saveResult === null) {
            $this->logger->err('Inserting seller into database failed.');
            throw new RuntimeException();
        }

        return new JsonResponse(
            $saveResult->toArray(),
            201
        );
    }


    /**
     * @param array $post
     * @return SellerEntity|null
     * @throws Exception
     */
    private function performSave(array $post): ?SellerEntity
    {
        $sellerEntity = new SellerEntity();
        $sellerEntity->setId($post['id']);
        $sellerEntity->setFirstName($post['firstName']);
        $sellerEntity->setLastName($post['lastName']);
        $sellerEntity->setCountry($post['country']);
        $sellerEntity->setDateJoined(new DateTime($post['dateJoined']));

        return $this->sellerService->add($sellerEntity) ? $sellerEntity : null;
    }
}
