<?php
    class Order {
        private $db;

        public function __construct() {
            $this->db = new Database();
        }

        public function createOrder($tableNumber, $layoutPosition, $customerId, $status, $date, $cartData) {
            $this->db->query("INSERT INTO `order` (tableNumber, layoutPosition, customerId, status, date) VALUES (:tableNumber, :layoutPosition, :customerId, :status, :date)");
            $this->db->bind(':tableNumber', $tableNumber);
            $this->db->bind(':layoutPosition', $layoutPosition);
            $this->db->bind(':customerId', $customerId);
            $this->db->bind(':status', $status);
            $this->db->bind(':date', $date);
            $this->db->execute();

            // 2. Lấy ID đơn hàng vừa tạo
            $orderId = $this->db->lastInsertId();

            // 3. Thêm từng món vào bảng orderincludeitem
            foreach ($cartData as $item) {
                $this->db->query("INSERT INTO orderincludeitem (orderId, itemId, quantity) VALUES (:orderId, :itemId, :quantity)");
                $this->db->bind(':orderId', $orderId);
                $this->db->bind(':itemId', $item['itemId']);
                $this->db->bind(':quantity', $item['quantity']);
                $this->db->execute();
            }

            return $orderId;
        }

        public function getAllPendingSuccessOrdersByTableNumber($tableNumber) {
            $this->db->query("
            SELECT * 
            FROM orderincludeitem oi
            JOIN `order` o ON oi.orderId = o.orderId
            WHERE o.tableNumber = :tableNumber AND (o.status = 'pending' OR o.status = 'success')
            ORDER BY 
                CASE 
                    WHEN o.status = 'pending' THEN 1
                    WHEN o.status = 'failed' THEN 2
                    WHEN o.status = 'success' THEN 3
                    ELSE 4
                END,
                o.date ASC
            ");

            $this->db->bind("tableNumber", $tableNumber);

            $rows =  $this->db->resultSet();
            return $this->mergeOrderbyOrderId($rows);
        }


        private function mergeOrderbyOrderId($rows) {
            $orders = [];

            foreach ($rows as $row) {
                $orderId = $row['orderId'];

                if (!isset($orders[$orderId])) {
                    $orderData = $row;
                    unset($orderData['itemId'], $orderData['quantity']);
                    $orderData['itemsPerOrder'] = [];

                    $orders[$orderId] = $orderData;
                }

                $orders[$orderId]['itemsPerOrder'][] = [
                    'itemId' => $row['itemId'],
                    'quantity' => $row['quantity']
                ];
            }

            return array_values($orders);
        }


        public function getAllOrders() {
            $this->db->query("
            SELECT * 
            FROM orderincludeitem oi
            JOIN `order` o ON oi.orderId = o.orderId
            ORDER BY 
                CASE 
                    WHEN o.status = 'pending' THEN 1
                    WHEN o.status = 'failed' THEN 2
                    WHEN o.status = 'success' THEN 3
                    ELSE 4
                END,
                o.date ASC
            ");

            $rows =  $this->db->resultSet();
            return $this->mergeOrderbyOrderId($rows);
        }

        public function getOrderById($orderId) {
            $this->db->query("SELECT * FROM orderincludeitem oi 
                            JOIN `order` o ON oi.orderId = o.orderId 
                            WHERE oi.orderId = :orderId
                            ORDER BY 
                            CASE 
                                WHEN o.status = 'success' THEN 1
                                WHEN o.status = 'pending' THEN 2
                                WHEN o.status = 'failed' THEN 3
                                ELSE 4
                            END,
                            o.date ASC");
            $this->db->bind(':orderId', $orderId);
            $rows = $this->db->resultSet();
            
            return $this->mergeOrderbyOrderId($rows)[0];
        }

        public function getOrderByCustomerId($customerId) {
            $this->db->query("SELECT * FROM orderincludeitem oi 
                            JOIN `order` o ON oi.orderId = o.orderId 
                            WHERE o.customerId = :customerId
                            ORDER BY 
                            CASE 
                                WHEN o.status = 'success' THEN 1
                                WHEN o.status = 'pending' THEN 2
                                WHEN o.status = 'failed' THEN 3
                                ELSE 4
                            END,
                            o.date ASC");
            $this->db->bind(':customerId', $customerId);
            $rows = $this->db->resultSet();
            return $this->mergeOrderbyOrderId($rows);
        }

        public function confirmOrder($orderId, $status) {
            $this->db->query('UPDATE `order` SET status = :status WHERE orderId = :orderId');
            $this->db->bind(':orderId', $orderId);
            $this->db->bind(':status', $status);
            $this->db->execute();
        }

        public function updateStaffId($orderId, $staffId) {
            $this->db->query('UPDATE `order` SET staffId = :staffId WHERE orderId = :orderId');
            $this->db->bind(':orderId', $orderId);
            $this->db->bind(':staffId', $staffId);
            return $this->db->execute();
        }

        public function countTotalOrderByStatus() {
            $this->db->query("SELECT 
                                        status,
                                        COUNT(*) AS totalOrders
                                    FROM 
                                        `order`
                                    WHERE 
                                        status IN ('pending', 'success', 'failed', 'paid')
                                    GROUP BY 
                                        status;
                                    ");
            return $this->db->resultSet();
        }

    }
?>