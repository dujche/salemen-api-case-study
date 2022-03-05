<?php

declare(strict_types=1);

namespace Sale;

use Dujche\MezzioHelperLib\Error\CustomErrorHandlerMiddleware;
use Dujche\MezzioHelperLib\Middleware\CreatePayloadValidationMiddleware;
use Laminas\Hydrator\ClassMethodsHydrator;
use Laminas\Log\LoggerInterface;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Money\Currencies\ISOCurrencies;
use Money\Parser\DecimalMoneyParser;
use Sale\Entity\SaleEntityHydrator;
use Sale\Filter\CreateSalePayloadFilter;
use Sale\Handler\GetSaleHandler;
use Sale\Handler\GetTotalsHandler;
use Sale\Handler\PostSaleHandler;
use Sale\Service\SaleService;
use Sale\Service\TotalService;
use Sale\Table\SaleTable;
use Sale\Table\TotalsTable;

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
                SaleEntityHydrator::class,
                CreateSalePayloadFilter::class,
                ClassMethodsHydrator::class,
            ],
            'factories' => [
                SaleTable::class => ConfigAbstractFactory::class,
                TotalsTable::class => ConfigAbstractFactory::class,
                SaleService::class => ConfigAbstractFactory::class,
                TotalService::class => ConfigAbstractFactory::class,
                GetSaleHandler::class => ConfigAbstractFactory::class,
                PostSaleHandler::class => ConfigAbstractFactory::class,
                GetTotalsHandler::class => ConfigAbstractFactory::class,
                CreatePayloadValidationMiddleware::class => ConfigAbstractFactory::class,
                CustomErrorHandlerMiddleware::class => ConfigAbstractFactory::class,
            ]
        ];
    }

    private function getConfigAbstractFactories(): array
    {
        return [
            SaleTable::class => [
                'sale-db',
                SaleEntityHydrator::class,
                LoggerInterface::class,
            ],
            TotalsTable::class => [
                'sale-db',
                ClassMethodsHydrator::class,
                LoggerInterface::class,
            ],
            SaleService::class => [
                SaleTable::class,
                TotalService::class,
            ],
            TotalService::class => [
                TotalsTable::class,
            ],
            GetSaleHandler::class => [
                SaleService::class
            ],
            PostSaleHandler::class => [
                SaleService::class,
                LoggerInterface::class,
            ],
            GetTotalsHandler::class => [
                TotalService::class,
                SaleService::class,
            ],
            CreatePayloadValidationMiddleware::class => [
                CreateSalePayloadFilter::class,
                LoggerInterface::class,
            ],
            CustomErrorHandlerMiddleware::class => [
                LoggerInterface::class
            ],
        ];
    }
}
