<?php
    class OrderController extends Controller {
        private function getAllOrdersInfo($orders) {
            $customerModel = $this->model('Customer');
            $ItemModel = $this->model('Item');

            foreach ($orders as &$order) {
                $order['customer'] = $customerModel->getCustomerById($order['customerId']);
                unset($order['customerId']);

                foreach ($order['itemsPerOrder'] as &$item) {
                    $itemInfo = $ItemModel->getItemById($item['itemId']);
                    unset($item['itemId']);
                    $item = array_merge($item, $itemInfo);
                }
                unset($item);
            }
            unset($order);
            return $orders;
        }

        public function orderConfirmPage($error = null) {
            $orderModel = $this->model('Order');
            $ordersRaw = $orderModel->getAllOrders();
            $orders = $this->getAllOrdersInfo($ordersRaw);

            $this->view('staff/confirm_order', ['orders'=> $orders, 'error'=> $error]);
        }

        public function customerTrackOrderPage($error = null) {
            $orderModel = $this->model('Order');
            $customerModel = $this->model('Customer');
            $ordersRaw = $orderModel->getOrderByCustomerId($customerModel->getCustomerIdByUserName($_SESSION['username']));
            $orders = $this->getAllOrdersInfo($ordersRaw);
            $this->view('customer/track_order', ['orders' => $orders,'error'=> $error]);
        }

        public function createOrder() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Kiểm tra giỏ hàng trong session readyOrder
                if (
                    !isset($_SESSION['readyOrder']) ||
                    !isset($_SESSION['readyOrder']['cart']) ||
                    empty($_SESSION['readyOrder']['cart']) ||
                    !isset($_SESSION['readyOrder']['tableNumber'])
                ) {
                    // Giỏ hàng rỗng
                    header('Location: /cnpm-final/InventoryController/customerMenuPage/error');
                    exit;
                }

                $cartData = $_SESSION['readyOrder']['cart'];

                $orderModel = $this->model('Order');
                $customerModel = $this->model('Customer');

                // Lấy thông tin đơn hàng
                $customerId = $customerModel->getCustomerIdByUserName($_SESSION['username']);
                $tableNumber = $_SESSION['readyOrder']['tableNumber'];
                $date = date('Y-m-d H:i:s');
                $status = 'pending';
                // Tạo đơn hàng
                $orderId = $orderModel->createOrder($tableNumber, $customerId, $status, $date, $cartData);
                unset($_SESSION['readyOrder']);
                header("Location: /cnpm-final/InventoryController/customerMenuPage/success/" . $orderId);
            }
        }




        public function confirmOrder() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $orderId = $_POST['orderId'];
                $status = $_POST['status'];
                

                $orderModel = $this->model('Order');
                $inventoryModel = $this->model('Inventory');

                // Nếu trạng thái là success thì kiểm tra và trừ kho
                if ($status === 'success') {
                    // Lấy danh sách món
                    $orderItems = $orderModel->getOrderById($orderId)['itemsPerOrder'];

                    // Kiểm tra tồn kho
                    foreach ($orderItems as $item) {
                        $inventoryItem = $inventoryModel->getItemById($item['itemId']);
                        if ($inventoryItem['quantity'] < $item['quantity']) {
                            $error = "Hiện tại không đủ số lượng món, vui lòng thử lại.";
                            $this->orderConfirmPage($error);
                            return;
                        }
                    }

                    // Trừ tồn kho
                    foreach ($orderItems as $item) {
                        $inventoryModel->deleteItem($item['itemId'], $item['quantity']);
                    }
                }

                // Cập nhật trạng thái đơn
                $orderModel->confirmOrder($orderId, $status);
                header('Location: /cnpm-final/OrderController/orderConfirmPage');
                exit;
            }
        }

    }
?>