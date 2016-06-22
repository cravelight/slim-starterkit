<?php

namespace Cravelight\Security\UserAuthentication;

use Illuminate\Database\Capsule\Manager as Capsule;


class EmailVerificationTokenRepository implements IEmailVerificationTokenRepository
{
    /**
     * @var Capsule
     */
    private $db;

    public function __construct(Capsule $db)
    {
        $this->db = $db;
    }

    public function store(EmailVerificationToken $emailVerificationToken) : EmailVerificationToken
    {
        // TODO: Implement store() method.
    }

    public function fetch(string $email, string $token) : EmailVerificationToken
    {
        // TODO: Implement fetch() method.
    }
}
