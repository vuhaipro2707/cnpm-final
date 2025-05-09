<?php
class Controller {
    // Gọi model
    public function model($modelName) {
        require_once __DIR__ . '/../app/models/' . $modelName . '.php';
        return new $modelName;
    }

    // Gọi view
    public function view($viewPath, $data = []) {
        require_once __DIR__ . '/../app/views/' . $viewPath . '.php';
    }
}
