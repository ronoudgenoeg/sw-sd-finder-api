<?php
require_once('Endpoints/JsonEndpoint.php');

$app->any('/{resource}', function ($request, $response, $args) {
    $class = createClassFromResource($request->getAttribute('resource'), $request, $response, $this);
    /**
     * @var JsonEndpoint $class
     */
    $function = strtolower($request->getMethod());
    if (method_exists($class, $function)) {
        return $class->$function($args);
    }
    throw new \Slim\Exception\NotFoundException($request, $response);
});

function createClassFromResource($resource, $request, $response, $container) {
    $className = ucwords($resource);
    if (file_exists('src/Endpoints/' . $className . '.php')) {
        require_once('src/Endpoints/' . $className . '.php');
        $class = new $className($request, $response, $container);
        return $class;
    } else {
        throw new \Slim\Exception\NotFoundException($request, $response);
    }
}
