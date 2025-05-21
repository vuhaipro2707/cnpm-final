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

        public function getMostPopularItem($number) {
            $this->db->query("SELECT i.*, oii_summary.itemId, oii_summary.total_quantity
                            FROM (
                                SELECT oii.itemId, SUM(oii.quantity) AS total_quantity
                                FROM orderincludeitem oii
                                JOIN `order` o ON oii.orderId = o.orderId
                                WHERE o.status IN ('paid', 'success')
                                GROUP BY oii.itemId
                                ORDER BY total_quantity DESC
                                LIMIT :number
                            ) oii_summary
                            JOIN item i ON oii_summary.itemId = i.itemId;");
            $this->db->bind(':number', $number);
            return $this->db->resultSet();
        }
    }
?>