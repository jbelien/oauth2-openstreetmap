<?php

namespace JBelien\OAuth2\Client\Test\Provider;

use JBelien\OAuth2\Client\Provider\OpenStreetMapResourceOwner;
use Mockery as m;
use PHPUnit\Framework\TestCase;

class OpenStreeMapResourceOwnerTest extends TestCase
{
    public function testUrlIsNullWithoutDomainOrNickname(): void
    {
        $user = new OpenStreetMapResourceOwner();

        $url = $user->getUrl();

        $this->assertNull($url);
    }

    public function testUrlIsDomainWithoutNickname(): void
    {
        $domain = uniqid();
        $user = new OpenStreetMapResourceOwner();
        $user->setDomain($domain);

        $url = $user->getUrl();

        $this->assertEquals($domain, $url);
    }

    public function testUrlIsNicknameWithoutDomain(): void
    {
        $nickname = uniqid();
        $user = new OpenStreetMapResourceOwner(['login' => $nickname]);

        $url = $user->getUrl();

        $this->assertEquals($nickname, $url);
    }
}
