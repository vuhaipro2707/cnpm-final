<?php

    class Inventory {
        private $db;

        public function __construct() {
            $this->db = new Database();
        }

        public function getAllItems() {
            $this->db->query("SELECT item.itemId, item.name, item.price, inventory.quantity, item.note, item.type FROM inventory LEFT JOIN item ON inventory.itemId = item.itemId");
            return $this->db->resultSet();
        }

        public function getItemById($itemId) {
            $this->db->query("SELECT * FROM inventory WHERE itemId = :itemId");
            $this->db->bind(':itemId', $itemId);
            return $this->db->single();
        }

        public function getAllItemsGroupType() {
            $this->db->query('SELECT item.itemId, item.name, item.price, inventory.quantity, item.note, item.type FROM inventory LEFT JOIN item ON inventory.itemId = item.itemId');
            $rows = $this->db->resultSet();

            $itemsByType = [];

            foreach ($rows as &$row) {
                $type = $row['type'];
                unset($row['type']);
                $itemsByType[$type][] = $row;
            }
            unset($row);
            return $itemsByType;
        }

        public function deleteItem($itemId, $quantity) {
            // Giảm số lượng sản phẩm
            $this->db->query("UPDATE inventory SET quantity = quantity - :qty WHERE itemId = :itemId AND quantity >= :qty");
            $this->db->bind(':itemId', $itemId);
            $this->db->bind(':qty', $quantity);
            $this->db->execute();

            // Nếu sau khi trừ số lượng <= 0 thì cập nhật quantity về 0 thay vì xóa
            $this->db->query("UPDATE inventory SET quantity = 0 WHERE itemId = :itemId AND quantity <= 0");
            $this->db->bind(':itemId', $itemId);
            $this->db->execute();
        }
    }
    
?>
