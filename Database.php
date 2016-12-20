<?php

/**
 * Created by PhpStorm.
 * User: Asus
 * Date: 20.12.2016
 * Time: 11:05
 */
class Database
{
    private $local_opt, $conn, $db_errors, $result_data;
    private $result_str;
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
        } catch (Exception $e) {
        }

        if (!$this->conn) die($this->errors = 'Ошибка соединения с MYSQL: ошибка № ' . $this->conn->connect_errno . " " . $this->conn->connect_errno);
    }

    //проверить день в бд
    final public function Add_day(string $tmp)
    {

        $this->Check_day($tmp);
        # узнаем, есть ли такой день в бд
        if (empty($this->result_data['name_day_of_week'])) {

            # подготовка запроса
            $stmt = $this->conn->prepare("INSERT INTO calendar(name_day_of_week,type_week) VALUES (?,?)");


            for ($i = 1; $i < 3; $i++) { # вторая стадия: привязка параметров

                $stmt->bind_param('si', $tmp, $i);
                # выполнение запроса
                if (!$stmt->execute()) {
                    $this->db_errors[] = "ошибка выполнения запроса" . $stmt->errno . " " . $stmt->error;
                } else {
                    $stmt->store_result();# сохраняем результаты
                }
            }
            $stmt->close();
        } else {
            echo "такой день уже добавлен =(";
        }

    }

    final private function Check_day(string $tmp)
    {
        # подготовка запроса
        # mysql> SELECT * FROM [table name] WHERE [field name] = "whatever"

        $stmt = $this->conn->prepare("SELECT name_day_of_week FROM calendar WHERE name_day_of_week=? ");

        # вторая стадия: привязка параметров
        $stmt->bind_param('s', $tmp);
        if (!$stmt->execute()) {
            $this->db_errors[] = "ошибка выполнения запроса" . $stmt->errno . " " . $stmt->error;
        } else {
            # выполняем запрос
            $stmt->execute();
            $stmt->store_result();# сохраняем результаты

            # Определить переменные для записи результата
            $stmt->bind_result($this->result_data['name_day_of_week']);
            # получить найденные значения
            $stmt->fetch();
            # закрываем запрос
            $stmt->close();
        }

    }


}