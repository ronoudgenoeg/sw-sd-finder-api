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