<?php

namespace Cravelight\Security\UserAuthentication;


class VerificationToken
{

    public function __construct($targetId)
    {
        $this->targetId = $targetId;
    }

    /**
     * @var mixed A reference to the thing we are verifying
     */
    public $targetId;

    /**
     * @var string The verification token
     */
    public $token;

    /**
     * @var \DateTime When the token expires
     */
    public $expiresOn;

    /**
     * @var \DateTime When the token was created
     */
    public $createdOn;

}