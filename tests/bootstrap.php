<?php

// -----------------------------------------------------------------------------
// Standardize the environment
// -----------------------------------------------------------------------------

$siteRoot = dirname(dirname(__FILE__)) . '/';
date_default_timezone_set('UTC');
require_once $siteRoot . 'vendor/autoload.php';


// Load environment variables (https://github.com/vlucas/phpdotenv)
$dotenv = new Dotenv\Dotenv($siteRoot);
$dotenv->load();



