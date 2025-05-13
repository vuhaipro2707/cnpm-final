<?php

    class Customer {
        private $db;

        public function __construct() {
            $this->db = new Database();
        }

        public function getCustomerById($customerId) {
            $this->db->query("SELECT * FROM customer WHERE customerId = :customerId");
            $this->db->bind(':customerId', $customerId);
            return $this->db->single();
        }



        public function getCustomerIdByUserName($username) {
            $this->db->query("SELECT customerId FROM customer WHERE username = :username");
            $this->db->bind(':username', $username);
            return $this->db->single()['customerId'];
        }

        public function setPoints($customerId, $points) {
            $this->db->query("UPDATE customer SET points = :points WHERE customerId = :customerId");
            $this->db->bind(':points', $points);
            $this->db->bind(':customerId', $customerId);
            $this->db->execute();
        }

        public function getCustomerByOrderId($orderId) {
            $this->db->query("
                SELECT c.* 
                FROM customer c
                INNER JOIN `order` o ON c.customerId = o.customerId
                WHERE o.orderId = :orderId
            ");
            $this->db->bind(':orderId', $orderId);
            return $this->db->single();
        }

    }
?>