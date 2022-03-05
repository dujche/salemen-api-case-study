<?php

declare(strict_types=1);

namespace Seller;

use Dujche\MezzioHelperLib\Error\CustomErrorHandlerMiddleware;
use Dujche\MezzioHelperLib\Middleware\CreatePayloadValidationMiddleware;
use Laminas\Log\LoggerInterface;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Seller\Entity\SellerEntityHydrator;
use Seller\Filter\CreateSellerPayloadFilter;
use Seller\Handler\GetSellerHandler;
use Seller\Handler\PostSellerHandler;
use Seller\Service\SellerService;
use Seller\Table\SellerTable;

class ConfigProvider
{
    /**
     * Return configuration for this component.
     *
     * @return array
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencyConfig(),
            ConfigAbstractFactory::class => $this->getConfigAbstractFactories(),
        ];
    }

    /**
     * Return dependency mappings for this component.
     *
     * @return array
     */
    public function getDependencyConfig(): array
    {
        return [
            // Legacy Zend Framework aliases
            'invokables' => [
                SellerEntityHydrator::class,
                CreateSellerPayloadFilter::class,
            ],
            'factories' => [
                SellerTable::class => ConfigAbstractFactory::class,
                SellerService::class => ConfigAbstractFactory::class,
                GetSellerHandler::class => ConfigAbstractFactory::class,
                PostSellerHandler::class => ConfigAbstractFactory::class,
                CreatePayloadValidationMiddleware::class => ConfigAbstractFactory::class,
                CustomErrorHandlerMiddleware::class => ConfigAbstractFactory::class,
            ]
        ];
    }

    private function getConfigAbstractFactories(): array
    {
        return [
            SellerTable::class => [
                'seller-db',
                SellerEntityHydrator::class,
                LoggerInterface::class,
            ],
            SellerService::class => [
                SellerTable::class,
            ],
            GetSellerHandler::class => [
                SellerService::class
            ],
            PostSellerHandler::class => [
                SellerService::class,
                LoggerInterface::class,
            ],
            CreatePayloadValidationMiddleware::class => [
                CreateSellerPayloadFilter::class,
                LoggerInterface::class,
            ],
            CustomErrorHandlerMiddleware::class => [
                LoggerInterface::class
            ],
        ];
    }
}
