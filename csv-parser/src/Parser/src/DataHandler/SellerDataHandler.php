<?php

declare(strict_types=1);

namespace Parser\DataHandler;

class SellerDataHandler extends AbstractHandler
{
    protected function getDataToPost(array $row): ?array
    {
        return [
            "id" => $row[1],
            "firstName" => $row[2],
            "lastName" => $row[3],
            "dateJoined" => $row[4],
            "country" => $row[5],
        ];
    }

    protected function getEndpointUri(): string
    {
        return $this->config['api']['seller'];
    }
}
