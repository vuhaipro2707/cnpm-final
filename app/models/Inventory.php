<?php

class Inventory {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllItems() {
        $this->db->query("SELECT * FROM inventory");
        return $this->db->resultSet();
    }

    public function getItemById($id) {
        $this->db->query("SELECT * FROM inventory WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
}
