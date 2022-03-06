<?php

declare(strict_types=1);

namespace Parser\Service\Factory;

use Interop\Container\ContainerInterface;
use Laminas\Log\LoggerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Parser\DataHandler\ParseResult;
use Parser\Service\ParserInterface;
use Parser\Service\ParserService;

class ParserServiceFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): ParserInterface
    {
        $config = $container->get('config');
        return new ParserService(
            $container->get(LoggerInterface::class),
            array_map(
                static function (string $serviceIdentifier) use ($container) {
                    return $container->get($serviceIdentifier);
                },
                $config['data-handlers'],
            ),
            $container->get(ParseResult::class)
        );
    }
}
