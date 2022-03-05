<?php

declare(strict_types=1);

namespace Contact\Handler;

use Contact\Entity\ContactEntity;
use Contact\Service\ContactService;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class GetContactHandler implements RequestHandlerInterface
{
    private ContactService $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();

        return new JsonResponse(
            array_map(
                static function (ContactEntity $contactEntity) {
                    return $contactEntity->toArray();
                },
                ($queryParams['sellerId'] ?? null) ?
                    $this->contactService->getAllBySellerId((int) $queryParams['sellerId']) :
                    $this->contactService->getAll()
            )
        );
    }
}
