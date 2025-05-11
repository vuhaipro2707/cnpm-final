<?php
    class Item {
        private $db;

        public function __construct() {
            $this->db = new Database();
        }

        public function getAllItems() {
            $this->db->query("SELECT * FROM item");
            return $this->db->resultSet();
        }

        public function getItemById($itemId) {
            $this->db->query("SELECT * FROM item WHERE itemId = :itemId");
            $this->db->bind(':itemId', $itemId);
            return $this->db->single();
        }
    }
?>