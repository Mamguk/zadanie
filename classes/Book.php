<?php
require_once 'DB.php';
class Book {
    private $db;
    public function __construct($db) {
        $this->db = $db;
    }
    public function add($title, $author) {
        $stmt = $this->db->conn->prepare('INSERT INTO books (title, author) VALUES (?, ?)');
        $stmt->bind_param('ss', $title, $author);
        return $stmt->execute();
    }
    public function edit($id, $title, $author, $is_available) {
        $stmt = $this->db->conn->prepare("UPDATE books SET title = ?, author = ?, is_available = ? WHERE id = ?");
        $stmt->bind_param("ssii", $title, $author, $is_available, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}