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
// Slim Framework Settings
// -----------------------------------------------------------------------------

//The protocol version used by the Response object. (Default: '1.1')
//$launch_settings['httpVersion'] = '1.1';

// Size of each chunk read from the Response body when sending to the browser. (Default: 4096)
//$launch_settings['responseChunkSize'] = 4096;

// If false, then no output buffering is enabled. If 'append' or 'prepend', then any echo or print statements are
// captured and are either appended or prepended to the Response returned from the route callable. (Default: 'append')
//$launch_settings['outputBuffering'] = 'append';

// When true, the route is calculated before any middleware is executed. This means that you can inspect route
// parameters in middleware if you need to.  (Default: false)
//$launch_settings['determineRouteBeforeAppMiddleware'] = false;

// When true, additional information about exceptions are displayed by the default error handler. (Default: false)
$launch_settings['displayErrorDetails'] = true;

// When true, Slim will add a Content-Length header to the response. If you are using a runtime analytics tool, such
// as New Relic, then this should be disabled. (Default: true)
//$launch_settings['addContentLengthHeader'] = true;

// Filename for caching the FastRoute routes. Must be set to to a valid filename within a writeable directory.
// If the file does not exist, then it is created with the correct cache information on first run. Set to false
// to disable the FastRoute cache system. (Default: false)
//$launch_settings['routerCacheFile'] = false;


// -----------------------------------------------------------------------------
// Custom Settings Arrays
// -----------------------------------------------------------------------------

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


