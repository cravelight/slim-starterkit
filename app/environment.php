<?php

// -----------------------------------------------------------------------------
// Describe required environment variables
// -----------------------------------------------------------------------------
// One at a time:     $dotenv->required('FOO');
// Or multiple:       $dotenv->required(['FOO', 'BAR', 'BAZ']);
// Not empty:         $dotenv->required('FOO')->notEmpty();
// Integer:           $dotenv->required('FOO')->isInteger();
// Allowed values:    $dotenv->required('FOO')->allowedValues(['This', 'That']);

$dotenv->required('APPLICATION_DEBUG_IS_ENABLED')->allowedValues([true, false]);
$dotenv->required('APPLICATION_LOG_LEVEL')->allowedValues(['DEBUG', 'INFO', 'NOTICE', 'WARNING', 'ERROR', 'CRITICAL', 'ALERT', 'EMERGENCY']);


//todo: support sql server
$dotenv->required('DB_ADAPTER')->notEmpty()->allowedValues(['sqlite', 'mysql']); // , 'sqlsrv']);
switch (getenv('DB_ADAPTER')) {
    case 'sqlite':
        $dotenv->required('DB_NAME')->notEmpty();
        break;

    case 'mysql':
        $dotenv->required('DB_HOST')->notEmpty();
        $dotenv->required('DB_NAME')->notEmpty();
        $dotenv->required('DB_USER')->notEmpty();
        $dotenv->required('DB_PASS')->notEmpty();
        $dotenv->required('DB_PORT')->notEmpty()->isInteger();
        $dotenv->required('DB_CHARSET')->notEmpty();
        $dotenv->required('DB_COLLATION')->notEmpty();
        break;
}




