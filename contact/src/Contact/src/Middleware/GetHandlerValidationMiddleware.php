<?php

declare(strict_types=1);

namespace Contact\Middleware;

use Dujche\MezzioHelperLib\Exception\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class GetHandlerValidationMiddleware implements MiddlewareInterface
{
    /**
     * @throws ValidationException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $queryParams = $request->getQueryParams();

        if (!empty($queryParams['sellerId'] ?? null) && !is_numeric($queryParams['sellerId'])) {
            throw new ValidationException('Invalid sellerId');
        }

        return $handler->handle($request);
    }
}
