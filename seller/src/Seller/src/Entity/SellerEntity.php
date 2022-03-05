<?php

declare(strict_types=1);

namespace Seller\Entity;

use DateTime;
use Dujche\MezzioHelperLib\Entity\EntityInterface;

class SellerEntity implements EntityInterface
{
    private int $id;

    private string $firstName;

    private string $lastName;

    private string $country;

    private DateTime $dateJoined;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = (int) $id;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    /**
     * @return DateTime
     */
    public function getDateJoined(): DateTime
    {
        return $this->dateJoined;
    }

    /**
     * @param DateTime $dateJoined
     */
    public function setDateJoined(DateTime $dateJoined): void
    {
        $this->dateJoined = $dateJoined;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'country' => $this->country,
            'dateJoined' => $this->dateJoined->format('Y-m-d'),
        ];
    }
}
