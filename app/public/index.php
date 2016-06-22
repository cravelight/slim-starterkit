<?php

/**
 * The only reasons to touch this file are:
 * - Override the default value of a SlimFramework setting
 * - Create a custom settings array for settings that get used outside of the container
 * - session_start() before SlimFramework launches
 */


// -----------------------------------------------------------------------------
// Standardize the environment
// -----------------------------------------------------------------------------
$launch_settings['environment']['path_site_root'] = dirname(dirname(dirname(__FILE__))) . '/';
$launch_settings['environment']['path_app_root'] = $launch_settings['environment']['path_site_root'] . 'app/';
$launch_settings['environment']['path_web_root'] = $launch_settings['environment']['path_site_root'] . 'public/';
date_default_timezone_set('UTC');
require $launch_settings['environment']['path_site_root'] . 'vendor/autoload.php';


// Load environment variables (https://github.com/vlucas/phpdotenv)
$dotenv = new Dotenv\Dotenv($launch_settings['environment']['path_site_root']);
$dotenv->load();
require $launch_settings['environment']['path_app_root'] . 'environment.php';




// -----------------------------------------------------------------------------
// Debugger
// -----------------------------------------------------------------------------
// Tracy Debugger Docs ( https://tracy.nette.org/en/ )
// Example: Tracy\Debugger::barDump($_ENV, '$_ENV');
if (getenv('APPLICATION_DEBUG_IS_ENABLED')) {
    Tracy\Debugger::enable();
}



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
/*
    Set one up like this...

    $launch_settings['your_group_key'] = [
        'this_setting' => true,
        'that_setting' => 42,
        'the_other_setting' => [
            // http://twig.sensiolabs.org/doc/api.html#environment-options
            'foo' => $launch_settings['environment']['path_site_root'] . 'path/to/something',
            'bar' => time(),
        ],
    ];

    Later inside Slim you can use it like this...

    $foo_path = $app->getContainer()->get('settings')['your_group_key']['the_other_setting']['foo'];

*/


// -----------------------------------------------------------------------------
// Bootstrap the app
// -----------------------------------------------------------------------------

//session_start();

$app = new \Slim\App(['settings' => $launch_settings]); // Instantiate the app

require $launch_settings['environment']['path_app_root'] . 'container.php';     // Set up dependencies
require $launch_settings['environment']['path_app_root'] . 'middleware.php';    // Register middleware
require $launch_settings['environment']['path_app_root'] . 'routes.php';        // Register routes


// Run!
$app->run();
