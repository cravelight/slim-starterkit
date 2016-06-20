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

    public function registerEmailAddress(string $email) : EmailAccessCredential
    {
        $emailAccessCredential = new EmailAccessCredential($email);
        $this->emailAccessCredentialRepo->store($emailAccessCredential);
        return $emailAccessCredential;
    }


    public function stageVerification(string $email, int $validHours = 24) : VerificationToken
    {
        $token = new VerificationToken($email);
        $token->expiresAt = Carbon::createFromTimestamp(time())->addHour($validHours);
        $token->token = random_bytes(40);
        return $this->verificationTokenRepo->store($token);
    }


    // for now, we're forcing callers to store password hash when we verify token
    public function verifyAddress(string $email, string $verifyToken, string $passwordHash) : EmailAccessCredential
    {
        // attempt to load VerificationToken with matching email/token
        $token = $this->verificationTokenRepo->fetch($email, $verifyToken);
        if (is_null($token)) {
            return null;
        }

        // if successful, store the password hash and return the EmailAccessCredential
        $emailAccessCredential = $this->emailAccessCredentialRepo->fetchForEmailAddress($email);
        if (is_null($emailAccessCredential)) {
            throw new \Exception('Failed to load EmailAccessCredential for ' . $email);
        }
        $emailAccessCredential->passwordHash = $passwordHash;
        $emailAccessCredential->verifiedAt = new \DateTime();
        return $this->emailAccessCredentialRepo->store($emailAccessCredential);

    }


    public function credentialsAreValid(string $email, string $passwordHash) : bool
    {
        $emailAccessCredential = $this->emailAccessCredentialRepo->fetchForEmailAddress($email);
        if (is_null($emailAccessCredential)) {
            return false;
        }
        return $emailAccessCredential->passwordHash === $passwordHash;
    }


}