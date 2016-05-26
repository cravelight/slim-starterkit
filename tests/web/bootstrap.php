<?php

// -----------------------------------------------------------------------------
// Standardize the environment
// -----------------------------------------------------------------------------
define('SITE_ROOT', dirname(dirname(dirname(__FILE__))) . '/');
define('WEB_TEST_ROOT', SITE_ROOT . 'tests/web/');
date_default_timezone_set('UTC');
require SITE_ROOT . 'vendor/autoload.php';


// Hack some environment settings for now
define('API_TEST_SECRET', 'supersecret');
define('API_TEST_URL', 'http://slimkit.dev/api/v1');


// Include our support classes since we're not autoloading them
require_once WEB_TEST_ROOT.'ApiClient.php';

