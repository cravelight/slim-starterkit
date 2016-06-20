<?php

namespace Cravelight\Security\UserAuthentication;


interface IEmailAccessCredentialRepository
{
    public function store(EmailAccessCredential $emailAccessCredential) : EmailAccessCredential;

    public function fetchForEmailAddress(string $emailAddress) : EmailAccessCredential;

}