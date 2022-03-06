<?php

declare(strict_types=1);

namespace Parser;

use Dujche\MezzioHelperLib\Error\CustomErrorHandlerMiddleware;
use Laminas\Http\Client;
use Laminas\Log\LoggerInterface;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Parser\Command\ParseCommand;
use Parser\DataHandler\ContactDataHandler;
use Parser\DataHandler\ParseResult;
use Parser\DataHandler\SaleDataHandler;
use Parser\DataHandler\SellerDataHandler;
use Parser\Entity\ImportEntityHydrator;
use Parser\Handler\UploadHandler;
use Parser\Middleware\UploadContentValidationMiddleware;
use Parser\Service\Factory\ParserServiceFactory;
use Parser\Service\ImportService;
use Parser\Service\ParserService;
use Parser\Table\ImportTable;

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
            'laminas-cli' => $this->getCliConfig(),
            'dependencies' => $this->getDependencyConfig(),
            ConfigAbstractFactory::class => $this->getConfigAbstractFactories(),
        ];
    }

    public function getCliConfig(): array
    {
        return [
            'commands' => [
                'parser:parse' => ParseCommand::class,
            ],
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
            'invokables' => [
                ImportEntityHydrator::class,
                Client::class,
                ParseResult::class,
            ],
            'factories' => [
                CustomErrorHandlerMiddleware::class => ConfigAbstractFactory::class,
                UploadContentValidationMiddleware::class => ConfigAbstractFactory::class,
                UploadHandler::class => ConfigAbstractFactory::class,
                ImportTable::class => ConfigAbstractFactory::class,
                ImportService::class => ConfigAbstractFactory::class,
                ParseCommand::class => ConfigAbstractFactory::class,
                ParserService::class => ParserServiceFactory::class,
                SellerDataHandler::class => ConfigAbstractFactory::class,
                ContactDataHandler::class => ConfigAbstractFactory::class,
                SaleDataHandler::class => ConfigAbstractFactory::class,
            ],
        ];
    }

    private function getConfigAbstractFactories(): array
    {
        return [
            UploadContentValidationMiddleware::class => [
                LoggerInterface::class,
            ],
            UploadHandler::class => [
                ImportService::class,
                LoggerInterface::class,
            ],
            CustomErrorHandlerMiddleware::class => [
                LoggerInterface::class,
            ],
            ImportTable::class => [
                'import-db',
                ImportEntityHydrator::class,
                LoggerInterface::class,
            ],
            ImportService::class => [
                ImportTable::class,
            ],
            ParseCommand::class => [
                ImportService::class,
                ParserService::class,
            ],
            SellerDataHandler::class => [
                LoggerInterface::class,
                Client::class,
                'config'
            ],
            ContactDataHandler::class => [
                LoggerInterface::class,
                Client::class,
                'config'
            ],
            SaleDataHandler::class => [
                LoggerInterface::class,
                Client::class,
                'config'
            ],
        ];
    }
}
