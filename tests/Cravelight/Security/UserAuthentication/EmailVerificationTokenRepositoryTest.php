<?php

use Cravelight\PhpUnit\Database_TestCase;
use Cravelight\Security\UserAuthentication\EmailVerificationToken;
use Cravelight\Security\UserAuthentication\EmailVerificationTokenRepository;


class EmailVerificationTokenRepositoryTest extends Database_TestCase
{
    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }


    public function testStore()
    {
        // Arrange|Given
        $email = 'test_' . uniqid() . '@example.com';
        $expected = new EmailVerificationToken($email);
        $expected->token = bin2hex(random_bytes(20)); //bin2hex converts to ascii, doubling string length
        $expected->expiresAt = new DateTime();
        $expected->createdAt = null;
        $repo = new EmailVerificationTokenRepository();

        // Act|When
        $actual = $repo->store($expected);

        // Assert|Then
        $this->assertInstanceOf('Cravelight\Security\UserAuthentication\EmailVerificationToken', $actual);
        $this->assertEquals($expected->email, $actual->email);
        $this->assertEquals($expected->token, $actual->token);
        $this->assertEquals($expected->expiresAt->getTimestamp(), $actual->expiresAt->getTimestamp());
        $this->assertNotNull($actual->createdAt);
    }


}
