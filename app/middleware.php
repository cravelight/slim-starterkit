<?php
// Application middleware
// http://www.slimframework.com/docs/concepts/middleware.html
// e.g: $app->add(new \Slim\Csrf\Guard);


use Slim\Http\Request;
use Slim\Http\Response;



// -----------------------------------------------------------------------------
// Redirect paths with a trailing slash to their non-trailing counterpart (from the SlimPHP documentation)
// -----------------------------------------------------------------------------
$app->add(function (Request $request, Response $response, callable $next) {
    $uri = $request->getUri();
    $path = $uri->getPath();
    if ($path != '/' && substr($path, -1) == '/') {
        $uri = $uri->withPath(substr($path, 0, -1));
        return $response->withRedirect((string)$uri, 301);
    }

    return $next($request, $response);
});


