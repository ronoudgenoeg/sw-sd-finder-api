<?php

class Regions extends JsonEndpoint {

    /**
     * @param $args
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function get($args) {
        $data = $this->container->get('Mysql')->getRegions();
        return $this->_respond($data, 200);
    }
}