<?php

declare(strict_types=1);

namespace ContactTest;

use Contact\ConfigProvider;
use Contact\Entity\ContactEntityHydrator;
use Contact\Filter\CreateContactPayloadFilter;
use Contact\Handler\GetContactHandler;
use Contact\Handler\PostContactHandler;
use Contact\Middleware\GetHandlerValidationMiddleware;
use Contact\Service\ContactService;
use Contact\Table\ContactTable;
use Dujche\MezzioHelperLib\Error\CustomErrorHandlerMiddleware;
use Dujche\MezzioHelperLib\Middleware\CreatePayloadValidationMiddleware;
use Laminas\Log\LoggerInterface;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use PHPUnit\Framework\TestCase;

class ConfigProviderTest extends TestCase
{
    public function testInvoke(): void
    {
        $configProvider = new ConfigProvider();
        $this->assertEquals(
            [
                'dependencies' => [
                    'invokables' => [
                        ContactEntityHydrator::class,
                        CreateContactPayloadFilter::class,
                        GetHandlerValidationMiddleware::class,
                    ],
                    'factories' => [
                        ContactTable::class => ConfigAbstractFactory::class,
                        ContactService::class => ConfigAbstractFactory::class,
                        GetContactHandler::class => ConfigAbstractFactory::class,
                        PostContactHandler::class => ConfigAbstractFactory::class,
                        CreatePayloadValidationMiddleware::class => ConfigAbstractFactory::class,
                        CustomErrorHandlerMiddleware::class => ConfigAbstractFactory::class,
                    ],
                ],
                ConfigAbstractFactory::class => [
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
                ]
            ],
            $configProvider()
        );
    }
}
