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
    }
?>