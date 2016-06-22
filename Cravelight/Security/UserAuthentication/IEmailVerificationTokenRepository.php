<?php

namespace Cravelight\Security\UserAuthentication;


interface IEmailVerificationTokenRepository
{
    public function store(EmailVerificationToken $emailVerificationToken) : EmailVerificationToken;

    public function fetch(string $email, string $token) : EmailVerificationToken;

}