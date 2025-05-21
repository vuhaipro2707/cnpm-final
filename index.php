<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Coffee Shop</title>
    <link rel="stylesheet" href="/public/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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

<style>
  body {
    /* background-color:rgb(227,208,193); Màu nền (có thể đổi thành màu bạn muốn) */
    /* Hoặc nếu muốn dùng hình nền thì dùng: */
    background-image: url('/cnpm-final/public/images/other/background.png   ');
    background-size: auto;
    background-repeat: repeat;
    background-position: 0 0;
  }
    /* Đổi màu nền và viền của btn-primary thành màu nâu */
.btn-primary {
    background-color: #6f4f28;  /* Màu nâu */
    border-color: #6f4f28;      /* Màu viền nâu */
    color: #fff;                /* Màu chữ trắng */
}

/* Đổi màu chữ của các phần tử có class text-primary thành màu nâu */
.text-primary {
    color: #6f4f28 !important;  /* Màu chữ nâu */
}

/* Đổi màu nền của các phần tử có class bg-primary thành màu nâu */
.bg-primary {
    background-color: #6f4f28 !important;  /* Màu nền nâu */
    color: #fff !important;  /* Màu chữ trắng cho text trên nền nâu */
}

/* Đổi màu viền của các phần tử có class border-primary thành màu nâu */
.border-primary {
    border-color: #6f4f28 !important;  /* Màu viền nâu */
}

/* Đổi màu cho các phần tử có class text-decoration-primary (ví dụ trong link) */
.text-decoration-primary {
    text-decoration-color: #6f4f28 !important;  /* Màu gạch chân nâu */
}

/* Đổi màu cho các phần tử có class shadow-primary (ví dụ trong shadow) */
.shadow-primary {
    box-shadow: 0 4px 6px rgba(111, 79, 40, 0.2) !important;  /* Đổi màu shadow */
}

/* Đổi màu cho các phần tử có class list-group-item-primary */
.list-group-item-primary {
    background-color: #6f4f28 !important;  /* Màu nền nâu */
    color: #fff !important;  /* Màu chữ trắng */
}

/* Thêm hiệu ứng hover cho các nút btn-primary */
.btn-primary:hover {
    background-color: #5c3e1a;  /* Màu nâu đậm khi hover */
    border-color: #5c3e1a;      /* Màu viền nâu đậm khi hover */
}

/* Thêm hiệu ứng hover cho các phần tử có class text-primary */
.text-primary:hover {
    color: #5c3e1a !important;  /* Màu chữ nâu đậm khi hover */
}

/* Thêm hiệu ứng hover cho các phần tử có class bg-primary */
.bg-primary:hover {
    background-color: #5c3e1a !important;  /* Màu nền nâu đậm khi hover */
    color: #fff !important;  /* Màu chữ trắng khi hover */
}

/* Thêm hiệu ứng hover cho các phần tử có class border-primary */
.border-primary:hover {
    border-color: #5c3e1a !important;  /* Màu viền nâu đậm khi hover */
}

/* Đổi màu khi btn-primary được focus */
.btn-primary:focus {
    box-shadow: 0 0 0 0.2rem rgba(111, 79, 40, 0.5);  /* Viền nhạt màu nâu khi focus */
}
/* Đổi màu viền và màu chữ của btn-outline-primary thành màu nâu */
.btn-outline-primary {
    color: #6f4f28; /* Màu chữ nâu */
    border-color: #6f4f28; /* Màu viền nâu */
}

/* Khi hover vào nút */
.btn-outline-primary:hover {
    background-color: #6f4f28; /* Màu nền nâu khi hover */
    color: #fff; /* Màu chữ trắng khi hover */
    border-color: #6f4f28; /* Màu viền nâu khi hover */
}

/* Ẩn nút radio mặc định */
.form-check-input {
    appearance: none; /* Ẩn nút radio mặc định */
    border: 2px solid #8B4513;
}

/* Khi radio được chọn */
.form-check-input:checked {
    background-color: #8B4513; /* Màu nền nâu khi được chọn */
    border-color: #8B4513;
}
/* Hiệu ứng hover khi di chuột qua nút radio */
.form-check-input:hover {
    border-color: #6a3b2f; /* Viền khi hover (màu nâu tối hơn) */
}

</style>

</html>