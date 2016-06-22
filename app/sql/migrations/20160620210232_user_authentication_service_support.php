<?php

use Phinx\Migration\AbstractMigration;

class UserAuthenticationServiceSupport extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $this->table('email_access_credentials',
            array(
                'id' => false,
                'primary_key' => 'email_address'))
            ->addColumn('email_address', 'string', array('limit' => 255))
            ->addColumn('password_hash', 'string', array('limit' => 255, 'null' => true))
            ->addColumn('verified_at', 'datetime', array('null' => true))
            ->addColumn('created_at', 'datetime', array('default' => 'CURRENT_TIMESTAMP'))
            ->addColumn('updated_at', 'datetime', array('update' => 'CURRENT_TIMESTAMP'))
            ->save();

        $this->table('email_verification_tokens',
            array(
                'id' => false,
                'primary_key' => 'token'))
            ->addColumn('token', 'string', array('limit' => 40))
            ->addColumn('email_address', 'string', array('limit' => 255))
            ->addColumn('expires_at', 'datetime', array('null' => true))
            ->addColumn('created_at', 'datetime', array('default' => 'CURRENT_TIMESTAMP'))
            ->addColumn('updated_at', 'datetime', array('update' => 'CURRENT_TIMESTAMP'))
            ->save();

    }
}
