<?php

namespace Cravelight\Phinx;

use Illuminate\Database\Capsule\Manager as Capsule;
use Phinx\Migration\AbstractMigration;

/**
 * Adapter to enable the use of Eloquent migrations via the Phinx command line runner
 *
 * HT: https://siipo.la/blog/how-to-use-eloquent-orm-migrations-outside-laravel
 */
class EloquentMigrationAdapter extends AbstractMigration
{
    /** @var \Illuminate\Database\Capsule\Manager $capsule */
    public $capsule;

    /** @var \Illuminate\Database\Schema\Builder $capsule */
    public $schema;

    public function init()
    {
        $connection = [];
        switch (getenv('DB_ADAPTER')) {
            case 'sqlite':
                $connection['driver'] = 'sqlite';
                $connection['database'] = getenv('DB_NAME');
                break;

            case 'mysql':
                $connection['driver'] = 'mysql';
                $connection['host'] = getenv('DB_HOST');
                $connection['port'] = getenv('DB_PORT');
                $connection['database'] = getenv('DB_NAME');
                $connection['username'] = getenv('DB_USER');
                $connection['password'] = getenv('DB_PASS');
                $connection['charset'] = getenv('DB_CHARSET');
                $connection['collation'] = getenv('DB_COLLATION');
                break;
        }

        $this->capsule = new Capsule;
        $this->capsule->addConnection($connection);
        $this->capsule->setAsGlobal();
        $this->capsule->bootEloquent();
        $this->schema = $this->capsule->schema();
    }
}