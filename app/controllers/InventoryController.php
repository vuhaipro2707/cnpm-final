<?php

    class InventoryController extends Controller {
        public function displayAllItem() {
            $data = $this->model('Inventory')->getAllItems();
            // Gọi view để hiển thị giao diện đăng nhập
            $this->view('manager/track_inventory', $data);
        }
    }

?>