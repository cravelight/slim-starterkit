<?php

namespace App\RouteHandlers\Web;

use Slim\Http\Request;
use Slim\Http\Response;


class HomeAction extends WebHandlerBase
{
    public function __invoke(Request $request, Response $response, $args)
    {
//        $this->logger->addInfo("an info was here");
//        $this->logger->addError('an error was here');
//        \Tracy\Debugger::barDump($this, 'container');

        $currentUser = null;

        return $this->view->render($response, 'home.twig', ['currentUser' => $currentUser]);
    }
}