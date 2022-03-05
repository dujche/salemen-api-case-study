<?php

declare(strict_types=1);

use Dujche\MezzioHelperLib\Middleware\CreatePayloadValidationMiddleware;
use Mezzio\Application;
use Mezzio\MiddlewareFactory;
use Psr\Container\ContainerInterface;
use Sale\Handler\GetSaleHandler;
use Sale\Handler\GetTotalsHandler;
use Sale\Handler\PostSaleHandler;
use Sale\Middleware\GetHandlerValidationMiddleware;

/**
 * FastRoute route configuration
 *
 * @see https://github.com/nikic/FastRoute
 *
 * Setup routes with a single request method:
 *
 * $app->get('/', App\Handler\HomePageHandler::class, 'home');
 * $app->post('/album', App\Handler\AlbumCreateHandler::class, 'album.create');
 * $app->put('/album/{id:\d+}', App\Handler\AlbumUpdateHandler::class, 'album.put');
 * $app->patch('/album/{id:\d+}', App\Handler\AlbumUpdateHandler::class, 'album.patch');
 * $app->delete('/album/{id:\d+}', App\Handler\AlbumDeleteHandler::class, 'album.delete');
 *
 * Or with multiple request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class, ['GET', 'POST', ...], 'contact');
 */

return static function (Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {
    $app->get(
        '/sales',
        [
            GetHandlerValidationMiddleware::class,
            GetSaleHandler::class
        ],
        'api.sales.get'
    );

    $app->get(
        '/sales/{year:\d+}',
        [
            GetTotalsHandler::class
        ],
        'api.sales.get.totals'
    );

    $app->post(
        '/sales',
        [
            CreatePayloadValidationMiddleware::class,
            PostSaleHandler::class
        ],
        'api.sales.post'
    );
};
