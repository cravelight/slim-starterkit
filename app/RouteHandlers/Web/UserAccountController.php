<?php

namespace App\RouteHandlers\Web;

use Slim\Http\Request;
use Slim\Http\Response;


class UserAccountController extends WebHandlerBase
{

    public function getRegister(Request $request, Response $response, $args)
    {
        echo 'getRegister';
    }

    public function postRegister(Request $request, Response $response, $args)
    {

    }

    public function getLogin(Request $request, Response $response, $args)
    {
        return $this->view->render($response, 'user-account/login-form.twig');
    }

    public function postLogin(Request $request, Response $response, $args)
    {

    }

    public function getChangePassword(Request $request, Response $response, $args)
    {
        echo 'getChangePassword';
    }

    public function postChangePassword(Request $request, Response $response, $args)
    {

    }

    public function logout(Request $request, Response $response, $args)
    {

    }

}