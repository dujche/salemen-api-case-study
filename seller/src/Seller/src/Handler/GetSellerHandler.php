<?php

declare(strict_types=1);

namespace Seller\Handler;

use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Seller\Entity\SellerEntity;
use Seller\Service\SellerService;

class GetSellerHandler implements RequestHandlerInterface
{
    private SellerService $sellerService;

    public function __construct(SellerService $sellerService)
    {
        $this->sellerService = $sellerService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $single = $request->getAttribute('id');

        if ($single === null) {
            return new JsonResponse(
                array_map(
                    static function (SellerEntity $sellerEntity) {
                        return $sellerEntity->toArray();
                    },
                    $this->sellerService->getAll()
                )
            );
        }

        $sellerEntity = $this->sellerService->getById((int) $single);

        return $sellerEntity ? new JsonResponse($sellerEntity->toArray()) : new EmptyResponse(404);
    }
}
