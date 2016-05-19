<?php

namespace App\Actions;

use Slim\Http\Request;
use Slim\Http\Response;


class Home extends ActionBase
{
    public function __invoke(Request $request, Response $response, $args)
    {
//        $this->logger->addInfo("an info was here");
//        $this->logger->addError('an error was here');
//        \Tracy\Debugger::barDump($this, 'container');

        $fooBar = json_encode(['foo' => 'bar'], JSON_PRETTY_PRINT);

        return $this->view->render($response, 'Home.twig', ['fooBar' => $fooBar]);
    }
}