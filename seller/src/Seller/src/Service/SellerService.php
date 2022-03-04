<?php

namespace Seller\Service;

use Seller\Entity\SellerEntity;
use Seller\Table\SellerTable;

class SellerService
{
    private SellerTable $sellerTable;

    public function __construct(SellerTable $sellerTable)
    {
        $this->sellerTable = $sellerTable;
    }

    public function add(SellerEntity $sellerEntity): bool
    {
        return $this->sellerTable->add($sellerEntity);
    }

    public function getAll(): array
    {
        $result = $this->sellerTable->getAll();
        if ($result === null) {
            return [];
        }

        $toReturn = [];
        foreach ($result as $item) {
            $toReturn[] = $item;
        }

        return $toReturn;
    }

    public function getById(int $sellerId): ?SellerEntity
    {
        return $this->sellerTable->getById($sellerId);
    }
}
