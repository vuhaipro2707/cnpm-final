<?php

class Customer {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllCustomers() {
        $this->db->query("SELECT * FROM customer");
        return $this->db->resultSet();
    }


    // Tạo khách hàng mới
    public function createCustomer($name, $username, $phone, $points) {
        $this->db->query("INSERT INTO customer (name, username, phone, points) 
                          VALUES (:name, :username, :phone, :points)");
        $this->db->bind(':name', $name);
        $this->db->bind(':username', $username);
        $this->db->bind(':phone', $phone);
        $this->db->bind(':points', $points);
        $this->db->execute();
    }

    // Lấy thông tin khách hàng theo ID
    public function getCustomerByCustomerId($customerId) {
        $this->db->query("SELECT * FROM customer WHERE customerId = :customerId");
        $this->db->bind(':customerId', $customerId);
        return $this->db->single();
    }

    // Lấy thông tin khách hàng theo username
    public function getCustomerByUserName($username) {
        $this->db->query("SELECT * FROM customer WHERE username = :username");
        $this->db->bind(':username', $username);
        return $this->db->single();
    }

    // Lấy customerId từ username
    public function getCustomerIdByUserName($username) {
        return $this->getCustomerByUserName($username)['customerId'];
    }

    // Lấy khách hàng theo orderId
    public function getCustomerByOrderId($orderId) {
        $this->db->query("
            SELECT c.* 
            FROM customer c
            INNER JOIN `order` o ON c.customerId = o.customerId
            WHERE o.orderId = :orderId
        ");
        $this->db->bind(':orderId', $orderId);
        return $this->db->single();
    }

    // Cập nhật điểm tích lũy
    public function updateCustomerPoints($customerId, $points) {
        $this->db->query("UPDATE customer SET points = :points WHERE customerId = :customerId");
        $this->db->bind(':points', $points);
        $this->db->bind(':customerId', $customerId);
        $this->db->execute();
    }

    // Cập nhật tên
    public function updateCustomerName($customerId, $name) {
        $this->db->query("UPDATE customer SET name = :name WHERE customerId = :customerId");
        $this->db->bind(':name', $name);
        $this->db->bind(':customerId', $customerId);
        $this->db->execute();
    }

    // Cập nhật số điện thoại
    public function updateCustomerPhone($customerId, $phone) {
        $this->db->query("UPDATE customer SET phone = :phone WHERE customerId = :customerId");
        $this->db->bind(':phone', $phone);
        $this->db->bind(':customerId', $customerId);
        $this->db->execute();
    }
}

?>
