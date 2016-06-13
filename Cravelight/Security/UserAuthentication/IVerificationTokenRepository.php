<?php

namespace Cravelight\Security\UserAuthentication;


interface IVerificationTokenRepository
{
    public function store(VerificationToken $verificationToken) : VerificationToken;

    public function fetch($targetId, string $token) : VerificationToken;

}