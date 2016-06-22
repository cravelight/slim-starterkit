<?php

namespace Cravelight\Security\UserAuthentication;

use Phinx\Migration\AbstractMigration;

class EmailAccessCredentialRepositoryMigration extends AbstractMigration
{

    public function change()
    {
        $this->table('email_access_credentials',
            array(
                'id' => false,
                'primary_key' => 'email'))
            ->addColumn('email', 'string', array('limit' => 255))
            ->addColumn('password_hash', 'string', array('limit' => 255, 'null' => true))
            ->addColumn('verified_at', 'datetime', array('null' => true))
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime')
            ->save();

    }
}
