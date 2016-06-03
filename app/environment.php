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





