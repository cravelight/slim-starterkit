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
        // note that some table/column modifications require doctrine/dbal

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

/*
    Column Types

    // IDs
    $table->uuid('id'); // UUID equivalent for the database.
    $table->increments('id'); // Incrementing ID (primary key) using a "UNSIGNED INTEGER" equivalent.
    $table->bigIncrements('id'); // Incrementing ID (primary key) using a "UNSIGNED BIG INTEGER" equivalent.

    // Numeric
    $table->tinyInteger('numbers'); // TINYINT equivalent for the database.
    $table->smallInteger('votes'); // SMALLINT equivalent for the database.
    $table->integer('votes'); // INTEGER equivalent for the database.
    $table->mediumInteger('numbers'); // MEDIUMINT equivalent for the database.
    $table->bigInteger('votes'); // BIGINT equivalent for the database.
    $table->decimal('amount', 5, 2); // DECIMAL equivalent with a precision and scale.
    $table->double('column', 15, 8); // DOUBLE equivalent with precision, 15 digits in total and 8 after the decimal point.
    $table->float('amount'); // FLOAT equivalent for the database.

    // String
    $table->char('name', 4); // CHAR equivalent with a length.
    $table->string('email'); // VARCHAR equivalent column.
    $table->string('name', 100); // VARCHAR equivalent with a length.
    $table->text('description'); // TEXT equivalent for the database.
    $table->mediumText('description'); // MEDIUMTEXT equivalent for the database.
    $table->longText('description'); // LONGTEXT equivalent for the database.

    // Date Time
    $table->date('created_at'); // DATE equivalent for the database.
    $table->dateTime('created_at'); // DATETIME equivalent for the database.
    $table->dateTimeTz('created_at'); // DATETIME (with timezone) equivalent for the database.
    $table->time('sunrise'); // TIME equivalent for the database.
    $table->timeTz('sunrise'); // TIME (with timezone) equivalent for the database.
    $table->timestamp('added_on'); // TIMESTAMP equivalent for the database.
    $table->timestampTz('added_on'); // TIMESTAMP equivalent for the database.
    $table->timestamps(); // Adds created_at and updated_at columns.
    $table->nullableTimestamps(); // Same as timestamps(), except allows NULLs.

    // Other
    $table->enum('choices', ['foo', 'bar']); // ENUM equivalent for the database.
    $table->binary('data'); // BLOB equivalent for the database.
    $table->boolean('confirmed'); // BOOLEAN equivalent for the database.
    $table->softDeletes(); // Adds deleted_at column for soft deletes.
    $table->json('options'); // JSON equivalent for the database.
    $table->jsonb('options'); // JSONB equivalent for the database.
    $table->ipAddress('visitor'); // IP address equivalent for the database.
    $table->macAddress('device'); // MAC address equivalent for the database.
    $table->morphs('taggable'); // Adds INTEGER taggable_id and STRING taggable_type.
    $table->rememberToken(); // Adds remember_token as VARCHAR(100) NULL.

    Column Modifiers

    ->nullable()
    ->default($value)
    ->unsigned()

    Indexes

    $table->string('column')->unique();
    $table->primary('column');
    $table->primary(array('first', 'last'));
    $table->unique('column');
    $table->unique('column', 'key_name');
    $table->unique(array('first', 'last'));
    $table->unique(array('first', 'last'), 'key_name');
    $table->index('column');
    $table->index('column', 'key_name');
    $table->index(array('first', 'last'));
    $table->index(array('first', 'last'), 'key_name');
    $table->dropPrimary('table_column_primary');
    $table->dropUnique('table_column_unique');
    $table->dropIndex('table_column_index');

    Foreign Keys

    $table->foreign('user_id')->references('id')->on('users');
    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'|'restrict'|'set null'|'no action');
    $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade'|'restrict'|'set null'|'no action');
    $table->dropForeign('posts_user_id_foreign');

*/

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