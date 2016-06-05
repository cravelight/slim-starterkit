<?php

namespace Cravelight\Phinx;


class EloquentMigrationFactory implements \Phinx\Migration\CreationInterface
{

    /**
     * Get the migration template.
     *
     * This will be the content that Phinx will amend to generate the migration file.
     *
     * Phinx will replace the following variables in the returned content:
     *  $useClassName, $className, $version, $baseClassName
     * from:
     *  https://github.com/robmorgan/phinx/blob/8254407ab8b5bbc42905e57655d05c88e57d2a7c/src/Phinx/Console/Command/Create.php
     *
     * @return string The content of the template for Phinx to amend.
     */
    public function getMigrationTemplate()
    {
        return <<<'EOT'
<?php

use $useClassName;
use Illuminate\Database\Schema\Blueprint;

class $className extends $baseClassName
{
    public function up()
    {
        // see: https://laravel.com/docs/5.2/migrations#writing-migrations

        $this->schema->create('your_table_name', function(Blueprint $table){
            $table->increments('id'); // Auto-increment id
            $table->string('label');  // Some string column
            $table->timestamps();     // Required for Eloquent's created_at and updated_at columns
        });
    }

    public function down()
    {
        $this->schema->drop('your_table_name');
    }
}

EOT;

    }

    /**
     * Post Migration Creation.
     *
     * Once the migration file has been created, this method will be called, allowing any additional
     * processing, specific to the template to be performed.
     *
     * @param string $migrationFilename The name of the newly created migration.
     * @param string $className The class name.
     * @param string $baseClassName The name of the base class.
     * @return void
     */
    public function postMigrationCreation($migrationFilename, $className, $baseClassName)
    {
        // TODO: Implement postMigrationCreation() method.
    }
}