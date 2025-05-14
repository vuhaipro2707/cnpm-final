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

        public function createAccount($username, $password, $role, $avatar) {
            $this->db->query("INSERT INTO Account (username, password, role, avatar) 
                            VALUES (:username, :password, :role, :avatar)");
            $this->db->bind(':username', $username);
            $this->db->bind(':password', $password);
            $this->db->bind(':role', $role);
            $this->db->bind(':avatar', $avatar);
            $this->db->execute();
        }

        public function updateAvatar($username, $avatar) {
            $this->db->query("UPDATE account SET avatar = :avatar WHERE username = :username");
            $this->db->bind(':avatar', $avatar);
            $this->db->bind(':username', $username);
            $this->db->execute();
        }

        public function updatePassword($username, $password) {
            $this->db->query("UPDATE account SET password = :password WHERE username = :username");
            $this->db->bind(':password', $password);
            $this->db->bind(':username', $username);
            $this->db->execute();
        }

        public function updateRole($username, $role) {
            $this->db->query("UPDATE account SET role = :role WHERE username = :username");
            $this->db->bind(':role', $role);
            $this->db->bind(':username', $username);
            $this->db->execute();
        }

        public function updateUsername($oldUsername, $newUsername) {
            $this->db->query("UPDATE account SET username = :newUsername WHERE username = :oldUsername");
            $this->db->bind(':newUsername', $newUsername);
            $this->db->bind(':oldUsername', $oldUsername);
            $this->db->execute();
        }

        public function deleteAccount($username) {
            $this->db->query("DELETE FROM account WHERE username = :username");
            $this->db->bind(':username', $username);
            $this->db->execute();
        }


    }

?>