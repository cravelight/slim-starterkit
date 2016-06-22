<?php

namespace App\RouteHandlers\Api;

use Slim\Http\Request;
use Slim\Http\Response;


class PingAction extends ApiHandlerBase
{
    public function __invoke(Request $request, Response $response, $args)
    {
        //todo: add more helpful info related to the user's account
        $data = array("message" => "pong");
        return $response->withJson((object)$data);
    }
}