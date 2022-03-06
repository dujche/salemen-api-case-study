<?php

declare(strict_types=1);

namespace Parser\DataHandler;

use Laminas\Http\Response;

interface DataHandlerInterface
{
    public function handle(array $row): bool;
}
