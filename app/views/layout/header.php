<?php $role = $_SESSION['role'] ?? 'guest'; ?>
<?php $username = $_SESSION['username'] ?? 'Guest'; ?>
<?php
$imgSrc = empty($_SESSION['avatar']) 
    ? '/cnpm-final/public/images/avatar/default.jpg' 
    : '/cnpm-final/public/images/avatar/' . $_SESSION['avatar'];
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light px-4">
  <a class="navbar-brand" href="/cnpm-final/HomeController/index">☕ Coffee Shop</a>

  <div class="ms-auto d-flex align-items-center gap-3">
    <span class="text-muted"><?= $username   ?></span>

    <?php if ($role === 'guest'): ?>
      <a href="/cnpm-final/HomeController/loginPage" class="btn btn-outline-primary">Đăng nhập</a>
    <?php else: ?>
      <!-- Avatar Dropdown -->
      <div class="dropdown">
        <button class="btn btn-light dropdown-toggle p-0 border-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
          <img src="<?=$imgSrc?>" alt="avatar" class="rounded-circle" style="width: 40px; height: 40px;">
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
          <li><a class="dropdown-item" href="/cnpm-final/ProfileController/index">Hồ sơ</a></li>
          <li><a class="dropdown-item" href="/cnpm-final/HomeController/changePasswordPage">Đổi mật khẩu</a></li>
          <li><a class="dropdown-item" href="/cnpm-final/AuthController/logout">Đăng xuất</a></li>
        </ul>
      </div>
    <?php endif; ?>
  </div>
</nav>
