<?php

class Inventory {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllItems() {
        $this->db->query("SELECT * FROM inventory");
        return $this->db->resultSet();
    }

    public function getItemById($id) {
        $this->db->query("SELECT * FROM inventory WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function deleteItem($id, $quantity) {
        // Giảm số lượng sản phẩm
        $this->db->query("UPDATE inventory SET quantity = quantity - :qty WHERE id = :id AND quantity >= :qty");
        $this->db->bind(':id', $id);
        $this->db->bind(':qty', $quantity);
        $this->db->execute();

        // Nếu sau khi trừ số lượng <= 0 thì xóa luôn
        $this->db->query("DELETE FROM inventory WHERE id = :id AND quantity <= 0");
        $this->db->bind(':id', $id);
        $this->db->execute();
    }

}
