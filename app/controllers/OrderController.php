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