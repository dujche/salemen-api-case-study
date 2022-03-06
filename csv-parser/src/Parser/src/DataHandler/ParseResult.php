<?php

declare(strict_types=1);

namespace Parser\DataHandler;

class ParseResult
{
    private int $totalRecords;

    private int $validRecords;

    private int $fullyImportedRecords;

    private int $partiallyImportedRecords;

    public function initialize(): void
    {
        $this->totalRecords = 0;
        $this->validRecords = 0;
        $this->fullyImportedRecords = 0;
        $this->partiallyImportedRecords = 0;
    }

    /**
     * @return int
     */
    public function getTotalRecords(): int
    {
        return $this->totalRecords;
    }

    /**
     * @param int $totalRecords
     */
    public function setTotalRecords(int $totalRecords): void
    {
        $this->totalRecords = $totalRecords;
    }

    /**
     * @return int
     */
    public function getValidRecords(): int
    {
        return $this->validRecords;
    }

    /**
     * @param int $validRecords
     */
    public function setValidRecords(int $validRecords): void
    {
        $this->validRecords = $validRecords;
    }

    /**
     * @return int
     */
    public function getFullyImportedRecords(): int
    {
        return $this->fullyImportedRecords;
    }

    /**
     * @param int $fullyImportedRecords
     */
    public function setFullyImportedRecords(int $fullyImportedRecords): void
    {
        $this->fullyImportedRecords = $fullyImportedRecords;
    }

    /**
     * @return int
     */
    public function getPartiallyImportedRecords(): int
    {
        return $this->partiallyImportedRecords;
    }

    /**
     * @param int $partiallyImportedRecords
     */
    public function setPartiallyImportedRecords(int $partiallyImportedRecords): void
    {
        $this->partiallyImportedRecords = $partiallyImportedRecords;
    }
}