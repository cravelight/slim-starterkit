<?php

namespace App\RouteHandlers\Api;


use Slim\Container;
use Psr\Log\LoggerInterface as Logger;
use Illuminate\Database\Capsule\Manager as Capsule;



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


    /**
     * @var Capsule
     */
    protected $db;



    /**
     * ApiHandlerBase constructor.
     * @param Container $c
     */
    public function __construct(Container $c)
    {
        $this->container = $c;
        $this->logger = $this->container->get('logger');
        $this->db = $this->container->get('db');
    }

}