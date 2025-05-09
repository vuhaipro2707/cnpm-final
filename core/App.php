<?php

    class App {
        protected $controller = 'ExceptionController';  // Controller mặc định
        protected $method = 'index';              // Method mặc định
        protected $params = [];                   // Params mặc định

        public function __construct() {
            $url = $this->parseUrl();  // Phân tích URL
            // print_r($url);

            // Controller
            $controllerPath = __DIR__ . '/../app/controllers/' . ucfirst($url[0]) . '.php';
            if (file_exists($controllerPath)) {
                $this->controller = ucfirst($url[0]); // Set controller nếu tồn tại
                unset($url[0]);
            }

            // Include controller
            require_once __DIR__ . '/../app/controllers/' . $this->controller . '.php';
            $this->controller = new $this->controller;

            // Method
            if (isset($url[1]) && method_exists($this->controller, $url[1])) {
                $this->method = $url[1];  // Set method nếu tồn tại
                unset($url[1]);
            }

            // Params
            $this->params = $url ? array_values($url) : []; // Params từ URL

            // Kiểm tra phương thức HTTP
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && method_exists($this->controller, 'handlePost')) {
                // Nếu phương thức HTTP là POST và có phương thức handlePost trong controller
                call_user_func_array([$this->controller, 'handlePost'], $this->params);
            } else {
                // Nếu không phải POST, gọi phương thức thông thường
                call_user_func_array([$this->controller, $this->method], $this->params);
            }
        }

        private function parseUrl() {
            if (isset($_GET['url'])) {
                return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
            }
            return ['home'];  // controller mặc định
        }
    }

?>
