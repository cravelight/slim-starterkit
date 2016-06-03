<?php

namespace App\RouteHandlers\Web;


use Slim\Container;
use Psr\Log\LoggerInterface as Logger;
use Slim\Views\Twig;


class WebHandlerBase
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