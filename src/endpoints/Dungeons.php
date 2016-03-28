<?php
require_once('JsonEndpoint.php');

class Dungeons extends JsonEndpoint {

    /**
     * @param $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function get($args) {
        $data = $this->container->get('Mysql')->getDungeons();
        return $this->_respond($data, 200);
    }
}