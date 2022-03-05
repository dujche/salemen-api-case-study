<?php

declare(strict_types=1);

namespace Sale\Handler;

use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sale\Entity\SaleEntity;
use Sale\Service\SaleService;
use Sale\Service\TotalService;

class GetTotalsHandler implements RequestHandlerInterface
{
    private TotalService $totalService;

    private SaleService $saleService;

    public function __construct(TotalService $totalService, SaleService $saleService)
    {
        $this->totalService = $totalService;
        $this->saleService = $saleService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $year = $request->getAttribute('year');

        $totalEntity = $this->totalService->getByYear((int) $year);

        if ($totalEntity === null) {
            return new EmptyResponse(404);
        }

        return new JsonResponse(
            [
                'totals' => $totalEntity->toArray(),
                'items' => array_map(
                    static function (SaleEntity $saleEntity) {
                        return $saleEntity->toArray();
                    },
                    $this->saleService->getAllByYear((int) $year)
                )
            ]
        );
    }
}
