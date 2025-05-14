<?php
    class Staff {
        private $db;

        public function __construct() {
            $this->db = new Database();
        }

        public function getAllStaff() {
            $this->db->query("SELECT * FROM Staff");
            return $this->db->resultSet();
        }

        public function createStaff($name, $position, $username, $isManager = 0) {
            $this->db->query("INSERT INTO Staff (name, position, username, isManager) VALUES (:name, :position, :username, :isManager)");
            $this->db->bind(':name', $name);
            $this->db->bind(':position', $position);
            $this->db->bind(':username', $username);
            $this->db->bind(':isManager', $isManager);
            $this->db->execute();
        }

        public function getStaffByUserName($username) {
            $this->db->query("SELECT * FROM staff WHERE username = :username");
            $this->db->bind(':username', $username);
            return $this->db->single();
        }

        public function setStaffInfoByUsername($username, $name, $position) {
            $this->db->query("UPDATE staff SET name = :name, position = :position WHERE username = :username");
            $this->db->bind(':name', $name);
            $this->db->bind(':position', $position);
            $this->db->bind(':username', $username);
            $this->db->execute();
        }

    }

?>