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

        public function getRandomItem($number) {
            $this->db->query("SELECT * FROM item ORDER BY RAND() LIMIT :number");
            $this->db->bind(':number', $number, PDO::PARAM_INT);
            return $this->db->resultSet();
        }


        public function getItemById($itemId) {
            $this->db->query("SELECT * FROM item WHERE itemId = :itemId");
            $this->db->bind(':itemId', $itemId);
            return $this->db->single();
        }
    }
?>