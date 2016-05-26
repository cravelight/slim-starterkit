<?php

namespace App\Actions\Api;


use Slim\Container;
use Monolog\Logger;


class ApiActionBase
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