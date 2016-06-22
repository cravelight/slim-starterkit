<?php

use Cravelight\Security\UserAuthentication\EmailAccessCredential;
use Cravelight\Security\UserAuthentication\EmailAccessCredentialRepository;
use Cravelight\PhpUnit\Enhanced_TestCase;


class EmailAccessCredentialRepositoryTest extends Enhanced_TestCase
{
    protected function setUp()
    {
        $connection = [];
        switch (getenv('DB_ADAPTER')) {
            case 'sqlite':
                $connection['driver'] = 'sqlite';
                $connection['database'] = getenv('DB_NAME');
                break;

            case 'mysql':
                $connection['driver'] = 'mysql';
                $connection['host'] = getenv('DB_HOST');
                $connection['port'] = getenv('DB_PORT');
                $connection['database'] = getenv('DB_NAME');
                $connection['username'] = getenv('DB_USER');
                $connection['password'] = getenv('DB_PASS');
                $connection['charset'] = getenv('DB_CHARSET');
                $connection['collation'] = getenv('DB_COLLATION');
                break;
        }

        $capsule = new \Illuminate\Database\Capsule\Manager;
        $capsule->addConnection($connection);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

    }

    protected function tearDown()
    {
    }


    public function testRepository()
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
