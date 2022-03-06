<?php

declare(strict_types=1);

namespace Parser\Middleware;

use Dujche\MezzioHelperLib\Exception\ValidationException;
use JsonException;
use Laminas\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class UploadContentValidationMiddleware implements MiddlewareInterface
{
    public const EXPECTED_HEADERS = [
        'uuid',
        'seller_id',
        'seller_firstname',
        'seller_lastname',
        'date_joined',
        'country',
        'contact_region',
        'contact_date',
        'contact_customer_fullname',
        'contact_type',
        'contact_product_type_offered_id',
        'contact_product_type_offered',
        'sale_net_amount',
        'sale_gross_amount',
        'sale_tax_rate',
        'sale_product_total_cost'
    ];

    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @throws ValidationException
     * @throws JsonException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $parsedBody = $request->getBody()->getContents();
        if (empty($parsedBody)) {
            throw new ValidationException("Invalid format of the uploaded file");
        }

        $lines = explode(PHP_EOL, $parsedBody);
        $header = str_getcsv($lines[0], ';');

        if (
            count(self::EXPECTED_HEADERS) !== count($header) ||
            array_diff(self::EXPECTED_HEADERS, $header) !== array_diff($header, self::EXPECTED_HEADERS)
        ) {
            $this->logger->err("Invalid header of the csv file: " . json_encode($header, JSON_THROW_ON_ERROR));
            throw new ValidationException("Header column is not as expected");
        }

        return $handler->handle($request);
    }
}
