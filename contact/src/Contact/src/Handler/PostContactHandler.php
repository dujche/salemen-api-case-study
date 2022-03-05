<?php

declare(strict_types=1);

namespace Contact\Handler;

use Contact\Entity\ContactEntity;
use DateTime;
use Dujche\MezzioHelperLib\Entity\EntityInterface;
use Dujche\MezzioHelperLib\Handler\PostHandler;

class PostContactHandler extends PostHandler
{
    protected function getEntityToSave(array $post): EntityInterface
    {
        $contactEntity = new ContactEntity();
        $contactEntity->setUuid($post['uuid']);
        $contactEntity->setSellerId((int) $post['sellerId']);
        $contactEntity->setFullName($post['fullName']);
        $contactEntity->setRegion($post['region']);
        $contactEntity->setContactType($post['contactType']);
        $contactEntity->setContactDate(new DateTime($post['contactDate']));
        $contactEntity->setContactProductTypeOffered($post['contactProductTypeOffered']);
        $contactEntity->setContactProductTypeOfferedId((int) $post['contactProductTypeOfferedId']);

        return $contactEntity;
    }
}
