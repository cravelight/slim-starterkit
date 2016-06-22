<?php

namespace Cravelight\Security\UserAuthentication;


class EmailAccessCredential
{
    public function __construct(string $email)
    {
        $this->email = $email;
    }

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $passwordHash;

    /**
     * @var \DateTime
     */
    public $verifiedAt;

    /**
     * @var \DateTime
     */
    public $createdAt;

    /**
     * @var \DateTime
     */
    public $updatedAt;


    // Helpers...

}