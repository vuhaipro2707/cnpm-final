<?php
    class AuthController extends Controller {


        public function login() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $username = $_POST['username'];
                $password = $_POST['password'];
                $role = $_POST['role'];

                $accountModel = $this->model('Account');
                $account = $accountModel->getAccountInfoByUsername($username);

                if ($account && $password == $account['password']) {
                    if (
                        $role === $account['role'] || ($role === 'staff' && $account['role'] === 'manager' || $account['role'] === 'admin')
                    ) {
                        $_SESSION['username'] = $account['username'];
                        $_SESSION['role'] = $account['role'];
                        $_SESSION['avatar'] = $account['avatar'];
                        header('Location: /cnpm-final/HomeController/index');
                        exit;
                    }
                }

                $error = "Sai tài khoản hoặc mật khẩu hoặc vai trò.";
                $this->view('mainpage/login', ['error' => $error]);
            } else {
                $this->view('mainpage/login');
            }
        }


        public function logout() {
            session_destroy();
            header('Location: /cnpm-final/auth/login');
            exit;
        }

        public function customerRegister() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $name = $_POST['name'];
                $phone = $_POST['phone'];
                $username = $_POST['username'];
                $password = $_POST['password'];
                $confirm = $_POST['confirm_password'];
                $role = 'customer';

                $_SESSION['remCusRegister'] = [
                    'name' => $name,
                    'phone' => $phone,
                    'username' => $username
                ];

                $accountModel = $this->model('Account');
                $customerModel = $this->model('Customer');

                if ($password !== $confirm) {
                    $this->view('mainpage/register', ['error' => 'Mật khẩu xác nhận không khớp.']);
                    return;
                }

                if ($accountModel->getAccountInfoByUsername($username)) {
                    $this->view('mainpage/register', ['error' => 'Tên đăng nhập đã tồn tại.']);
                    return;
                }

                // Xử lý upload avatar (nếu có)
                $avatar = null;
                if (!empty($_FILES['avatar']['name'])) {
                    $uploadDir = __DIR__ . '/../../public/images/avatar/';
                    $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
                    $filename = 'avatar_' . $username . '.' . $ext; // Tên chuẩn hóa
                    $uploadPath = $uploadDir . $filename;

                    // Xoá ảnh cũ nếu có (nếu có ảnh cũ thì sẽ xóa trước khi upload ảnh mới)
                    foreach (glob($uploadDir . 'avatar_' . $username . '.*') as $oldFile) {
                        unlink($oldFile);
                    }

                    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadPath)) {
                        $avatar = $filename;
                    }
                }

                // $hash = password_hash($password, PASSWORD_DEFAULT);
                $accountModel->createAccount($username, $password, $role, $avatar);
                $customerModel->createCustomer($name, $username, $phone, 0);

                unset($_SESSION['remCusRegister']);

                $this->view('mainpage/login', ['success' => 'Đăng ký thành công! Bạn có thể đăng nhập.']);
            } else {
                $this->view('mainpage/register');
            }
        }

        public function staffRegister() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Lấy dữ liệu từ form
                $name = $_POST['name'];
                $position = $_POST['position'];
                $username = $_POST['username'];
                $password = $_POST['password'];
                $confirmPassword = $_POST['confirmPassword'];
                $salary = str_replace('.', '', $_POST['salary']); // Bỏ dấu chấm
                $phone = $_POST['phone'];
                $isManager = isset($_POST['isManager']) ? 1 : 0;

                // Lưu dữ liệu tạm để đổ lại form nếu lỗi
                $_SESSION['remStaRegister'] = [
                    'name' => $name,
                    'position' => $position,
                    'username' => $username,
                    'salary' => $_POST['salary'], // Lưu bản gốc có dấu chấm
                    'phone' => $phone,
                    'isManager' => $isManager
                ];

                // Kiểm tra các trường bắt buộc
                if (empty($name) || empty($position) || empty($username) || empty($password) || empty($salary) || empty($phone)) {
                    $_SESSION['error'] = "Vui lòng điền đầy đủ thông tin!";
                    header('Location: /cnpm-final/HomeController/staffRegisterPage');
                    exit;
                }

                // Kiểm tra mật khẩu khớp
                if ($password !== $confirmPassword) {
                    $_SESSION['error'] = "Mật khẩu và xác nhận mật khẩu không khớp!";
                    header('Location: /cnpm-final/HomeController/staffRegisterPage');
                    exit;
                }

                // Kiểm tra username đã tồn tại
                $accountModel = $this->model('Account');
                if ($accountModel->getAccountInfoByUsername($username)) {
                    $_SESSION['error'] = "Tên đăng nhập đã tồn tại!";
                    header('Location: /cnpm-final/HomeController/staffRegisterPage');
                    exit;
                }

                // Xử lý avatar (nếu có)
                $avatar = null;
                if (!empty($_FILES['avatar']['name'])) {
                    $uploadDir = __DIR__ . '/../../public/images/avatar/';
                    $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
                    $filename = 'avatar_' . $username . '.' . $ext;
                    $uploadPath = $uploadDir . $filename;

                    foreach (glob($uploadDir . 'avatar_' . $username . '.*') as $oldFile) {
                        unlink($oldFile);
                    }

                    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadPath)) {
                        $avatar = $filename;
                    }
                }

                // Tạo tài khoản và thông tin nhân viên
                $role = $isManager ? 'manager' : 'staff';
                $accountModel->createAccount($username, $password, $role, $avatar);

                $staffModel = $this->model('Staff');
                $staffModel->createStaff($name, $position, $username, $isManager, $phone, $salary);

                // Xóa session tạm nếu thành công
                unset($_SESSION['remStaRegister']);

                $_SESSION['success'] = "Tạo tài khoản nhân viên thành công!";
                header('Location: /cnpm-final/StaffController/managerStaffManagePage');
                exit;
            }
        }

        public function changePassword() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $username = $_SESSION['username'];
                $oldPassword = $_POST['old_password'];
                $newPassword = $_POST['new_password'];
                $confirmPassword = $_POST['confirm_password'];
                $accountModel = $this->model('Account');

                if ($newPassword !== $confirmPassword) {
                    $_SESSION['message'] = [
                        'type' => 'danger',
                        'text' => 'Mật khẩu mới và xác nhận không khớp!'
                    ];
                } else {
                    $user = $accountModel->getAccountInfoByUsername($username);
                    if (!$user) {
                        $_SESSION['message'] = [
                            'type' => 'danger',
                            'text' => 'Không tìm thấy tài khoản!'
                        ];
                    } else {
                        if ($oldPassword === $user['password']) {
                            $accountModel->updatePassword($username, $newPassword);
                            $_SESSION['message'] = [
                                'type' => 'success',
                                'text' => 'Đổi mật khẩu thành công!'
                            ];
                        } else {
                            $_SESSION['message'] = [
                                'type' => 'danger',
                                'text' => 'Mật khẩu cũ không đúng!'
                            ];
                        }
                    }
                }

                // Sau khi xử lý xong, redirect để tránh submit lại form
                header("Location: /cnpm-final/HomeController/changePasswordPage");
                exit();
            }
        }


    }
?>