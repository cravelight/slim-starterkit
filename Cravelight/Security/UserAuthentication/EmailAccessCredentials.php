<?php

namespace Cravelight\Security\UserAuthentication;


class EmailAccessCredentials
{
    public function __construct($email)
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
    public $verifiedOn;

    /**
     * @var \DateTime
     */
    public $createdOn;

    /**
     * @var \DateTime
     */
    public $updatedOn;


    // Helpers...

}