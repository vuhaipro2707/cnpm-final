<?php
    class OrderController extends Controller {

        public function orderConfirmPage($error = null) {
            $orderModel = $this->model('Order');
            $ordersRaw = $orderModel->getAllOrders();
            $enricher = new DataEnricher([$this, 'model']);
            $orders = $enricher->getAllOrdersInfo($ordersRaw);

            $this->view('staff/confirm_order', ['orders'=> $orders, 'error'=> $error]);
        }

        public function customerTrackOrderPage($error = null, $success = null) {
            $orderModel = $this->model('Order');
            $customerModel = $this->model('Customer');
            $ordersRaw = $orderModel->getOrderByCustomerId($customerModel->getCustomerIdByUserName($_SESSION['username']));
            $enricher = new DataEnricher([$this, 'model']);
            $orders = $enricher->getAllOrdersInfo($ordersRaw);
            $this->view('customer/track_order', ['orders' => $orders,'error'=> $error, 'success'=> $success]);
        }

        public function tableTrackOrder() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $tableNumber = $_POST['tableNumber'];
                $layoutPosition = $_POST['layoutPosition'];
                $orderModel = $this->model('Order');
                $ordersRaw = $orderModel->getAllPendingSuccessOrdersByTableNumber($tableNumber);
                $enricher = new DataEnricher([$this, 'model']);
                $orders = $enricher->getAllOrdersInfo($ordersRaw);

                $this->view('staff/table_order', ['orders'=> $orders, 'tableNumber'=> $tableNumber, 'layoutPosition' => $layoutPosition]);
            }
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
                $tableModel = $this->model('Table');

                // Lấy thông tin đơn hàng
                $customerId = $customerModel->getCustomerIdByUserName($_SESSION['username']);
                $tableNumber = $_SESSION['readyOrder']['tableNumber'];
                date_default_timezone_set('Asia/Ho_Chi_Minh');
                $date = date('Y-m-d H:i:s');
                $status = 'pending';
                $layoutPosition = $tableModel->getTableByTableNumber($tableNumber)['layoutPosition'];

                // Tạo đơn hàng
                $orderId = $orderModel->createOrder($tableNumber, $layoutPosition, $customerId, $status, $date, $cartData);
                
                // Đổi trạng thái bàn
                $tableModel->updateStatusByPosition($layoutPosition, 'serving');
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
                $tableModel = $this->model('Table');

                // Nếu trạng thái là success thì kiểm tra và trừ kho
                if ($status === 'success') {
                    // Lấy danh sách món
                    $orderItems = $orderModel->getOrderById($orderId)['itemsPerOrder'];
                    
                    // Kiểm tra tồn kho
                    foreach ($orderItems as $item) {
                        $inventoryItem = $inventoryModel->getItemById($item['itemId']);

                        if (!$inventoryItem) {
                            $error = "Món không tồn tại hoặc đã bị xóa, hãy từ chối đơn.";
                            $this->orderConfirmPage($error);
                            return;
                        }

                        if ($inventoryItem['quantity'] < $item['quantity']) {
                            $error = "Hiện tại không đủ số lượng món, vui lòng thử lại.";
                            $this->orderConfirmPage($error);
                            return;
                        }
                    }


                    // Trừ tồn kho
                    foreach ($orderItems as $item) {
                        $inventoryItem = $inventoryModel->getItemById($item['itemId']);
                        $inventoryModel->updateQuantity($item['itemId'], $inventoryItem['quantity'] - $item['quantity']);
                    }
                }
                
                // Cập nhật trạng thái đơn
                $orderModel->confirmOrder($orderId, $status);

                if ($status === 'failed') {
                    //xử lý table
                    $tableNumber = $orderModel->getOrderById($orderId)['tableNumber'];
                    $orderOnTableLeft = $orderModel->getAllPendingSuccessOrdersByTableNumber($tableNumber);

                    if(empty($orderOnTableLeft)) {
                        $pos = $tableModel->getTableByTableNumber($tableNumber)['layoutPosition'];
                        $tableModel->updateStatusByPosition($pos, 'empty');
                    }
                }

                header('Location: /cnpm-final/OrderController/orderConfirmPage');
                exit;
            }
        }

    }
?>