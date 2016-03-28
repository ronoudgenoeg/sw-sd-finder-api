<?php

/**
 * Singleton Class Mysql
 */
class Mysql {

    /**
     * @var Mysql
     */
    protected static $instance;

    /**
     * @var PDO
     */
    protected $mysql;

    /**
     * @return Mysql
     */
    public static function getInstance() {
        if (is_null(static::$instance)) {
            static::$instance = new Mysql();
        }
        return static::$instance;
    }

    public function getDungeons() {
        $q = $this->mysql->query('SELECT * FROM dungeon');
        return $q->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOpenDungeons() {
        $q = $this->mysql->query('SELECT dungeon_id, 3600 - TIMESTAMPDIFF(SECOND, open_time, NOW()) as time_remaining, region_id FROM open_dungeon where ADDTIME(open_time, \'01:00:00\') > NOW()');
        return $q->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRegions() {
        $q = $this->mysql->query('SELECT * FROM region');
        return $q->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addOpenDungeon($openDungeon) {
        $openTime = null;
        if(isset($openDungeon->time_remaining)) {
            $q = $this->mysql->prepare('INSERT INTO open_dungeon (dungeon_id, open_time, region_id) VALUES (:dungeon_id, :open_time, :region_id)');
            $openTime = time() - 3600 + $openDungeon->time_remaining;
            return $q->execute([
                'dungeon_id' => $openDungeon->dungeon_id,
                'open_time' => date('Y-m-d H:i:s', $openTime),
                'region_id' => $openDungeon->region_id
            ]);
        } else {
            $q = $this->mysql->prepare('INSERT INTO open_dungeon (dungeon_id, region_id) VALUES (:dungeon_id, :region_id)');
            return $q->execute([
                'dungeon_id' => $openDungeon->dungeon_id,
                'region_id' => $openDungeon->region_id
            ]);
        }

    }

    /**
     *
     */
    protected function __construct() {
        $dsn = 'mysql:host=' . MYSQL_HOST . ';dbname=' . MYSQL_DB;
        $this->mysql = new PDO($dsn, MYSQL_USER, MYSQL_PASS);
    }

    /**
     *
     */
    protected function __clone() {
        return;
    }

    /**
     *
     */
    protected function __wakeup() {
        return;
    }
}