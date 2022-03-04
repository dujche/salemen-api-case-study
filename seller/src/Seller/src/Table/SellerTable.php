<?php

declare(strict_types=1);

namespace Seller\Table;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\Adapter\Driver\ResultInterface;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Hydrator\HydratorInterface;
use Laminas\Log\LoggerInterface;
use Seller\Entity\SellerEntity;

class SellerTable extends TableGateway
{
    public const DB_TABLE_NAME = 'sellers';

    private HydratorInterface $hydrator;

    private LoggerInterface $logger;

    public function __construct(AdapterInterface $adapter, HydratorInterface $hydrator, LoggerInterface $logger)
    {
        parent::__construct(static::DB_TABLE_NAME, $adapter);

        $this->logger = $logger;
        $this->hydrator = $hydrator;
    }

    public function add(SellerEntity $sellerEntity): bool
    {
        $data = $this->hydrator->extract($sellerEntity);

        $insert = $this->getSql()->insert();
        $insert->values($data);

        $this->logger->debug($insert->getSqlString($this->getAdapter()->getPlatform()));

        return $this->insertWith($insert) === 1;
    }

    public function getAll(): ?HydratingResultSet
    {
        $select = $this->getSql()->select();
        $stmt = $this->getSql()->prepareStatementForSqlObject($select);
        $this->logger->debug($select->getSqlString($this->getAdapter()->getPlatform()));

        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult() && $result->count() > 0) {
            return (new HydratingResultSet($this->hydrator, new SellerEntity()))->initialize($result);
        }

        return null;
    }

    public function getById(int $sellerId): ?SellerEntity
    {
        $select = $this->getSql()->select();
        $select->where->equalTo('id', $sellerId);

        $stmt = $this->getSql()->prepareStatementForSqlObject($select);
        $this->logger->debug($select->getSqlString($this->getAdapter()->getPlatform()));

        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult() && $result->count() > 0) {
            return $this->hydrator->hydrate($result->current(), new SellerEntity());
        }

        return null;
    }
}
