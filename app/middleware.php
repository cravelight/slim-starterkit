<?php
/**
 * This file is for application wide middleware.
 * Application middleware is invoked for every incoming HTTP request.
 * http://www.slimframework.com/docs/concepts/middleware.html
 *
 * Note how middleware works...
 *
 *  function ($request, $response, $next) {
 *      // code here happens on the way in...
 *
 *      // this line triggers either the next middleware
 *      // or the route handler if there is no more middleware
 *      $response = $next($request, $response);
 *
 *      // code here happens on the way out... (after the route handler)
 *
 *      // finally we have to return the response
 *      return $response;
 *  };
 *
 */

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


