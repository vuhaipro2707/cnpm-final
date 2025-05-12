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
            return $this->db->single();
        }
    }
?>