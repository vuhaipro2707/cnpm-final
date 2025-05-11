<?php $role = $_SESSION['role'] ?? 'guest';?>

<?php 
  $actionUrl = ($role == 'guest') ? '/cnpm-final/HomeController/loginPage' : '/cnpm-final/AuthController/logout';
  $buttonText = ($role == 'guest') ? 'Đăng nhập' : 'Đăng xuất';
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light px-4">
  <a class="navbar-brand" href="/cnpm-final/HomeController/index">☕ Coffee Shop</a>
  <div class="ms-auto d-flex align-items-center gap-2">
    <span><?=$role?></span>
    <a href="<?=$actionUrl?>" class="btn btn-outline-primary"><?=$buttonText?></a>
  </div>
</nav>
