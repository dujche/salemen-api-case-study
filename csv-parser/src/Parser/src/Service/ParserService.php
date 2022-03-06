<?php

declare(strict_types=1);

namespace Parser\Service;

use JsonException;
use Laminas\Log\LoggerInterface;
use Parser\DataHandler\DataHandlerInterface;
use Parser\DataHandler\ParseResult;

class ParserService implements ParserInterface
{
    private LoggerInterface $logger;

    private array $dataHandlers;

    private ParseResult $parseResult;

    public function __construct(LoggerInterface $logger, array $dataHandlers, ParseResult $parseResult)
    {
        $this->logger = $logger;

        $this->dataHandlers = $dataHandlers;

        $this->parseResult = $parseResult;
    }

    /**
     * @throws JsonException
     */
    public function parse(string $fileContents): ParseResult
    {
        $rows = [];
        $lines = explode(PHP_EOL, $fileContents);
        $numberOfLines = count($lines);
        for ($count = 1; $count < $numberOfLines; $count++) {
            if (!empty(trim($lines[$count]))) {
                $rows[] = str_getcsv(trim($lines[$count]), ';');
            }
        }

        $this->parseResult->initialize();
        $this->parseResult->setTotalRecords(count($rows));

        foreach ($rows as $row) {
            if (empty($row[0])) {
                $this->logger->err('Empty uuid. Skipping row: ' . json_encode($row, JSON_THROW_ON_ERROR));
                continue;
            }

            $this->parseRow($row);
        }

        return $this->parseResult;
    }

    /**
     * @param $row
     * @return void
     */
    private function parseRow($row): void
    {
        $this->parseResult->setValidRecords($this->parseResult->getValidRecords() + 1);

        $rowFullyParsed = true;
        $rowPartiallyParsed = false;

        /** @var DataHandlerInterface $dataHandler */
        foreach ($this->dataHandlers as $dataHandler) {
            try {
                $this->logger->info('Calling ' . get_class($dataHandler));
                $response = $dataHandler->handle($row);
                $rowFullyParsed = $rowFullyParsed && $response;
                $rowPartiallyParsed = $rowPartiallyParsed || (!$response);
            } catch (\Throwable $exception) {
                $this->logger->err($exception->getMessage());
                $rowFullyParsed = false;
                $rowPartiallyParsed = true;
            }
        }

        if ($rowFullyParsed) {
            $this->parseResult->setFullyImportedRecords($this->parseResult->getFullyImportedRecords() + 1);
        }

        if ($rowPartiallyParsed) {
            $this->parseResult->setPartiallyImportedRecords($this->parseResult->getPartiallyImportedRecords() + 1);
        }
    }
}
