<?php

declare(strict_types=1);

namespace ContactTest\Handler;

use Contact\Entity\ContactEntity;
use Contact\Handler\GetContactHandler;
use Contact\Service\ContactService;
use DateTime;
use Laminas\Diactoros\Response\JsonResponse;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

class GetContactHandlerTest extends TestCase
{
    private ContactService $contactService;

    private GetContactHandler $handler;

    /**
     * @return ContactEntity
     */
    public function getMockContactEntity(): ContactEntity
    {
        $contactEntity = new ContactEntity();
        $contactEntity->setUuid('620b9dcb-d751-4d77-a7e4-d4de97bd9ef3');
        $contactEntity->setSellerId(10);
        $contactEntity->setFullName('John Doe');
        $contactEntity->setRegion('Alaska');
        $contactEntity->setContactType('Email');
        $contactEntity->setContactDate(new DateTime('2020-01-01'));
        $contactEntity->setContactProductTypeOfferedId(100);
        $contactEntity->setContactProductTypeOffered('bar');
        return $contactEntity;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->contactService = $this->createMock(ContactService::class);
        $this->handler = new GetContactHandler($this->contactService);
    }

    public function testHandleOnGetAllRoute(): void
    {
        $this->contactService->expects($this->once())->method('getAll')->willReturn([$this->getMockContactEntity()]);
        $result = $this->handler->handle($this->createMock(ServerRequestInterface::class));

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(
            '[{"uuid":"620b9dcb-d751-4d77-a7e4-d4de97bd9ef3","sellerId":10,"fullName":"John Doe","region":"Alaska","contactType":"Email","contactDate":"2020-01-01","contactProductTypeOfferedId":100,"contactProductTypeOffered":"bar"}]',
            $result->getBody()->getContents()
        );
    }

    public function testHandleOnGetAllRouteWithSellerId(): void
    {
        $this->contactService->expects($this->once())->method('getAllBySellerId')
            ->with(10)->willReturn([$this->getMockContactEntity()]);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())->method('getQueryParams')
            ->willReturn(['sellerId' => 10]);

        $result = $this->handler->handle($request);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(
            '[{"uuid":"620b9dcb-d751-4d77-a7e4-d4de97bd9ef3","sellerId":10,"fullName":"John Doe","region":"Alaska","contactType":"Email","contactDate":"2020-01-01","contactProductTypeOfferedId":100,"contactProductTypeOffered":"bar"}]',
            $result->getBody()->getContents()
        );
    }
}
