<?php

$container = $app->getContainer();


// -----------------------------------------------------------------------------
// view: Consumers of this expect Twig because that's what we use.
//      http://twig.sensiolabs.org/
// -----------------------------------------------------------------------------
$container['view'] = function ($container) {
    $settings = $container->get('settings');

    $path = $settings['environment']['path_app_root'] . 'views';

    $twigSettings = [ // http://twig.sensiolabs.org/doc/api.html#environment-options
        'cache' => $settings['environment']['path_site_root'] . 'cache/views',
        'debug' => (bool)getenv('APPLICATION_DEBUG_IS_ENABLED'),
        'auto_reload' => (bool)getenv('APPLICATION_DEBUG_IS_ENABLED'),
    ];

    $view = new \Slim\Views\Twig($path, $twigSettings);

    // Add extensions
    $view->addExtension(new Slim\Views\TwigExtension($container->get('router'), $container->get('request')->getUri()));

    // Note that {{ dump(your_var_here) }} only works with this extension loaded
    if (getenv('APPLICATION_DEBUG_IS_ENABLED')) {
        $view->addExtension(new Twig_Extension_Debug());
    }

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

    return $view;
};


// -----------------------------------------------------------------------------
// logger: Consumers of this expect a PSR-3 LoggerInterface. Psr\Log\LoggerInterface
//      https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md
// -----------------------------------------------------------------------------
$container['logger'] = function ($container) {
    $settings = $container->get('settings');

    // Monolog https://github.com/Seldaek/monolog
    $logger = new \Monolog\Logger('applog');

    // Add one or more processors
    if (getenv('APPLICATION_DEBUG_IS_ENABLED')) {
        $logger->pushProcessor(new \Monolog\Processor\IntrospectionProcessor()); // Adds the line/file/class/method from which the log call originated.
    } else {
        $logger->pushProcessor(new \Monolog\Processor\UidProcessor()); // Adds a unique identifier to a log record.
        $logger->pushProcessor(new \Monolog\Processor\WebProcessor()); // Adds the current request URI, request method and client IP to a log record.
    }

    // Add one or more push handlers
    $path = $settings['environment']['path_site_root'] . 'logs/' . date('Y-m-d') . '.log';
    $loglevel = $logger::toMonologLevel($_ENV['APPLICATION_LOG_LEVEL']);
    $logger->pushHandler(new \Monolog\Handler\StreamHandler($path, $loglevel));

    return $logger;
};


// -----------------------------------------------------------------------------
// emailer: Consumers of this expect a Cravelight\Notifications\Email\IEmailHelper Interface.
// -----------------------------------------------------------------------------
$container['emailer'] = function ($container) {

    if (empty(getenv('SENDGRID_API_KEY'))) {
        throw new \Exception("SendGrid API key is not set.");
    }
    $apikey = getenv('SENDGRID_API_KEY');

    if (empty(getenv('SENDGRID_FROM_ADDRESS'))) {
        throw new \Exception("A default SendGrid FROM address is not set.");
    }
    $defaultFromAddress = getenv('SENDGRID_FROM_ADDRESS');

    return new SendGridV2Helper($apikey, $defaultFromAddress);

};



