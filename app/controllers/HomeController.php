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
    }
?>