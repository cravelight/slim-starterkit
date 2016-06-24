<?php

namespace Cravelight\PhpUnit;


class Database_TestCase extends Enhanced_TestCase
{

    public static function setUpBeforeClass()
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

        $capsule = new \Illuminate\Database\Capsule\Manager;
        $capsule->addConnection($connection);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }


    public function assertIsStringOfLength($value, int $expectedLength)
    {
        $this->assertNotNull($value);
        $this->assertTrue(is_string($value));
        $this->assertEquals($expectedLength, strlen($value));
    }


}
