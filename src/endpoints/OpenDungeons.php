<?php
require_once('JsonEndpoint.php');

class OpenDungeons extends JsonEndpoint {

    /**
     * @param $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function get($args) {
        $data = $this->container->get('Mysql')->getOpenDungeons();
        return $this->_respond([$data], 200);
    }

    public function post($args) {
        $object = json_decode($this->request->getBody());
        if (is_null($object)) {
            throw new InvalidArgumentException('Invalid JSON supplied', 400);
        }

        if (!$object->dungeon_id || !is_numeric($object->dungeon_id)) {
            throw new InvalidArgumentException('Required \'dungeon_id\' field is missing or not a number', 400);
        }
        if (isset($object->time_remaining) && !is_numeric($object->time_remaining)) {
            throw new InvalidArgumentException('Optional \'time_remaining\' field is not a number', 400);
        }

        $result = $this->container->get('Mysql')->addOpenDungeon($object->dungeon_id, isset($object->time_remaining) ? $object->time_remaining : null);
        var_dump($result);
    }
}