<?php

class Inventory {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllItems() {
        $this->db->query("SELECT item.itemId, item.name, item.price, inventory.quantity FROM inventory LEFT JOIN item ON inventory.itemId = item.itemId");
        return $this->db->resultSet();
    }

    public function getItemById($itemId) {
        $this->db->query("SELECT * FROM inventory WHERE itemId = :itemId");
        $this->db->bind(':itemId', $itemId);
        return $this->db->single();
    }

    public function deleteItem($itemId, $quantity) {
        // Giảm số lượng sản phẩm
        $this->db->query("UPDATE inventory SET quantity = quantity - :qty WHERE itemId = :itemId AND quantity >= :qty");
        $this->db->bind(':itemId', $itemId);
        $this->db->bind(':qty', $quantity);
        $this->db->execute();

        // Nếu sau khi trừ số lượng <= 0 thì xóa luôn
        $this->db->query("DELETE FROM inventory WHERE itemId = :itemId AND quantity <= 0");
        $this->db->bind(':itemId', $itemId);
        $this->db->execute();
    }

}
