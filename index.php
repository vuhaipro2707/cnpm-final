<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Coffee Shop</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="/cnpm-final/public/css/style.css">
</head>
<body>
    <?php 
        // class auto require
        spl_autoload_register(function ($class) {
            $paths = ['core/', 'app/controllers/', 'app/models/', 'app/services/'];
        
            foreach ($paths as $path) {
                $file = $path . $class . '.php';
                if (file_exists($file)) {
                    require_once $file;
                    return;
                }
            }
        });
        require_once __DIR__ . '/app/views/layout/header.php';
        $app = new App();
    ?>
</body>

</html>