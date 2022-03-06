<?php

declare(strict_types=1);

namespace Sale\Service;

use Dujche\MezzioHelperLib\Entity\EntityInterface;
use Dujche\MezzioHelperLib\Service\AddHandlerInterface;
use Sale\Entity\SaleEntity;
use Sale\Entity\TotalsEntity;
use Sale\Table\TotalsTable;

class TotalService implements AddHandlerInterface
{
    private TotalsTable $totalsTable;

    public function __construct(TotalsTable $totalsTable)
    {
        $this->totalsTable = $totalsTable;
    }

    /**
     * @param SaleEntity $entity
     * @return bool
     */
    public function add(EntityInterface $entity): bool
    {
        $totalEntity = new TotalsEntity();
        $totalEntity->setYear((int)$entity->getSaleDate()->format('Y'));
        $totalEntity->setNumberOfRecords(1);
        $totalEntity->setNetAmount($entity->getSaleNetAmount());
        $totalEntity->setGrossAmount($entity->getSaleGrossAmount());
        $totalEntity->setTaxAmount($entity->getSaleGrossAmount() - $entity->getSaleNetAmount());
        $totalEntity->setProfit($entity->getSaleNetAmount() - $entity->getSaleProductTotalCost());

        $existingYearData = $this->totalsTable->getByYear($totalEntity->getYear());

        return $existingYearData !== null ?
            $this->totalsTable->updateTotalsForYear($totalEntity, $existingYearData) :
            $this->totalsTable->add($totalEntity);
    }

    public function getByYear(int $year): ?TotalsEntity
    {
        return $this->totalsTable->getByYear($year);
    }
}
