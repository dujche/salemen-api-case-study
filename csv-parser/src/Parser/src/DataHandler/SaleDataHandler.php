<?php

declare(strict_types=1);

namespace Parser\DataHandler;

class SaleDataHandler extends AbstractHandler
{
    protected function getDataToPost(array $row): ?array
    {
        if (count($row) < 16 || empty($row[12]) || empty($row[13]) || empty($row[14]) || empty($row[15])) {
            return null;
        }

        return [
            "uuid" => $row[0],
            "sellerId" => $row[1],
            "saleNetAmount" => $row[12],
            "saleGrossAmount" => $row[13],
            "saleTaxRatePercentage" => $row[14],
            'saleProductTotalCost' => $row[15],
            'saleDate' => $row[7]
        ];
    }

    protected function getEndpointUri(): string
    {
        return $this->config['api']['sale'];
    }
}
