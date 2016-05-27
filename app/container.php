<?php

$container = $app->getContainer();


// -----------------------------------------------------------------------------
// View Engine [Twig](http://twig.sensiolabs.org/)
// -----------------------------------------------------------------------------
$container['view'] = function ($container) {
    $settings = $container->get('settings');
    $view = new \Slim\Views\Twig($settings['view']['template_path'], $settings['view']['twig']);

    // Add extensions
    $view->addExtension(new Slim\Views\TwigExtension($container->get('router'), $container->get('request')->getUri()));

    // Note that {{ dump(your_var_here) }} only works with this extension loaded
    if ($settings['environment']['debug_mode']) {
        $view->addExtension(new Twig_Extension_Debug());
    }

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

    return $view;
};


// -----------------------------------------------------------------------------
// Consumers of this expect a PSR-3 LoggerInterface. Psr\Log\LoggerInterface
//      https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md
// -----------------------------------------------------------------------------
$container['logger'] = function ($container) {
    $settings = $container->get('settings');

    // Monolog https://github.com/Seldaek/monolog
    $logger = new \Monolog\Logger($settings['logger']['name']);

    // Add one or more processors
    if ($settings['environment']['debug_mode']) {
        $logger->pushProcessor(new \Monolog\Processor\IntrospectionProcessor()); // Adds the line/file/class/method from which the log call originated.
    } else {
        $logger->pushProcessor(new \Monolog\Processor\UidProcessor()); // Adds a unique identifier to a log record.
        $logger->pushProcessor(new \Monolog\Processor\WebProcessor()); // Adds the current request URI, request method and client IP to a log record.
    }

    // Add one or more push handlers
    $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['logger']['path'], $settings['logger']['level']));

    return $logger;
};

