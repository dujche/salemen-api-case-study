<?php

namespace Seller\Service;

use Dujche\MezzioHelperLib\Entity\EntityInterface;
use Dujche\MezzioHelperLib\Exception\DuplicateRecordException;
use Dujche\MezzioHelperLib\Service\AddHandlerInterface;
use Laminas\Db\Adapter\Exception\InvalidQueryException;
use Seller\Entity\SellerEntity;
use Seller\Table\SellerTable;

class SellerService implements AddHandlerInterface
{
    private SellerTable $sellerTable;

    public function __construct(SellerTable $sellerTable)
    {
        $this->sellerTable = $sellerTable;
    }

    /**
     * @throws DuplicateRecordException
     */
    public function add(EntityInterface $entity): bool
    {
        try {
            return $this->sellerTable->add($entity);
        } catch (InvalidQueryException $invalidQueryException) {
            throw new DuplicateRecordException($invalidQueryException->getMessage());
        }
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
