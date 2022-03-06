<?php

declare(strict_types=1);

namespace Sale\Entity;

use Dujche\MezzioHelperLib\Entity\EntityInterface;

class TotalsEntity implements EntityInterface
{
    private int $year;

    private int $numberOfRecords;

    private float $netAmount;

    private float $grossAmount;

    private float $taxAmount;

    private float $profit;

    /**
     * @return int
     */
    public function getYear(): int
    {
        return $this->year;
    }

    /**
     * @param $year
     */
    public function setYear($year): void
    {
        $this->year = (int) $year;
    }

    /**
     * @return int
     */
    public function getNumberOfRecords(): int
    {
        return $this->numberOfRecords;
    }

    /**
     * @param $numberOfRecords
     */
    public function setNumberOfRecords($numberOfRecords): void
    {
        $this->numberOfRecords = (int) $numberOfRecords;
    }

    /**
     * @return float
     */
    public function getNetAmount(): float
    {
        return $this->netAmount;
    }

    /**
     * @param $netAmount
     */
    public function setNetAmount($netAmount): void
    {
        $this->netAmount = (float) $netAmount;
    }

    /**
     * @return float
     */
    public function getGrossAmount(): float
    {
        return $this->grossAmount;
    }

    /**
     * @param $grossAmount
     */
    public function setGrossAmount($grossAmount): void
    {
        $this->grossAmount = (float) $grossAmount;
    }

    /**
     * @return float
     */
    public function getTaxAmount(): float
    {
        return $this->taxAmount;
    }

    /**
     * @param $taxAmount
     */
    public function setTaxAmount($taxAmount): void
    {
        $this->taxAmount = (float) $taxAmount;
    }

    /**
     * @return float
     */
    public function getProfit(): float
    {
        return $this->profit;
    }

    /**
     * @param $profit
     */
    public function setProfit($profit): void
    {
        $this->profit = (float) $profit;
    }

    public function toArray(): array
    {
        return [
            'year' => $this->year,
            'numberOfRecords' => $this->numberOfRecords,
            'netAmount' => $this->netAmount,
            'grossAmount' => $this->grossAmount,
            'taxAmount' => $this->taxAmount,
            'profit' => $this->profit,
            'profitPercentage' =>
                (float) number_format(($this->profit / $this->netAmount) * 100, 2)
        ];
    }
}
