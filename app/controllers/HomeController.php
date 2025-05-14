<?php
    class HomeController extends Controller {
        public function index() {
            $this->view('mainpage/Home');
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

        // public function profilePage() {
        //     $this->view('mainpage/profile');
        // }


    }
?>