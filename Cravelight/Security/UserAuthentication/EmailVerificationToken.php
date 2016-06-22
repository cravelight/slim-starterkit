<?php

namespace Cravelight\Security\UserAuthentication;


class EmailVerificationToken
{

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    /**
     * @var string The address being verified
     */
    public $email;

    /**
     * @var string The verification token
     */
    public $token;

    /**
     * @var \DateTime When the token expires
     */
    public $expiresAt;

    /**
     * @var \DateTime When the token was created
     */
    public $createdAt;

}