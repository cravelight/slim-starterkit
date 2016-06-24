<?php

namespace Cravelight\Security\UserAuthentication;

use Phinx\Migration\AbstractMigration;

class EmailVerificationTokenRepositoryMigration extends AbstractMigration
{

    public function change()
    {
        $this->table('email_verification_tokens',
            array(
                'id' => false,
                'primary_key' => 'token'))
            ->addColumn('token', 'string', array('limit' => 40))
            ->addColumn('email', 'string', array('limit' => 255))
            ->addColumn('expires_at', 'datetime', array('null' => true))
            ->addColumn('created_at', 'datetime')
            ->save();

    }
}
