<?php

use Cravelight\Security\UserAuthentication\EmailAccessCredentials;
use Cravelight\Security\UserAuthentication\VerificationToken;
use Cravelight\Security\UserAuthentication\UserAuthenticationService;
use Cravelight\PhpUnit\Enhanced_TestCase;
use \Mockery as m;


class UserAuthenticationServiceTest extends Enhanced_TestCase
{
    protected function setUp()
    {
    }

    protected function tearDown()
    {
        m::close(); // http://docs.mockery.io/en/latest/reference/phpunit_integration.html
    }


    public function testCreateUserAuthenticationService()
    {
        // Arrange|Given
        $emailAccessCredentialRepository = m::mock('\Cravelight\Security\UserAuthentication\IEmailAccessCredentialRepository');
        $verificationTokenRepository = m::mock('\Cravelight\Security\UserAuthentication\IVerificationTokenRepository');

        // Act|When
        $userAuthenticationService = new UserAuthenticationService($emailAccessCredentialRepository, $verificationTokenRepository);

        // Assert|Then
        $this->assertInstanceOf('Cravelight\Security\UserAuthentication\UserAuthenticationService', $userAuthenticationService);

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
            ->with(m::type('\Cravelight\Security\UserAuthentication\EmailAccessCredentials'))
            ->andReturnUsing(function($accessCredentials) {
                $now = new DateTime();
                $accessCredentials->createdOn = $now;
                $accessCredentials->updatedOn = $now;
                return $accessCredentials;
            });

        $verificationTokenRepository = m::mock('\Cravelight\Security\UserAuthentication\IVerificationTokenRepository');
        $userAuthenticationService = new UserAuthenticationService($emailAccessCredentialRepository, $verificationTokenRepository);
        $email = 'person@address.com';


        // Act|When
        $accessCreds = $userAuthenticationService->registerEmailAddress($email);


        // Assert|Then
        $this->assertEquals($email, $accessCreds->email);
        $this->assertNull($accessCreds->passwordHash);
        $this->assertNull($accessCreds->verifiedOn);
        $this->assertNotNull($accessCreds->createdOn);
        $this->assertEquals($accessCreds->createdOn->getTimestamp(), time(), '', 5);
        $this->assertNotNull($accessCreds->updatedOn);
        $this->assertEquals($accessCreds->updatedOn->getTimestamp(), time(), '', 5);
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
        $verificationTokenRepository = m::mock('\Cravelight\Security\UserAuthentication\IVerificationTokenRepository');
        $verificationTokenRepository->shouldReceive('store')
            ->once()
            ->with(m::type('\Cravelight\Security\UserAuthentication\VerificationToken'))
            ->andReturnUsing(function($verificationToken) {
                $now = new DateTime();
                $verificationToken->createdOn = $now;
                $verificationToken->expiresOn = new DateTime('@' . ($now->getTimestamp() + (60*60*24)));
                return $verificationToken;
            });
        $userAuthenticationService = new UserAuthenticationService($emailAccessCredentialRepository, $verificationTokenRepository);
        $email = 'person@address.com';

        // Act|When
        $token = $userAuthenticationService->stageVerification($email);

        // Assert|Then
        $this->assertEquals($email, $token->targetId);
        $this->assertIsStringOfLength($token->token, 40);
        $this->assertNotNull($token->createdOn);
        $this->assertInstanceOf('\DateTime', $token->createdOn);
        $this->assertEquals($token->createdOn->getTimestamp(), time(), '', 5);
        $this->assertNotNull($token->expiresOn);
        $this->assertInstanceOf('\DateTime', $token->expiresOn);
        $this->assertEquals(time() + (60 * 60 * 24), $token->expiresOn->getTimestamp(), 'Token expiresOn has wrong timestamp.', 5);
    }



//    User clicks on link...is given the opportunity to set password
//    After setting password account becomes confirmed and user can log in
    public function testValidateTokenAndStorePassword()
    {
        // Arrange|Given
        $email = 'person@address.com';
        $passwordHash = password_hash('1supersecret!', PASSWORD_DEFAULT);

        $verificationTokenRepository = m::mock('\Cravelight\Security\UserAuthentication\IVerificationTokenRepository');
        $verificationTokenRepository->shouldReceive('fetch')
            ->once()
            ->andReturnUsing(function() {
                return new VerificationToken('person@address.com');
            });


        $emailAccessCredentialRepository = m::mock('\Cravelight\Security\UserAuthentication\IEmailAccessCredentialRepository');
        $mockedEmailAccessCredentials = new EmailAccessCredentials('person@address.com');
        $mockedEmailAccessCredentials->passwordHash = $passwordHash;
        $emailAccessCredentialRepository->shouldReceive('fetchForEmailAddress')
            ->andReturn($mockedEmailAccessCredentials);
        $emailAccessCredentialRepository->shouldReceive('store')
            ->once()
            ->andReturn($mockedEmailAccessCredentials);
        $userAuthenticationService = new UserAuthenticationService($emailAccessCredentialRepository, $verificationTokenRepository);

        // Act|When
        $emailAccessCredentials = $userAuthenticationService->verifyAddress($email, 'some token', $passwordHash);
        $userCanLogIn = $userAuthenticationService->credentialsAreValid($email, $passwordHash);

        // Assert|Then
        $this->assertEquals($email, $emailAccessCredentials->email);
        $this->assertEquals($passwordHash, $emailAccessCredentials->passwordHash);
        $this->assertEquals($emailAccessCredentials->verifiedOn->getTimestamp(), time(), '', 5);
        $this->assertTrue($userCanLogIn);
    }





}
