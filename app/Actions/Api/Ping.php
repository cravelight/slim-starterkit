<?php

namespace App\Actions\Api;

use Slim\Http\Request;
use Slim\Http\Response;


class Ping extends ApiActionBase
{
    public function __invoke(Request $request, Response $response, $args)
    {
        //todo: add more helpful info related to the user's account
        $data = array("message" => "pong");
        return $response->withJson((object)$data);
    }
}