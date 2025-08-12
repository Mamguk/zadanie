<?php
require_once 'DB.php';
class User {
    private $db;
    public function __construct($db) {
        $this->db = $db;
    }
    public function add($name, $email) {
        $stmt = $this->db->conn->prepare('INSERT INTO users (name, email) VALUES (?, ?)');
        $stmt->bind_param('ss', $name, $email);
        return $stmt->execute();
    }
    public function edit($id, $name, $email) {
        $stmt = $this->db->conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $email, $id);
        return $stmt->execute();
    }
}