<?php

declare(strict_types=1);

namespace Sale\Service;

use Dujche\MezzioHelperLib\Entity\EntityInterface;
use Dujche\MezzioHelperLib\Exception\DuplicateRecordException;
use Dujche\MezzioHelperLib\Service\AddHandlerInterface;
use Laminas\Db\Adapter\Exception\InvalidQueryException;
use Laminas\Db\ResultSet\HydratingResultSet;
use Sale\Entity\SaleEntity;
use Sale\Table\SaleTable;

class SaleService implements AddHandlerInterface
{
    private SaleTable $saleTable;

    private TotalService $totalService;

    public function __construct(SaleTable $saleTable, TotalService $totalService)
    {
        $this->saleTable = $saleTable;
        $this->totalService = $totalService;
    }

    /**
     * @param SaleEntity $entity
     * @return bool
     * @throws DuplicateRecordException
     */
    public function add(EntityInterface $entity): bool
    {
        try {
            $saleTableAddResult = $this->saleTable->add($entity);
            if ($saleTableAddResult === false) {
                return false;
            }
        } catch (InvalidQueryException $invalidQueryException) {
            throw new DuplicateRecordException($invalidQueryException->getMessage());
        }

        return $this->totalService->add($entity);
    }

    public function getAll(): array
    {
        $result = $this->saleTable->getAll();
        return $this->toArray($result);
    }

    public function getAllBySellerId(int $sellerId): array
    {
        $result = $this->saleTable->getAllBySellerId($sellerId);
        return $this->toArray($result);
    }

    public function getAllByYear(int $year): array
    {
        $result = $this->saleTable->getAllByYear($year);
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
