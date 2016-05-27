<?php

namespace App\RouteHandlers\Api;


use Slim\Container;
use Psr\Log\LoggerInterface as Logger;



class ApiHandlerBase
{
    /**
     * @var Container
     */
    protected $container;


    /**
     * @var Logger
     */
    protected $logger;



    //Constructor
    public function __construct(Container $c)
    {
        $this->container = $c;
        $this->logger = $this->container->get('logger');
    }

}