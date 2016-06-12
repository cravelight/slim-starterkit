<?php

namespace Cravelight\Security\UserAuthentication;

use Carbon\Carbon;


class UserAuthenticationService
{
    /**
     * @var IEmailAccessCredentialRepository
     */
    private $emailAccessCredentialRepo;

    /**
     * @var IVerificationTokenRepository
     */
    private $verificationTokenRepo;


    public function __construct(
        IEmailAccessCredentialRepository $emailAccessCredentialRepository,
        IVerificationTokenRepository $verificationTokenRepository)
    {
        $this->emailAccessCredentialRepo = $emailAccessCredentialRepository;
        $this->verificationTokenRepo = $verificationTokenRepository;
    }


//    User clicks register
//    User enters email address (required)
//    System creates unverified user account
//    User clicks on link...is given the opportunity to set password
//    After setting password account becomes confirmed and user can log in

    public function registerEmailAddress(string $email) : EmailAccessCredentials
    {
        $emailAccessCredentials = new EmailAccessCredentials($email);
        $this->emailAccessCredentialRepo->store($emailAccessCredentials);
        return $emailAccessCredentials;
    }


    public function stageVerification(string $email, int $validHours = 24) : VerificationToken
    {
        $token = new VerificationToken($email);
        $token->expiresOn = Carbon::createFromTimestamp(time())->addHour($validHours);
        $token->token = random_bytes(40);
        return $this->verificationTokenRepo->store($token);
    }


    public function verifyAddress(string $email, string $verifyToken, string $passwordHash) : EmailAccessCredentials
    {

    }


    public function credentialsAreValid(string $email, string $passwordHash) : bool
    {

    }


}