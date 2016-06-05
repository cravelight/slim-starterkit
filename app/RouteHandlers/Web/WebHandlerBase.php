<?php

namespace App\RouteHandlers\Web;


use Slim\Container;
use Psr\Log\LoggerInterface as Logger;
use Illuminate\Database\Capsule\Manager as Capsule;
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


    /**
     * @var Capsule
     */
    protected $db;



    /**
     * WebHandlerBase constructor.
     * @param Container $c
     */
    public function __construct(Container $c)
    {
        $this->container = $c;
        $this->logger = $this->container->get('logger');
        $this->db = $this->container->get('db');
        $this->view = $this->container->get('view');
    }

}