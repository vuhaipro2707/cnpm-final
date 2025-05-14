<?php
    class ProfileController extends Controller {
        public function index() {
            // Lấy role từ session
            $role = $_SESSION['role'] ?? 'guest';  // Nếu chưa có role, mặc định là guest

            // Lấy thông tin người dùng từ session
            $username = $_SESSION['username'] ?? '';

            if ($role === 'customer') {
                // Lấy thông tin khách hàng từ model Customer
                $customerModel = $this->model('Customer');
                $customer = $customerModel->getCustomerByUserName($username);

                // Gọi view profile cho customer
                $this->view('mainpage/profile', ['customer' => $customer]);
            } elseif ($role === 'staff' || $role === 'manager') {
                // Lấy thông tin nhân viên từ model Staff
                $staffModel = $this->model('Staff');
                $staff = $staffModel->getStaffByUserName($username);

                // Gọi view profile cho staff
                $this->view('mainpage/profile', ['staff' => $staff]);
            }
        }


        public function update() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $role = $_POST['role'];
                $username = $_POST['username'];
                $name = trim($_POST['name']);
                $avatar = null;

                
                // 👉 Xử lý avatar nếu có
                if (!empty($_FILES['avatar']['name'])) {
                    $uploadDir = __DIR__ . '/../../public/images/avatar/';
                    $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
                    $filename = 'avatar_' . $username . '.' . $ext; // tên chuẩn hóa
                    $uploadPath = $uploadDir . $filename;

                    // Xoá ảnh cũ nếu có (tùy chọn)
                    foreach (glob($uploadDir . 'avatar_' . $username . '.*') as $oldFile) {
                        unlink($oldFile);
                    }

                    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadPath)) {
                        $avatar = $filename;
                    }
                }

                // Cập nhật dữ liệu chính
                if ($role === 'customer') {
                    $phone = trim($_POST['phone']);
                    $model = $this->model('Customer');
                    $model->setCustomerInfoByUsername($username, $name, $phone);
                } elseif ($role === 'staff' || $role === 'manager') {
                    $position = trim($_POST['position']);
                    $model = $this->model('Staff');
                    $model->setStaffInfoByUsername($username, $name, $position);
                }

                // Cập nhật avatar nếu có
                if ($avatar) {
                    $accModel = $this->model('Account');
                    $accModel->updateAvatar($username, $avatar);
                    $_SESSION['avatar'] = $avatar;
                }

                header('Location: /cnpm-final/ProfileController/index');
                exit;
            }
        }



    }
?>