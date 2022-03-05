<?php

declare(strict_types=1);

namespace Seller\Handler;

use DateTime;
use Dujche\MezzioHelperLib\Entity\EntityInterface;
use Dujche\MezzioHelperLib\Handler\PostHandler;
use Seller\Entity\SellerEntity;

class PostSellerHandler extends PostHandler
{
    protected function getEntityToSave(array $post): EntityInterface
    {
        $sellerEntity = new SellerEntity();
        $sellerEntity->setId($post['id']);
        $sellerEntity->setFirstName($post['firstName']);
        $sellerEntity->setLastName($post['lastName']);
        $sellerEntity->setCountry($post['country']);
        $sellerEntity->setDateJoined(new DateTime($post['dateJoined']));

        return $sellerEntity;
    }
}
