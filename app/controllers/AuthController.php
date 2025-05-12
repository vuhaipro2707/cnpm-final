<?php
    class AuthController extends Controller {


        public function login() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $username = $_POST['username'];
                $password = $_POST['password'];
                $role = $_POST['role'];

                $accountModel = $this->model('Account');
                $account = $accountModel->getAccountInfoByUsername($username);
                var_dump($account);

                //if ($user && password_verify($password, $user['password'])) {
                if ($account && $password == $account['password'] && $role == $account['role']) {
                    $_SESSION['username'] = $account['username'];
                    $_SESSION['role'] = $account['role'];
                    header('Location: /cnpm-final/HomeController/index');
                    exit;
                } else {
                    $error = "Sai tài khoản hoặc mật khẩu hoặc vai trò.";
                    $this->view('mainpage/login', ['error' => $error]);
                }
            } else {
                $this->view('mainpage/login');
            }
        }

        public function logout() {
            session_destroy();
            header('Location: /cnpm-final/auth/login');
            exit;
        }

        public function register() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $name = $_POST['name'];
                $phone = $_POST['phone'];
                $username = $_POST['username'];
                $password = $_POST['password'];
                $confirm = $_POST['confirm_password'];
                $role = $_POST['role'];

                $accountModel = $this->model('Account');

                if ($password !== $confirm) {
                    $this->view('mainpage/register', ['error' => 'Mật khẩu xác nhận không khớp.']);
                    return;
                }

                if ($accountModel->getAccountInfoByUsername($username)) {
                    $this->view('mainpage/register', ['error' => 'Tên đăng nhập đã tồn tại.']);
                    return;
                }

                // $hash = password_hash($password, PASSWORD_DEFAULT);
                $accountModel->createAccount($username, $password, $role, $name, $phone);

                $this->view('mainpage/login', ['success' => 'Đăng ký thành công! Bạn có thể đăng nhập.']);
            } else {
                $this->view('mainpage/register');
            }
        }

        
    }


?>