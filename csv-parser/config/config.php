<?php

declare(strict_types=1);

use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\ConfigAggregator\PhpFileProvider;
use Mezzio\Helper\ConfigProvider;

$aggregator = new ConfigAggregator([
    \Laminas\InputFilter\ConfigProvider::class,
    \Laminas\Filter\ConfigProvider::class,
    \Laminas\Hydrator\ConfigProvider::class,
    \Laminas\Db\ConfigProvider::class,
    \Laminas\Log\ConfigProvider::class,
    \Mezzio\Tooling\ConfigProvider::class,
    \Mezzio\Helper\ConfigProvider::class,
    \Mezzio\Router\FastRouteRouter\ConfigProvider::class,
    \Laminas\HttpHandlerRunner\ConfigProvider::class,
    \Laminas\Validator\ConfigProvider::class,
    // Include cache configuration
    ConfigProvider::class,
    \Mezzio\ConfigProvider::class,
    \Mezzio\Router\ConfigProvider::class,
    \Laminas\Diactoros\ConfigProvider::class,

    \Parser\ConfigProvider::class,
    \Dujche\MezzioHelperLib\ConfigProvider::class,

    // Load application config in a pre-defined order in such a way that local settings
    // overwrite global settings. (Loaded as first to last):
    //   - `global.php`
    //   - `*.global.php`
    //   - `local.php`
    //   - `*.local.php`
    new PhpFileProvider(realpath(__DIR__) . '/autoload/{{,*.}global,{,*.}local}.php'),
    // Load development config if it exists
    new PhpFileProvider(realpath(__DIR__) . '/development.config.php'),
]);

return $aggregator->getMergedConfig();
