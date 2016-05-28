<?php

namespace App\RouteHandlers\Web;

use Slim\Http\Request;
use Slim\Http\Response;


class UserAccountController extends WebHandlerBase
{

    public function getHome(Request $request, Response $response, $args)
    {
        $currentUser = ['Name' => 'Joe User'];
        return $this->view->render($response, 'user-account/home.twig', ['currentUser' => $currentUser]);
    }

}