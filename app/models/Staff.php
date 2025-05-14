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

        public function createStaff($name, $position, $username, $isManager = 0, $phone = null, $salary = 0) {
            $this->db->query("INSERT INTO Staff (name, position, username, isManager, phone, salary) 
                            VALUES (:name, :position, :username, :isManager, :phone, :salary)");
            $this->db->bind(':name', $name);
            $this->db->bind(':position', $position);
            $this->db->bind(':username', $username);
            $this->db->bind(':isManager', $isManager);
            $this->db->bind(':phone', $phone);
            $this->db->bind(':salary', $salary);
            $this->db->execute();
        }


        public function getStaffByUserName($username) {
            $this->db->query("SELECT * FROM staff WHERE username = :username");
            $this->db->bind(':username', $username);
            return $this->db->single();
        }

        public function getStaffByStaffId($staffId) {
            $this->db->query("SELECT * FROM staff WHERE staffId = :staffId");
            $this->db->bind(':staffId', $staffId);
            return $this->db->single();
        }

        public function updateStaffName($staffId, $name) {
            $this->db->query("UPDATE staff SET name = :name WHERE staffId = :staffId");
            $this->db->bind(':name', $name);
            $this->db->bind(':staffId', $staffId);
            $this->db->execute();
        }

        public function updateStaffPosition($staffId, $position) {
            $this->db->query("UPDATE staff SET position = :position WHERE staffId = :staffId");
            $this->db->bind(':position', $position);
            $this->db->bind(':staffId', $staffId);
            $this->db->execute();
        }

        public function updateStaffIsManager($staffId, $isManager) {
            $this->db->query("UPDATE staff SET isManager = :isManager WHERE staffId = :staffId");
            $this->db->bind(':isManager', $isManager);
            $this->db->bind(':staffId', $staffId);
            $this->db->execute();
        }

        public function updateStaffSalary($staffId, $salary) {
            $this->db->query("UPDATE staff SET salary = :salary WHERE staffId = :staffId");
            $this->db->bind(':salary', $salary);
            $this->db->bind(':staffId', $staffId);
            $this->db->execute();
        }


        public function updateStaffPhone($staffId, $phone) {
            $this->db->query("UPDATE staff SET phone = :phone WHERE staffId = :staffId");
            $this->db->bind(':phone', $phone);
            $this->db->bind(':staffId', $staffId);
            $this->db->execute();
        }

        public function updateStaffInfoByStaffId($staffId, $name, $position, $phone, $salary, $isManager) {
            $query = "UPDATE staff SET name = :name, position = :position, phone = :phone, salary = :salary, isManager = :isManager WHERE staffId = :staffId";
            
            $this->db->query($query);
            
            $this->db->bind(':staffId', $staffId);
            $this->db->bind(':name', $name);
            $this->db->bind(':position', $position);
            $this->db->bind(':phone', $phone);
            $this->db->bind(':salary', $salary);
            $this->db->bind(':isManager', $isManager);

            $this->db->execute();
        }

        public function deleteStaff($staffId) {
            $this->db->query("DELETE FROM staff WHERE staffId = :staffId");
            $this->db->bind(':staffId', $staffId);
            $this->db->execute();
        }

    }

?>