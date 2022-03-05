<?php

declare(strict_types=1);

namespace Sale\Table;

use Dujche\MezzioHelperLib\Entity\EntityInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\Adapter\Driver\ResultInterface;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Db\Sql\Select;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Hydrator\HydratorInterface;
use Laminas\Log\LoggerInterface;
use Sale\Entity\SaleEntity;
use Sale\Entity\TotalsEntity;

class TotalsTable extends TableGateway
{
    public const DB_TABLE_NAME = 'totals';

    private HydratorInterface $hydrator;

    private LoggerInterface $logger;

    public function __construct(AdapterInterface $adapter, HydratorInterface $hydrator, LoggerInterface $logger)
    {
        parent::__construct(static::DB_TABLE_NAME, $adapter);

        $this->logger = $logger;
        $this->hydrator = $hydrator;
    }

    public function add(TotalsEntity $totalsEntity): bool
    {
        $data = $this->hydrator->extract($totalsEntity);

        $insert = $this->getSql()->insert();
        $insert->values($data);

        $this->logger->debug($insert->getSqlString($this->getAdapter()->getPlatform()));

        return $this->insertWith($insert) === 1;
    }

    public function updateTotalsForYear(TotalsEntity $totalsEntity, TotalsEntity $existingYearData): bool
    {
        $update = $this->getSql()->update();
        $update->set(
            [
                'net_amount' => $existingYearData->getNetAmount() + $totalsEntity->getNetAmount(),
                'gross_amount' => $existingYearData->getGrossAmount() + $totalsEntity->getGrossAmount(),
                'tax_amount' => $existingYearData->getTaxAmount() + $totalsEntity->getTaxAmount(),
                'profit' => $existingYearData->getProfit() + $totalsEntity->getProfit(),

            ]
        )->where->equalTo('year', $totalsEntity->getYear());

        $this->logger->debug($update->getSqlString($this->getAdapter()->getPlatform()));

        return $this->updateWith($update) === 1;
    }

    public function getByYear(int $year): ?TotalsEntity
    {
        $select = $this->getSql()->select();
        $select->where->equalTo('year', $year);

        $stmt = $this->getSql()->prepareStatementForSqlObject($select);
        $this->logger->debug($select->getSqlString($this->getAdapter()->getPlatform()));

        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult() && $result->count() > 0) {
            return $this->hydrator->hydrate($result->current(), new TotalsEntity());
        }

        return null;
    }
}
