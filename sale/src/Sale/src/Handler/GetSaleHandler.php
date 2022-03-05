<?php

declare(strict_types=1);

namespace Sale\Handler;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sale\Entity\SaleEntity;
use Sale\Service\SaleService;

class GetSaleHandler implements RequestHandlerInterface
{
    private SaleService $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();

        return new JsonResponse(
            array_map(
                static function (SaleEntity $saleEntity) {
                    return $saleEntity->toArray();
                },
                ($queryParams['sellerId'] ?? null) ?
                    $this->saleService->getAllBySellerId((int) $queryParams['sellerId']) :
                    $this->saleService->getAll()
            )
        );
    }
}
