<?php

declare(strict_types=1);

namespace Parser\Handler;

use DateTime;
use Dujche\MezzioHelperLib\Exception\RuntimeException;
use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Log\LoggerInterface;
use Parser\Entity\ImportEntity;
use Parser\Service\ImportService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class UploadHandler implements RequestHandlerInterface
{
    private ImportService $importService;

    private LoggerInterface $logger;

    public function __construct(ImportService $importService, LoggerInterface $logger)
    {
        $this->importService = $importService;
        $this->logger = $logger;
    }

    /**
     * @throws RuntimeException|Exception
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $saveResult = $this->performSave($request);
        if ($saveResult === null) {
            $this->logger->err('Inserting csv file content into database failed.');
            throw new RuntimeException();
        }

        return new JsonResponse(
            $saveResult->toArray(),
            201
        );
    }


    /**
     * @param ServerRequestInterface $request
     * @return ImportEntity
     * @throws Exception
     */
    private function performSave(ServerRequestInterface $request): ?ImportEntity
    {
        $post = $request->getBody()->getContents();
        $importEntity = new ImportEntity();
        $importEntity->setContent(base64_encode(trim($post)));
        $importEntity->setImportedAt(new DateTime());

        return $this->importService->add($importEntity) ? $importEntity : null;
    }
}
