<?php

declare(strict_types=1);

namespace Parser\Entity;

use DateTime;
use Dujche\MezzioHelperLib\Entity\EntityInterface;

class ImportEntity implements EntityInterface
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

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'importedAt' => $this->importedAt->format('Y-m-d H:i:s')
        ];
    }
}
