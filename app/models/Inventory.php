<?php

    class Inventory {
        private $db;

        public function __construct() {
            $this->db = new Database();
        }

        public function getAllItems() {
            $this->db->query("SELECT * FROM inventory LEFT JOIN item ON inventory.itemId = item.itemId");
            return $this->db->resultSet();
        }

        public function getItemById($itemId) {
            $this->db->query("SELECT * FROM inventory WHERE itemId = :itemId");
            $this->db->bind(':itemId', $itemId);
            return $this->db->single();
        }

        public function getAllItemsGroupType() {
            $this->db->query('SELECT item.itemId, item.name, item.price, inventory.quantity, item.note, item.type, item.image FROM inventory LEFT JOIN item ON inventory.itemId = item.itemId');
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

        public function addItem($name, $price, $quantity, $note, $type, $image) {
            $this->db->query("INSERT INTO item (name, price, note, type, image) VALUES (:name, :price, :note, :type, :image)");
            $this->db->bind(':name', $name);
            $this->db->bind(':price', $price);
            $this->db->bind(':note', $note);
            $this->db->bind(':type', $type);
            $this->db->bind(':image', $image);
            $this->db->execute();

            $itemId = $this->db->lastInsertId();

            $this->db->query("INSERT INTO inventory (itemId, quantity) VALUES (:itemId, :quantity)");
            $this->db->bind(':itemId', $itemId);
            $this->db->bind(':quantity', $quantity);
            $this->db->execute();
        }

        public function deleteItem($itemId) {
            $this->db->query("DELETE FROM inventory WHERE itemId = :itemId");
            $this->db->bind(':itemId', $itemId);
            $this->db->execute();
        }

        public function updateQuantity($itemId, $quantity) {
            $this->db->query("UPDATE inventory SET quantity = :quantity WHERE itemId = :itemId");
            $this->db->bind(':quantity', $quantity);
            $this->db->bind(':itemId', $itemId);
            $this->db->execute();
        }
    }
    
?>
