<?php

use Cravelight\Security\UserAuthentication\EmailAccessCredential;
use Cravelight\Security\UserAuthentication\EmailAccessCredentialRepository;
use Cravelight\PhpUnit\Database_TestCase;


class EmailAccessCredentialRepositoryTest extends Database_TestCase
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
        $expected = new EmailAccessCredential($email);
        $expected->passwordHash = password_hash($email, PASSWORD_DEFAULT);
        $expected->verifiedAt = new DateTime();
        $expected->createdAt = null;
        $expected->updatedAt = null;
        $repo = new EmailAccessCredentialRepository();

        // Act|When
        $actual = $repo->store($expected);

        // Assert|Then
        $this->assertInstanceOf('Cravelight\Security\UserAuthentication\EmailAccessCredential', $actual);
        $this->assertEquals($expected->email, $actual->email);
        $this->assertEquals($expected->passwordHash, $actual->passwordHash);
        $this->assertEquals($expected->verifiedAt->getTimestamp(), $actual->verifiedAt->getTimestamp());
        $this->assertNotNull($actual->createdAt);
        $this->assertNotNull($actual->updatedAt);
    }




}
