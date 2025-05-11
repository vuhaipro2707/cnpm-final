<?php
    class Account {
        private $db;

        public function __construct() {
            $this->db = new Database();
        }

        public function getAccountInfoByUsername($username) {
            $this->db->query("SELECT * FROM account WHERE username = :username");
            $this->db->bind(':username', $username);
            return $this->db->single();
        }

        public function createAccount($username, $password, $role, $name, $phone) {
            $this->db->query("INSERT INTO account (username, password, role) VALUES (:username, :password, :role)");
            $this->db->bind(':username', $username);
            $this->db->bind(':password', $password);
            $this->db->bind(':role', $role);
            $this->db->execute();

            if ($role === 'customer') {
                $this->db->query("INSERT INTO customer (name, phone, points, username) VALUES (:name, :phone, :points, :username)");
                $this->db->bind(':name', $name);
                $this->db->bind(':phone', $phone);
                $this->db->bind(':points', 0);
                $this->db->bind(':username', $username);
                return $this->db->execute();
            }

            // if ($role === 'staff') {

            // }

            return true;
        }

    }

?>