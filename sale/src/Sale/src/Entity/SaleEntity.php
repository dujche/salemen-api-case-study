<?php

declare(strict_types=1);

namespace Sale\Entity;

use DateTime;
use Dujche\MezzioHelperLib\Entity\EntityInterface;

class SaleEntity implements EntityInterface
{
    private string $uuid;

    private int $sellerId;

    private float $saleNetAmount;

    private float $saleGrossAmount;

    private float $saleTaxRatePercentage;

    private float $saleProductTotalCost;

    private DateTime $saleDate;

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
     * @return float
     */
    public function getSaleNetAmount(): float
    {
        return $this->saleNetAmount;
    }

    /**
     * @param $saleNetAmount
     */
    public function setSaleNetAmount($saleNetAmount): void
    {
        $this->saleNetAmount = (float) $saleNetAmount;
    }

    /**
     * @return float
     */
    public function getSaleGrossAmount(): float
    {
        return $this->saleGrossAmount;
    }

    /**
     * @param $saleGrossAmount
     */
    public function setSaleGrossAmount($saleGrossAmount): void
    {
        $this->saleGrossAmount = (float) $saleGrossAmount;
    }

    /**
     * @return float
     */
    public function getSaleTaxRatePercentage(): float
    {
        return $this->saleTaxRatePercentage;
    }

    /**
     * @param $saleTaxRatePercentage
     */
    public function setSaleTaxRatePercentage($saleTaxRatePercentage): void
    {
        $this->saleTaxRatePercentage = (float) $saleTaxRatePercentage;
    }

    /**
     * @return float
     */
    public function getSaleProductTotalCost(): float
    {
        return $this->saleProductTotalCost;
    }

    /**
     * @param $saleProductTotalCost
     */
    public function setSaleProductTotalCost($saleProductTotalCost): void
    {
        $this->saleProductTotalCost = (float) $saleProductTotalCost;
    }

    /**
     * @return DateTime
     */
    public function getSaleDate(): DateTime
    {
        return $this->saleDate;
    }

    /**
     * @param DateTime $saleDate
     */
    public function setSaleDate(DateTime $saleDate): void
    {
        $this->saleDate = $saleDate;
    }

    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'sellerId' => $this->sellerId,
            'saleNetAmount' => $this->saleNetAmount,
            'saleGrossAmount' => $this->saleGrossAmount,
            'saleTaxRatePercentage' => $this->saleTaxRatePercentage,
            'saleProductTotalCost' => $this->saleProductTotalCost,
            'saleDate' => $this->saleDate->format('Y-m-d'),
        ];
    }
}
