<?php

namespace App\RouteHandlers\Web;

use Slim\Http\Request;
use Slim\Http\Response;


//    I think the process should include verification of the email address.
//
//    Sample workflow:
//    - User clicks register
//    - User enters email address (required) and First/Last name (optional)
//    - System creates unverified user account
//    - System sends user an email with a one-time / time sensitive link to activate the account
//    - User clicks on link...is given the opportunity to set password
//    - After setting password account becomes confirmed and user can log in


class UserAuthenticationController extends WebHandlerBase
{

    public function getRegister(Request $request, Response $response, $args)
    {
        return $this->view->render($response, 'user-authentication/register-form.twig');
    }

    public function postRegister(Request $request, Response $response, $args)
    {
        $baseUrl = $request->getUri()->getBaseUrl();
        return $response->withRedirect($baseUrl . '/account/confirm', 301);
    }



    public function getConfirm(Request $request, Response $response, $args)
    {
        return $this->view->render($response, 'user-authentication/register-confirm-form.twig');
    }

    public function postConfirm(Request $request, Response $response, $args)
    {
        $baseUrl = $request->getUri()->getBaseUrl();
        return $response->withRedirect($baseUrl . '/account/login', 301);

    }



    public function getLogin(Request $request, Response $response, $args)
    {
        return $this->view->render($response, 'user-authentication/login-form.twig');
    }

    public function postLogin(Request $request, Response $response, $args)
    {
        $baseUrl = $request->getUri()->getBaseUrl();
        return $response->withRedirect($baseUrl . '/account', 301);
    }



}