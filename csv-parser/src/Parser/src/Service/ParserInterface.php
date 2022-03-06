<?php

declare(strict_types=1);

namespace Parser\Service;

use Parser\DataHandler\ParseResult;

interface ParserInterface
{
    public function parse(string $fileContents): ParseResult;
}
