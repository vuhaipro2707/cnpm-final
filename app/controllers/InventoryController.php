<?php

    class InventoryController extends Controller {
        public function displayAllItem() {
            $data = $this->model('Inventory')->getAllItems();
            // Gọi view để hiển thị giao diện đăng nhập
            $this->view('manager/track_inventory', $data);
        }

        public function deleteItem() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['id'];
                $quantity = $_POST['quantity'];

                $this->model('Inventory')->deleteItem($id, $quantity);

                // Redirect về danh sách
                header('Location: /cnpm-final/InventoryController/displayAllItem');
                exit;
            }
        }
    }


?>