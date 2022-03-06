<?php

declare(strict_types=1);

namespace Parser\Table;

use DateTime;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\Adapter\Driver\ResultInterface;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Hydrator\HydratorInterface;
use Laminas\Log\LoggerInterface;
use Parser\DataHandler\ParseResult;
use Parser\Entity\ImportEntity;

class ImportTable extends TableGateway
{
    public const DB_TABLE_NAME = 'imports';

    private HydratorInterface $hydrator;

    private LoggerInterface $logger;

    public function __construct(AdapterInterface $adapter, HydratorInterface $hydrator, LoggerInterface $logger)
    {
        parent::__construct(static::DB_TABLE_NAME, $adapter);

        $this->logger = $logger;
        $this->hydrator = $hydrator;
    }

    public function add(ImportEntity $importEntity): bool
    {
        $data = $this->hydrator->extract($importEntity);

        $insert = $this->getSql()->insert();
        $insert->values($data);

        $this->logger->debug($insert->getSqlString($this->getAdapter()->getPlatform()));

        return $this->insertWith($insert) === 1;
    }

    public function getFirstUnprocessed(): ?ImportEntity
    {
        $select = $this->getSql()->select();
        $select->where->isNull('process_started_at');
        $select->order('id');

        $stmt = $this->getSql()->prepareStatementForSqlObject($select);
        $this->logger->debug($select->getSqlString($this->getAdapter()->getPlatform()));

        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult() && $result->count() > 0) {
            return $this->hydrator->hydrate($result->current(), new ImportEntity());
        }

        return null;
    }

    public function markAsProcessing(int $importEntityId, DateTime $processingStartedAt = null): bool
    {
        $update = $this->getSql()->update();
        $update->set(['process_started_at' => ($processingStartedAt ?: new DateTime())->format('Y-m-d H:i:s')])
            ->where->equalTo('id', $importEntityId);

        $this->logger->debug($update->getSqlString($this->getAdapter()->getPlatform()));

        return $this->updateWith($update) === 1;
    }

    public function markAsProcessed(
        int $importEntityId,
        ParseResult $parseResult,
        DateTime $processingEndedAt = null
    ): bool {
        $update = $this->getSql()->update();
        $update->set(
            [
                'process_ended_at' => ($processingEndedAt ?: new DateTime())->format('Y-m-d H:i:s'),
                'total_records' => $parseResult->getTotalRecords(),
                'valid_records' => $parseResult->getValidRecords(),
                'fully_imported_records' => $parseResult->getFullyImportedRecords(),
                'partially_imported_records' => $parseResult->getPartiallyImportedRecords(),
            ]
        )
        ->where->equalTo('id', $importEntityId);

        $this->logger->debug($update->getSqlString($this->getAdapter()->getPlatform()));

        return $this->updateWith($update) === 1;
    }
}
