<?php
require_once('constants.php');
require_once('config.php');
require_once('src/Mysql.php');

$c = $app->getContainer();

//Catch all errors because we need to cast them to JSON
set_error_handler('error_handler');
register_shutdown_function('fatal_handler');
function error_handler($severity, $message, $filename, $line) {
    throw new ErrorException($message, 500, $severity, $filename, $line);
}
function fatal_handler() {
    $error = error_get_last();
    if ($error['type'] === E_ERROR) {
        header('Content-type', 'application-json', 500);
        $data = [
            'success' => false,
            'status' => 500,
            'message' => 'Something went wrong handling that request. Try again later or contact the developer'
        ];
        echo json_encode($data);
        die();
    }
}

//Cast all errors to json
$c['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
        if (ENVIRONMENT === ENV_DEV) {
            //show full stack trace and everything on development environment
            //Yes, we could/should create 2 different error handler objects for development and production..
            //..but it's a sunday morning and it currently doesn't add much value for the extra overhead
            $data = [
                'success' => false,
                'status' => $exception->getCode(),
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => explode("\n", $exception->getTraceAsString()),
            ];
        } else if ($exception->getCode() === 500 || $exception->getCode()) {
            //We didn't throw the exception ourselves, so throw internal server error
            $data = [
                'success' => false,
                'status' => $exception->getCode(),
                'message' => 'Something went wrong handling that request. Try again later or contact the developer'
            ];
        } else {
            //We threw the exception on purpose somewhere (like failed validation, or authentication)
            //Show the message
            $data = [
                'success' => false,
                'status' => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
        }
        $code = $exception->getCode();
        if ($code === 0) {
            $code = 500;
        }
        return $c->get('response')->withJson($data, $code);
    };
};

//Custom 404 with more useful error
$c['notFoundHandler'] = function ($c) {
    return function ($request) use ($c) {
        return $c['response']
            ->withJson([
                'success' => false,
                'status' => 404,
                'message' => 'There\'s nothing at [' . $request->getMethod() . '] ' . $request->getUri()->getPath()
            ], 404);
    };
};

$c['Mysql'] = Mysql::getInstance();

//Middleware to set everything to JSON
$app->add(function ($request, $response, $next) {
    $jsonResponse = $response->withHeader('Content-type', 'application/json');
    $jsonResponse = $next($request, $jsonResponse);
    return $jsonResponse;
});