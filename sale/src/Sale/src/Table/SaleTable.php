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

class SaleTable extends TableGateway
{
    public const DB_TABLE_NAME = 'sales';

    private HydratorInterface $hydrator;

    private LoggerInterface $logger;

    public function __construct(AdapterInterface $adapter, HydratorInterface $hydrator, LoggerInterface $logger)
    {
        parent::__construct(static::DB_TABLE_NAME, $adapter);

        $this->logger = $logger;
        $this->hydrator = $hydrator;
    }

    public function add(EntityInterface $saleEntity): bool
    {
        $data = $this->hydrator->extract($saleEntity);

        $insert = $this->getSql()->insert();
        $insert->values($data);

        $this->logger->debug($insert->getSqlString($this->getAdapter()->getPlatform()));

        return $this->insertWith($insert) === 1;
    }

    public function getAll(): ?HydratingResultSet
    {
        $select = $this->getSql()->select();
        return $this->selectMany($select);
    }

    public function getAllBySellerId(int $sellerId): ?HydratingResultSet
    {
        $select = $this->getSql()->select();
        $select->where->equalTo('seller_id', $sellerId);

        return $this->selectMany($select);
    }

    public function getAllByYear(int $year): ?HydratingResultSet
    {
        $select = $this->getSql()->select();
        $select->where->greaterThanOrEqualTo('sale_date', $year . '-01-01')
            ->and->lessThan('sale_date', ($year + 1) . '-01-01');

        return $this->selectMany($select);
    }

    /**
     * @param Select $select
     * @return HydratingResultSet|null
     */
    protected function selectMany(Select $select): ?HydratingResultSet
    {
        $stmt = $this->getSql()->prepareStatementForSqlObject($select);
        $this->logger->debug($select->getSqlString($this->getAdapter()->getPlatform()));

        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult() && $result->count() > 0) {
            return (new HydratingResultSet($this->hydrator, new SaleEntity()))->initialize($result);
        }

        return null;
    }

}
