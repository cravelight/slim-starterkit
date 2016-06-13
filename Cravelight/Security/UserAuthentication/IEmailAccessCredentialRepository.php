<?php

namespace Cravelight\Security\UserAuthentication;


interface IEmailAccessCredentialRepository
{
    public function store(EmailAccessCredentials $emailAccessCredentials) : EmailAccessCredentials;

    public function fetchForEmailAddress(string $emailAddress) : EmailAccessCredentials;

}