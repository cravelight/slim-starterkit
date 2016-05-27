<?php

use Slim\Http\Request;
use Slim\Http\Response;


// Home page
$app->get('/', '\App\RouteHandlers\Web\HomeAction');



// User Account Management
$app->group('/account', function () {

    // Register
    $this->get('/register', '\App\RouteHandlers\Web\UserAccountController:getRegister');
    $this->post('/register', '\App\RouteHandlers\Web\UserAccountController:postRegister');

    // Login
    $this->get('/login', '\App\RouteHandlers\Web\UserAccountController:getLogin');
    $this->post('/login', '\App\RouteHandlers\Web\UserAccountController:postLogin');

    // Authenticated Routes


    // Ping

});





// API Version 1
$app->group('/api/v1/{apikey}', function () {

    // Ping
    $this->get('/ping', '\App\RouteHandlers\Api\PingAction');

})->add(function (Request $request, Response $response, callable $next) {

    // You'll find the route parameters in the third element of the 'routeInfo' attribute.
    // https://github.com/slimphp/Slim/issues/1505 (akrabat commented on Sep 22, 2015)
    //$routeParams = $request->getAttribute('routeInfo')[2];
    //$apikey = $routeParams['apikey'];

    // todo: use the api key to get the customer secret
    $hmacHelper = new Cravelight\Slim\Hmac\HmacHelper('supersecret');

    if (!$hmacHelper->messageAuthenticates($request)) {
        // todo: log messages that fail authentication
        return $response->withJson((object)["message" => "Say goodnight, Gracie."], 401);
    }

    $response = $next($request, $response);

    return $response;
});
