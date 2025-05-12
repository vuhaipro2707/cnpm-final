<?php

    class InventoryController extends Controller {
        public function displayAllItem() {
            $data = $this->model('Inventory')->getAllItems();
            // Gọi view để hiển thị giao diện đăng nhập
            $this->view('manager/track_inventory', $data);
        }

        public function customerMenuPage($state = null, $orderId = null) {
            $data = $this->model('Inventory')->getAllItemsGroupType();
            if ($state == 'error') {
                $this->view('customer/menu_order', ['itemsByType'=> $data, 'error'=> 'Bạn chưa đặt món hoặc chưa có bàn vui lòng thử lại.']);
            } else if ($state == 'success') {
                $this->view('customer/menu_order', ['itemsByType'=> $data, 'success'=> 'Đặt món thành công! Hãy kiểm tra đơn đã đặt. Mã đơn: ' . $orderId]);
            } else {
                $this->view('customer/menu_order', ['itemsByType'=> $data]);
            }
        }

        public function deleteItem() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $itemId = $_POST['itemId'];
                $quantity = $_POST['quantity'];

                $this->model('Inventory')->deleteItem($itemId, $quantity);

                // Redirect về danh sách
                header('Location: /cnpm-final/InventoryController/displayAllItem');
                exit;
            }
        }

        
    }
?>