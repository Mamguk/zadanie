<?php
class DB {
    private $host = 'localhost';
    private $db = 'library';
    private $user = 'root';
    private $pass = '';
    public $conn;
    public function __construct() {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->db);
        if ($this->conn->connect_error) {
            die('Ошибка подключения: ' . $this->conn->connect_error);
        }
    }
}