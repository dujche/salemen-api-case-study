<?php

declare(strict_types=1);

namespace Parser\DataHandler;

class ContactDataHandler extends AbstractHandler
{
    protected function getDataToPost(array $row): ?array
    {
        return [
            "uuid" => $row[0],
            "sellerId" => $row[1],
            "region" => $row[6],
            "contactDate" => $row[7],
            "fullName" => $row[8],
            'contactType' => $row[9],
            'contactProductTypeOfferedId' => $row[10],
            'contactProductTypeOffered' => $row[11]
        ];
    }

    protected function getEndpointUri(): string
    {
        return $this->config['api']['contact'];
    }
}
