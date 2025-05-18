<?php
    class Table {
        private $db;

        public function __construct() {
            $this->db = new Database();
        }

        public function getAllLayout() {
            $this->db->query("SELECT * FROM TableLayout");
            return $this->db->resultSet();
            // return array_column($rows, 'layoutPosition');
        }

        public function getTableByTableNumber($tableNumber) {
            $this->db->query("SELECT * FROM TableLayout WHERE tableNumber = :tableNumber");
            $this->db->bind(':tableNumber', $tableNumber);
            return $this->db->single();
        }

        public function updateStatusByPosition($pos, $status) {
            $this->db->query("UPDATE TableLayout SET status = :status WHERE layoutPosition = :pos");
            $this->db->bind(':status', $status);
            $this->db->bind(':pos', $pos);
            $this->db->execute();
        }

        public function updateTableNumberByPosition($pos, $tableNumber) {
            $this->db->query("UPDATE TableLayout SET tableNumber = :tableNumber WHERE layoutPosition = :pos");
            $this->db->bind(':tableNumber', $tableNumber);
            $this->db->bind(':pos', $pos);
            $this->db->execute();
        }

        public function createTable($data) {
            $this->db->query("INSERT INTO TableLayout (layoutPosition, tableNumber, status) 
                        VALUES (:layoutPosition, :tableNumber, :status)");
            $this->db->bind(':layoutPosition', $data['layoutPosition']);
            $this->db->bind(':tableNumber', $data['tableNumber']);
            $this->db->bind(':status', $data['status']);
            $this->db->execute();
        }



    }

?>