<?php

declare(strict_types=1);

namespace Parser\Entity;

use DateTime;

class ImportEntity
{
    private ?int $id = null;

    private string $content;

    private DateTime $importedAt;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id ? (int) $id : null;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return DateTime
     */
    public function getImportedAt(): DateTime
    {
        return $this->importedAt;
    }

    /**
     * @param DateTime $importedAt
     */
    public function setImportedAt(DateTime $importedAt): void
    {
        $this->importedAt = $importedAt;
    }
}
