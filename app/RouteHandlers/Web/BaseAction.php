<?php

namespace App\RouteHandlers\Web;


use Slim\Container;
use Monolog\Logger;
use Slim\Views\Twig;


class BaseAction
{
    /**
     * @var Container
     */
    protected $container;


    /**
     * @var Logger
     */
    protected $logger;


    /**
     * @var Twig
     */
    protected $view;




    //Constructor
    public function __construct(Container $c)
    {
        $this->container = $c;
        $this->logger = $this->container->get('logger');
        $this->view = $this->container->get('view');
    }

}