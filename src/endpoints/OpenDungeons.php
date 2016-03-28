<?php
require_once('JsonEndpoint.php');

/**
 * Class OpenDungeons
 */
class OpenDungeons extends JsonEndpoint {

    /**
     * @param $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function get($args) {
        $data = $this->container->get('Mysql')->getOpenDungeons();
        return $this->_respond([$data], 200);
    }

    /**
     * @param $args
     * @return \Psr\Http\Message\ResponseInterface
     * @throws Exception
     */
    public function post($args) {
        $object = json_decode($this->request->getBody());

        //todo: validate the object based on a map to the database instead of doing it manually
        if (is_null($object)) {
            throw new InvalidArgumentException('Invalid JSON supplied', 400);
        }

        if (!isset($object->dungeon_id) || !is_numeric($object->dungeon_id)) {
            throw new InvalidArgumentException('Required \'dungeon_id\' field is missing or not a number', 400);
        }

        if (!isset($object->region_id) || !is_numeric($object->region_id)) {
            throw new InvalidArgumentException('Required \'region_id\' field is missing or not a number', 400);
        }

        if (isset($object->time_remaining) && !is_numeric($object->time_remaining)) {
            throw new InvalidArgumentException('Optional \'time_remaining\' field is not a number', 400);
        }

        $result = $this->container->get('Mysql')->addOpenDungeon($object);
        if ($result) {
            return $this->_respond([
                'success' => true,
                'status' => 201
            ], 201);
        } else {
            throw new Exception();
        }
    }
}