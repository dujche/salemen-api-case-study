<?php

namespace Parser\Service;

use DateTime;
use Parser\DataHandler\ParseResult;
use Parser\Entity\ImportEntity;
use Parser\Table\ImportTable;

class ImportService
{
    private ImportTable $importTable;

    public function __construct(ImportTable $importTable)
    {
        $this->importTable = $importTable;
    }

    public function add(ImportEntity $importEntity): bool
    {
        $result = $this->importTable->add($importEntity);
        if ($result) {
            $importEntity->setId((int)$this->importTable->getLastInsertValue());
        }

        return $result;
    }

    public function getFirstUnprocessed(): ?ImportEntity
    {
        return $this->importTable->getFirstUnprocessed();
    }

    public function markAsProcessing(int $importEntityId, DateTime $processingStartedAt = null): bool
    {
        return $this->importTable->markAsProcessing($importEntityId, $processingStartedAt);
    }

    public function markAsProcessed(
        int $importEntityId,
        ParseResult $parseResult,
        DateTime $processingEndedAt = null
    ): bool {
        return $this->importTable->markAsProcessed($importEntityId, $parseResult, $processingEndedAt);
    }
}
