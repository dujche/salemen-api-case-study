<?php

declare(strict_types=1);

namespace Contact;

use Contact\Entity\ContactEntityHydrator;
use Contact\Handler\GetContactHandler;
use Contact\Handler\PostContactHandler;
use Contact\Middleware\GetHandlerValidationMiddleware;
use Contact\Service\ContactService;
use Contact\Table\ContactTable;
use Contact\Filter\CreateContactPayloadFilter;
use Dujche\MezzioHelperLib\Error\CustomErrorHandlerMiddleware;
use Dujche\MezzioHelperLib\Middleware\CreatePayloadValidationMiddleware;
use Laminas\Log\LoggerInterface;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;

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
                ContactEntityHydrator::class,
                CreateContactPayloadFilter::class,
                GetHandlerValidationMiddleware::class
            ],
            'factories' => [
                ContactTable::class => ConfigAbstractFactory::class,
                ContactService::class => ConfigAbstractFactory::class,
                GetContactHandler::class => ConfigAbstractFactory::class,
                PostContactHandler::class => ConfigAbstractFactory::class,
                CreatePayloadValidationMiddleware::class => ConfigAbstractFactory::class,
                CustomErrorHandlerMiddleware::class => ConfigAbstractFactory::class,
            ]
        ];
    }

    private function getConfigAbstractFactories(): array
    {
        return [
            ContactTable::class => [
                'contact-db',
                ContactEntityHydrator::class,
                LoggerInterface::class,
            ],
            ContactService::class => [
                ContactTable::class,
            ],
            GetContactHandler::class => [
                ContactService::class
            ],
            PostContactHandler::class => [
                ContactService::class,
                LoggerInterface::class,
            ],
            CreatePayloadValidationMiddleware::class => [
                CreateContactPayloadFilter::class,
                LoggerInterface::class,
            ],
            CustomErrorHandlerMiddleware::class => [
                LoggerInterface::class
            ],
        ];
    }
}
