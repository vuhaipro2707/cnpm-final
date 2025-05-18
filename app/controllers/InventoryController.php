<?php

    class InventoryController extends Controller {
        public function displayAllItem() {
            $data = $this->model('Inventory')->getAllItems();
            // Gọi view để hiển thị giao diện đăng nhập
            $this->view('manager/track_inventory', $data);
        }

        public function customerMenuPage($state = null, $orderId = null) {
            $tableModel = $this->model('Table');
            $inventoryModel = $this->model('Inventory');

            $inventory = $inventoryModel->getAllItemsGroupType();
            $table = $tableModel->getAllLayout();

            if ($state == 'error') {
                $this->view('customer/menu_order', ['itemsByType'=> $inventory , 'table' => $table, 'error'=> 'Bạn chưa đặt món hoặc chưa có bàn vui lòng thử lại.']);
            } else if ($state == 'success') {
                $this->view('customer/menu_order', ['itemsByType'=> $inventory , 'table' => $table, 'success'=> 'Đặt món thành công! Hãy kiểm tra đơn đã đặt. Mã đơn: ' . $orderId]);
            } else {
                $this->view('customer/menu_order', ['itemsByType'=> $inventory , 'table' => $table]);
            }
        }

        public function deleteItem() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $itemId = $_POST['itemId'];

                $this->model('Inventory')->deleteItem($itemId);

                $_SESSION['message'] = [
                    'type' => 'success',
                    'text' => 'Đã xóa sản phẩm thành công! Mã sản phẩm:' . $itemId
                ];
                header('Location: /cnpm-final/InventoryController/displayAllItem');
                exit;
            }
        }

        public function subtractItem() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $itemId = $_POST['itemId'] ?? null;
                $quantity = intval($_POST['quantity'] ?? 0);
                $inventoryModel = $this->model('Inventory');

                if ($itemId && $quantity > 0) {
                    $currentItem = $inventoryModel->getItemById($itemId);
                    if ($currentItem && $currentItem['quantity'] >= $quantity) {
                        $newQuantity = $currentItem['quantity'] - $quantity;
                        $inventoryModel->updateQuantity($itemId, $newQuantity);

                        $_SESSION['message'] = [
                            'type' => 'success',
                            'text' => "Đã trừ $quantity sản phẩm khỏi Id: $itemId"
                        ];
                    } else {
                        $_SESSION['message'] = [
                            'type' => 'danger',
                            'text' => "Số lượng trừ không hợp lệ, không đủ hàng."
                        ];
                    }
                } else {
                    $_SESSION['message'] = [
                        'type' => 'danger',
                        'text' => "Thông tin gửi lên không hợp lệ."
                    ];
                }

                header("Location: /cnpm-final/InventoryController/displayAllItem");
                exit;
            }
        }

        public function restockItem() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $itemId = $_POST['itemId'] ?? null;
                $quantity = intval($_POST['quantity'] ?? 0);
                $inventoryModel = $this->model('Inventory');

                if ($itemId && $quantity > 0) {
                    $currentItem = $inventoryModel->getItemById($itemId);
                    if ($currentItem) {
                        $newQuantity = $currentItem['quantity'] + $quantity;
                        $inventoryModel->updateQuantity($itemId, $newQuantity);

                        $_SESSION['message'] = [
                            'type' => 'success',
                            'text' => "Đã nhập thêm $quantity sản phẩm cho Id: $itemId"
                        ];
                    } else {
                        $_SESSION['message'] = [
                            'type' => 'danger',
                            'text' => "Sản phẩm không tồn tại."
                        ];
                    }
                } else {
                    $_SESSION['message'] = [
                        'type' => 'danger',
                        'text' => "Thông tin gửi lên không hợp lệ."
                    ];
                }

                header("Location: /cnpm-final/InventoryController/displayAllItem");
                exit;
            }
        }

        public function createItem() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $name = trim($_POST['name']);
                $price = str_replace('.', '', $_POST['price']);
                $quantity = (int) $_POST['quantity'];
                $type = $_POST['type'];
                $note = $_POST['note'];

                if (empty($name) || $price <= 0 || $quantity <= 0) {
                    $_SESSION['message'] = [
                        'type' => 'danger',
                        'text' => 'Vui lòng điền đầy đủ và hợp lệ thông tin món ăn.'
                    ];
                    header('Location: /cnpm-final/InventoryController/displayAllItem');
                    exit;
                }

                if (empty($_FILES['image']['name'])) {
                    $_SESSION['message'] = [
                        'type' => 'danger',
                        'text' => 'Ảnh món ăn là bắt buộc.'
                    ];
                    header('Location: /cnpm-final/InventoryController/displayAllItem');
                    exit;
                }

                // Tạo tên file từ tên món ăn
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $baseName = $this->slugify($name);
                $filename = $baseName . '.' . $ext;

                $uploadDir = __DIR__ . '/../../public/images/productCard/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $uploadPath = $uploadDir . $filename;

                // Xoá ảnh cũ nếu trùng tên
                foreach (glob($uploadDir . $baseName . '.*') as $oldFile) {
                    unlink($oldFile);
                }

                if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                    $_SESSION['message'] = [
                        'type' => 'danger',
                        'text' => 'Tải ảnh lên thất bại.'
                    ];
                    header('Location: /cnpm-final/InventoryController/displayAllItem');
                    exit;
                }

                $model = $this->model('Inventory');
                $model->addItem($name, $price, $quantity, $note, $type, $filename);

                $_SESSION['message'] = [
                    'type' => 'success',
                    'text' => 'Đã thêm món "' . htmlspecialchars($name) . '" thành công!'
                ];
                header('Location: /cnpm-final/InventoryController/displayAllItem');
                exit;
            }
        }
        private function slugify($text) {
            $vietCharMap = [
                'à'=>'a','á'=>'a','ạ'=>'a','ả'=>'a','ã'=>'a','â'=>'a','ầ'=>'a','ấ'=>'a','ậ'=>'a','ẩ'=>'a','ẫ'=>'a',
                'ă'=>'a','ằ'=>'a','ắ'=>'a','ặ'=>'a','ẳ'=>'a','ẵ'=>'a',
                'è'=>'e','é'=>'e','ẹ'=>'e','ẻ'=>'e','ẽ'=>'e','ê'=>'e','ề'=>'e','ế'=>'e','ệ'=>'e','ể'=>'e','ễ'=>'e',
                'ì'=>'i','í'=>'i','ị'=>'i','ỉ'=>'i','ĩ'=>'i',
                'ò'=>'o','ó'=>'o','ọ'=>'o','ỏ'=>'o','õ'=>'o','ô'=>'o','ồ'=>'o','ố'=>'o','ộ'=>'o','ổ'=>'o','ỗ'=>'o',
                'ơ'=>'o','ờ'=>'o','ớ'=>'o','ợ'=>'o','ở'=>'o','ỡ'=>'o',
                'ù'=>'u','ú'=>'u','ụ'=>'u','ủ'=>'u','ũ'=>'u','ư'=>'u','ừ'=>'u','ứ'=>'u','ự'=>'u','ử'=>'u','ữ'=>'u',
                'ỳ'=>'y','ý'=>'y','ỵ'=>'y','ỷ'=>'y','ỹ'=>'y',
                'đ'=>'d',
                'À'=>'A','Á'=>'A','Ạ'=>'A','Ả'=>'A','Ã'=>'A','Â'=>'A','Ầ'=>'A','Ấ'=>'A','Ậ'=>'A','Ẩ'=>'A','Ẫ'=>'A',
                'Ă'=>'A','Ằ'=>'A','Ắ'=>'A','Ặ'=>'A','Ẳ'=>'A','Ẵ'=>'A',
                'È'=>'E','É'=>'E','Ẹ'=>'E','Ẻ'=>'E','Ẽ'=>'E','Ê'=>'E','Ề'=>'E','Ế'=>'E','Ệ'=>'E','Ể'=>'E','Ễ'=>'E',
                'Ì'=>'I','Í'=>'I','Ị'=>'I','Ỉ'=>'I','Ĩ'=>'I',
                'Ò'=>'O','Ó'=>'O','Ọ'=>'O','Ỏ'=>'O','Õ'=>'O','Ô'=>'O','Ồ'=>'O','Ố'=>'O','Ộ'=>'O','Ổ'=>'O','Ỗ'=>'O',
                'Ơ'=>'O','Ờ'=>'O','Ớ'=>'O','Ợ'=>'O','Ở'=>'O','Ỡ'=>'O',
                'Ù'=>'U','Ú'=>'U','Ụ'=>'U','Ủ'=>'U','Ũ'=>'U','Ư'=>'U','Ừ'=>'U','Ứ'=>'U','Ự'=>'U','Ử'=>'U','Ữ'=>'U',
                'Ỳ'=>'Y','Ý'=>'Y','Ỵ'=>'Y','Ỷ'=>'Y','Ỹ'=>'Y',
                'Đ'=>'D'
            ];
            $text = strtr($text, $vietCharMap);

            $text = strtolower($text);

            $text = preg_replace('~[^a-z0-9]+~', '', $text);

            return $text;
        }




    }
?>