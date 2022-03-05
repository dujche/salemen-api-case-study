<?php

declare(strict_types=1);

namespace Sale\Handler;

use DateTime;
use Dujche\MezzioHelperLib\Entity\EntityInterface;
use Dujche\MezzioHelperLib\Handler\PostHandler;
use Sale\Entity\SaleEntity;

class PostSaleHandler extends PostHandler
{
    protected function getEntityToSave(array $post): EntityInterface
    {
        $saleEntity = new SaleEntity();
        $saleEntity->setUuid($post['uuid']);
        $saleEntity->setSellerId((int) $post['sellerId']);
        $saleEntity->setSaleNetAmount($post['saleNetAmount']);
        $saleEntity->setSaleGrossAmount($post['saleGrossAmount']);
        $saleEntity->setSaleTaxRatePercentage($post['saleTaxRatePercentage']);
        $saleEntity->setSaleProductTotalCost($post['saleProductTotalCost']);

        $saleEntity->setSaleDate(new DateTime($post['saleDate']));


        return $saleEntity;
    }
}
