<?php
    class HomeController extends Controller {
        public function index() {
            $itemModel = $this->model('Item');
            $randomItem = $itemModel->getRandomItem(15);
            $this->view('mainpage/Home', ['item'=>$randomItem]);
        }

        public function loginPage() {
            $this->view('mainpage/login');
        }

        public function registerPage() {
            $this->view('mainpage/register');
        }

        public function staffRegisterPage() {
            if ($_SESSION['role'] == 'manager') {
                $this->view('mainpage/staff_register');
            } else {
                $this->view('mainpage/Home');
            }
        }

        public function changePasswordPage() {
            $this->view('mainpage/change_password');
        }
    }
?>