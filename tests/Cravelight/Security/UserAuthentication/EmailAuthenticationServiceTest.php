<?php

use Cravelight\Security\UserAuthentication\EmailAccessCredential;
use Cravelight\Security\UserAuthentication\EmailVerificationToken;
use Cravelight\Security\UserAuthentication\EmailAuthenticationService;
use Cravelight\PhpUnit\Enhanced_TestCase;
use \Mockery as m;


class EmailAuthenticationServiceTest extends Enhanced_TestCase
{
    protected function setUp()
    {
    }

    protected function tearDown()
    {
        m::close(); // http://docs.mockery.io/en/latest/reference/phpunit_integration.html
    }


    public function testCreateEmailAuthenticationService()
    {
        // Arrange|Given
        $emailAccessCredentialRepository = m::mock('\Cravelight\Security\UserAuthentication\IEmailAccessCredentialRepository');
        $emailVerificationTokenRepository = m::mock('\Cravelight\Security\UserAuthentication\IEmailVerificationTokenRepository');

        // Act|When
        $emailAuthenticationService = new EmailAuthenticationService($emailAccessCredentialRepository, $emailVerificationTokenRepository);

        // Assert|Then
        $this->assertInstanceOf('Cravelight\Security\UserAuthentication\EmailAuthenticationService', $emailAuthenticationService);

    }

//    User clicks register
//    User enters email address (required)
//    System creates unverified user account
    public function testRegisterEmailAddress()
    {
        // Arrange|Given
        $emailAccessCredentialRepository = m::mock('\Cravelight\Security\UserAuthentication\IEmailAccessCredentialRepository');
        $emailAccessCredentialRepository->shouldReceive('store')
            ->once()
            ->with(m::type('\Cravelight\Security\UserAuthentication\EmailAccessCredential'))
            ->andReturnUsing(function($accessCredential) {
                $now = new DateTime();
                $accessCredential->createdAt = $now;
                $accessCredential->updatedAt = $now;
                return $accessCredential;
            });

        $emailVerificationTokenRepository = m::mock('\Cravelight\Security\UserAuthentication\IEmailVerificationTokenRepository');
        $emailAuthenticationService = new EmailAuthenticationService($emailAccessCredentialRepository, $emailVerificationTokenRepository);
        $email = 'person@address.com';


        // Act|When
        $accessCreds = $emailAuthenticationService->registerEmailAddress($email);


        // Assert|Then
        $this->assertEquals($email, $accessCreds->email);
        $this->assertNull($accessCreds->passwordHash);
        $this->assertNull($accessCreds->verifiedAt);
        $this->assertNotNull($accessCreds->createdAt);
        $this->assertEquals($accessCreds->createdAt->getTimestamp(), time(), '', 5);
        $this->assertNotNull($accessCreds->updatedAt);
        $this->assertEquals($accessCreds->updatedAt->getTimestamp(), time(), '', 5);
    }


//    User clicks on link...is given the opportunity to set password
//    After setting password account becomes confirmed and user can log in
    // Create a time sensitive verification token
    // which will be used to make sure the user has access to
    // the email address they registered
    public function testStageEmailAddressVerification()
    {
        // Arrange|Given
        $emailAccessCredentialRepository = m::mock('\Cravelight\Security\UserAuthentication\IEmailAccessCredentialRepository');
        $emailVerificationTokenRepository = m::mock('\Cravelight\Security\UserAuthentication\IEmailVerificationTokenRepository');
        $emailVerificationTokenRepository->shouldReceive('store')
            ->once()
            ->with(m::type('\Cravelight\Security\UserAuthentication\EmailVerificationToken'))
            ->andReturnUsing(function($emailVerificationToken) {
                $now = new DateTime();
                $emailVerificationToken->createdAt = $now;
                $emailVerificationToken->expiresAt = new DateTime('@' . ($now->getTimestamp() + (60*60*24)));
                return $emailVerificationToken;
            });
        $emailAuthenticationService = new EmailAuthenticationService($emailAccessCredentialRepository, $emailVerificationTokenRepository);
        $email = 'person@address.com';

        // Act|When
        $token = $emailAuthenticationService->stageVerification($email);

        // Assert|Then
        $this->assertEquals($email, $token->email);
        $this->assertIsStringOfLength($token->token, 40);
        $this->assertNotNull($token->createdAt);
        $this->assertInstanceOf('\DateTime', $token->createdAt);
        $this->assertEquals($token->createdAt->getTimestamp(), time(), '', 5);
        $this->assertNotNull($token->expiresAt);
        $this->assertInstanceOf('\DateTime', $token->expiresAt);
        $this->assertEquals(time() + (60 * 60 * 24), $token->expiresAt->getTimestamp(), 'Token expiresAt has wrong timestamp.', 5);
    }



//    User clicks on link...is given the opportunity to set password
//    After setting password account becomes confirmed and user can log in
    public function testValidateTokenAndStorePassword()
    {
        // Arrange|Given
        $email = 'person@address.com';
        $passwordHash = password_hash('1supersecret!', PASSWORD_DEFAULT);

        $emailVerificationTokenRepository = m::mock('\Cravelight\Security\UserAuthentication\IEmailVerificationTokenRepository');
        $emailVerificationTokenRepository->shouldReceive('fetch')
            ->once()
            ->andReturnUsing(function() {
                return new EmailVerificationToken('person@address.com');
            });


        $emailAccessCredentialRepository = m::mock('\Cravelight\Security\UserAuthentication\IEmailAccessCredentialRepository');
        $mockedEmailAccessCredential = new EmailAccessCredential('person@address.com');
        $mockedEmailAccessCredential->passwordHash = $passwordHash;
        $emailAccessCredentialRepository->shouldReceive('fetchForEmailAddress')
            ->andReturn($mockedEmailAccessCredential);
        $emailAccessCredentialRepository->shouldReceive('store')
            ->once()
            ->andReturn($mockedEmailAccessCredential);
        $emailAuthenticationService = new EmailAuthenticationService($emailAccessCredentialRepository, $emailVerificationTokenRepository);

        // Act|When
        $emailAccessCredential = $emailAuthenticationService->verifyAddress($email, 'some token', $passwordHash);
        $userCanLogIn = $emailAuthenticationService->credentialsAreValid($email, $passwordHash);

        // Assert|Then
        $this->assertEquals($email, $emailAccessCredential->email);
        $this->assertEquals($passwordHash, $emailAccessCredential->passwordHash);
        $this->assertEquals($emailAccessCredential->verifiedAt->getTimestamp(), time(), '', 5);
        $this->assertTrue($userCanLogIn);
    }





}
