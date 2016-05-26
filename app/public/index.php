<?php

// -----------------------------------------------------------------------------
// Standardize the environment
// -----------------------------------------------------------------------------
define('SITE_ROOT', dirname(dirname(dirname(__FILE__))) . '/');
define('APP_ROOT', SITE_ROOT . 'app/');
define('WEB_ROOT', APP_ROOT . 'public/');
date_default_timezone_set('UTC');
require SITE_ROOT . 'vendor/autoload.php';

// Tracy Debugger Docs ( https://tracy.nette.org/en/ )
Tracy\Debugger::enable();
// Example: Tracy\Debugger::barDump($_ENV, '$_ENV');


// -----------------------------------------------------------------------------
// Load the settings
// -----------------------------------------------------------------------------

// Slim Framework settings
$launch_settings['settings'] = [
    // Set this to true only if you need route params in APPLICATION level middleware
    'determineRouteBeforeAppMiddleware' => false,
];

$launch_settings['environment'] = [
    'debug_mode' => isset($_ENV['APPLICATION_DEBUG_MODE']) ?  $_ENV['APPLICATION_DEBUG_MODE'] : false,
];

$launch_settings['logger'] = [
    'name' => 'applog',
    'level' => isset($_ENV['APPLICATION_LOG_LEVEL']) ?  $_ENV['APPLICATION_LOG_LEVEL'] : 400,
    'path' => SITE_ROOT . 'logs/' . date('Y-m-d') . '.log',
];

$launch_settings['view'] = [
    'template_path' => APP_ROOT . 'views',
    'twig' => [
        'cache' => SITE_ROOT . 'cache/views',
        'debug' => $launch_settings['environment']['debug_mode'],
        'auto_reload' => true,
    ],
];


// -----------------------------------------------------------------------------
// Bootstrap the app
// -----------------------------------------------------------------------------

//session_start();

$app = new \Slim\App(['settings' => $launch_settings]); // Instantiate the app

require APP_ROOT . 'container.php';     // Set up dependencies
require APP_ROOT . 'middleware.php';    // Register middleware
require APP_ROOT . 'routes.php';        // Register routes


// Run!
$app->run();


