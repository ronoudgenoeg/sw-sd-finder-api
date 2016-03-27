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
        $q = $this->mysql->query('SELECT dungeon_id, TIMESTAMPDIFF(SECOND, open_time, NOW()) as time_remaining FROM open_dungeons where ADDTIME(open_time, \'01:00:00\') > NOW()');
        return $q->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addOpenDungeon($dungeonId, $timeRemaining = null) {
        $openTime = null;
        if(!is_null($timeRemaining)) {
            $q = $this->mysql->prepare('INSERT INTO open_dungeons (dungeon_id, open_time) VALUES (:dungeon_id, :open_time)');
            $openTime = time() - 3600 + $timeRemaining;
            return $q->execute(['dungeon_id' => $dungeonId, 'open_time' => date('Y-m-d H:i:s', $openTime)]);

        } else {
            $q = $this->mysql->prepare('INSERT INTO open_dungeons (dungeon_id) VALUES (:dungeon_id)');
            return $q->execute(['dungeon_id' => $dungeonId]);
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