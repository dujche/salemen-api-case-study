<?php

declare(strict_types=1);

namespace Parser\DataHandler;

use Laminas\Http\Client;
use Laminas\Http\Request;
use Laminas\Log\LoggerInterface;

abstract class AbstractHandler implements DataHandlerInterface
{
    protected LoggerInterface $logger;

    protected Client $httpClient;

    protected array $config;

    public function __construct(LoggerInterface $logger, Client $httpClient, array $config)
    {
        $this->logger = $logger;

        $this->httpClient = $httpClient;

        $this->config = $config;
    }

    /**
     * @throws JsonException
     */
    public function handle(array $row): bool
    {
        $dataToPost = $this->getDataToPost($row);

        if ($dataToPost === null) {
            $this->logger->info("No data to post. Skipping.");
            return true;
        }

        $this->logger->info(
            sprintf(
                "Posting %s to %s",
                json_encode($dataToPost, JSON_UNESCAPED_UNICODE),
                $this->getEndpointUri()
            )
        );

        $this->initializeHttpClient($this->getEndpointUri());

        $this->httpClient->setRawBody(json_encode($dataToPost, JSON_UNESCAPED_UNICODE));

        $response = $this->httpClient->send();
        $this->logger->info("Response status code: " . $response->getStatusCode());
        if (!in_array($response->getStatusCode(), [201, 409])) {
            $this->logger->warn("Request unsuccessful - Response received: " . $response->getBody());
            return false;
        }

        return true;
    }

    abstract protected function getDataToPost(array $row): ?array;

    abstract protected function getEndpointUri(): string;

    /**
     * @param string $uri
     * @return void
     */
    protected function initializeHttpClient(string $uri): void
    {
        $this->httpClient->resetParameters(true);
        $this->httpClient->setEncType('application/json');
        $this->httpClient->setUri($uri);
        $this->httpClient->setMethod(Request::METHOD_POST);
    }
}