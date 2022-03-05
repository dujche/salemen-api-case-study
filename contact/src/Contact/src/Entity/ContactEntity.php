<?php

declare(strict_types=1);

namespace Contact\Entity;

use DateTime;
use Dujche\MezzioHelperLib\Entity\EntityInterface;

class ContactEntity implements EntityInterface
{
    private string $uuid;

    private int $sellerId;

    private string $fullName;

    private string $region;

    private DateTime $contactDate;

    private string $contactType;

    private int $contactProductTypeOfferedId;

    private string $contactProductTypeOffered;

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     */
    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    /**
     * @return int
     */
    public function getSellerId(): int
    {
        return $this->sellerId;
    }

    /**
     * @param $sellerId
     */
    public function setSellerId($sellerId): void
    {
        $this->sellerId = (int) $sellerId;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     */
    public function setFullName(string $fullName): void
    {
        $this->fullName = $fullName;
    }

    /**
     * @return string
     */
    public function getRegion(): string
    {
        return $this->region;
    }

    /**
     * @param string $region
     */
    public function setRegion(string $region): void
    {
        $this->region = $region;
    }

    /**
     * @return DateTime
     */
    public function getContactDate(): DateTime
    {
        return $this->contactDate;
    }

    /**
     * @param DateTime $contactDate
     */
    public function setContactDate(DateTime $contactDate): void
    {
        $this->contactDate = $contactDate;
    }

    /**
     * @return string
     */
    public function getContactType(): string
    {
        return $this->contactType;
    }

    /**
     * @param string $contactType
     */
    public function setContactType(string $contactType): void
    {
        $this->contactType = $contactType;
    }

    /**
     * @return int
     */
    public function getContactProductTypeOfferedId(): int
    {
        return $this->contactProductTypeOfferedId;
    }

    /**
     * @param $contactProductTypeOfferedId
     */
    public function setContactProductTypeOfferedId($contactProductTypeOfferedId): void
    {
        $this->contactProductTypeOfferedId = (int) $contactProductTypeOfferedId;
    }

    /**
     * @return string
     */
    public function getContactProductTypeOffered(): string
    {
        return $this->contactProductTypeOffered;
    }

    /**
     * @param string $contactProductTypeOffered
     */
    public function setContactProductTypeOffered(string $contactProductTypeOffered): void
    {
        $this->contactProductTypeOffered = $contactProductTypeOffered;
    }

    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'sellerId' => $this->sellerId,
            'fullName' => $this->fullName,
            'region' => $this->region,
            'contactType' => $this->contactType,
            'contactDate' => $this->contactDate->format('Y-m-d'),
            'contactProductTypeOfferedId' => $this->contactProductTypeOfferedId,
            'contactProductTypeOffered' => $this->contactProductTypeOffered
        ];
    }
}
