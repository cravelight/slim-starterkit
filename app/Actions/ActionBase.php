<?php

namespace App\Actions;


use Slim\Container;
use Monolog\Logger;
use Slim\Views\Twig;


class ActionBase
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