<?php

/**
 * Created by PhpStorm.
 * User: Asus
 * Date: 20.12.2016
 * Time: 11:05
 */
class Database
{
    private $local_opt, $conn, $db_errors, $result_data,$result_str;
    private $defaults = array(
        'host' => 'localhost',
        'user' => 'root',
        'pass' => 'root',
        'db' => 'schedule',
    );
    public function __construct($opt = array())
    {
        try {
            $this->local_opt = array_merge($this->defaults, $opt); # мержим два массива

            $this->db_errors = array();
            $this->result_data = array();
            $this->result_str = array();
            @$this->conn = new mysqli($this->local_opt['host'], $this->local_opt['user'], $this->local_opt['pass'], $this->local_opt['db']);
        }
        catch (Exception $e)
        {}

        if (!$this->conn) die($this->errors = 'Ошибка соединения с MYSQL: ошибка № ' . $this->conn->connect_errno . " " . $this->conn->connect_errno);
    }

}