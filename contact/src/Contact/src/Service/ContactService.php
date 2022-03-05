<?php

namespace Contact\Service;

use Contact\Table\ContactTable;
use Dujche\MezzioHelperLib\Entity\EntityInterface;
use Dujche\MezzioHelperLib\Exception\DuplicateRecordException;
use Dujche\MezzioHelperLib\Service\AddHandlerInterface;
use Laminas\Db\Adapter\Exception\InvalidQueryException;
use Laminas\Db\ResultSet\HydratingResultSet;

class ContactService implements AddHandlerInterface
{
    private ContactTable $contactTable;

    public function __construct(ContactTable $contactTable)
    {
        $this->contactTable = $contactTable;
    }

    /**
     * @throws DuplicateRecordException
     */
    public function add(EntityInterface $contactEntity): bool
    {
        try {
            return $this->contactTable->add($contactEntity);
        } catch (InvalidQueryException $invalidQueryException) {
            throw new DuplicateRecordException($invalidQueryException->getMessage());
        }
    }

    public function getAll(): array
    {
        $result = $this->contactTable->getAll();
        return $this->toArray($result);
    }

    public function getAllBySellerId(int $sellerId): array
    {
        $result = $this->contactTable->getAllBySellerId($sellerId);
        return $this->toArray($result);
    }

    /**
     * @param HydratingResultSet|null $result
     * @return array
     */
    protected function toArray(?HydratingResultSet $result): array
    {
        if ($result === null) {
            return [];
        }

        $toReturn = [];
        foreach ($result as $item) {
            $toReturn[] = $item;
        }

        return $toReturn;
    }
}
