<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

/**
 * Class JsonEndpoint
 */
class JsonEndpoint {

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var \Slim\Container
     */
    protected $container;

    /**
     * @var
     */
    protected $mysql;

    /**
     * @param Request $request
     * @param Response $response
     * @param \Slim\Container $container
     */
    public function __construct(Request $request, Response $response, \Slim\Container $container) {
        $this->request = $request;
        $this->response = $response;
        $this->container = $container;
    }

    /**
     * @param $args
     * @return mixed
     */
    public function get($args) {
        $this->_404();
    }

    /**
     * @param $args
     * @return mixed
     */
    public function post($args) {
        $this->_404();
    }

    /**
     * @param $args
     * @return mixed
     */
    public function put($args) {
        $this->_404();
    }

    /**
     * @param $args
     * @return mixed
     */
    public function delete($args) {
        $this->_404();
    }

    /**
     * @param array $data
     * @param int $code
     * @return Response
     */
    protected function _respond($data, $code) {
        return $this->response->withJson($data, $code);
    }

    /**
     * @throws \Slim\Exception\NotFoundException
     */
    protected function _404() {
        throw new \Slim\Exception\NotFoundException($this->request, $this->response);
    }
}