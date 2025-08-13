<?php
require_once 'DB.php';
class Booking {
    private $db;
    public function __construct($db) {
        $this->db = $db;
    }
    public function reserve($user_id, $book_id) {
        $stmt = $this->db->conn->prepare('INSERT INTO booking (user_id, book_id) VALUES (?, ?)');
        $stmt->bind_param('ii', $user_id, $book_id);
        return $stmt->execute();
    }
    public function cancel($booking_id) {
        $stmt = $this->db->conn->prepare('UPDATE booking SET status="canceled" WHERE id=?');
        $stmt->bind_param('i', $booking_id);
        return $stmt->execute();
    }

}
