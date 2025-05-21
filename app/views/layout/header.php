<?php
ob_start(); // Bắt đầu output buffering
?>


<?php
  $role = $_SESSION['role'] ?? 'guest';
?>
<style>
  /* Dropdown submenu style */
  .dropdown-submenu {
    position: relative;
  }

  .dropdown-submenu > .dropdown-menu {
    top: 0;
    left: 100%;
    margin-top: -0.1rem;
    display: none;
  }

  .dropdown-submenu:hover > .dropdown-menu {
    display: block;
  }
</style>

<div class="container-fluid mt-3 mb-3">
  <nav class="navbar navbar-expand-lg bg-light text-dark px-4 py-2 shadow rounded-3">
    <a class="navbar-brand" href="/cnpm-final/HomeController/index">☕ Coffee Shop</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php $role = $_SESSION['role'] ?? 'guest'; ?>

        <?php if ($role !== 'guest'): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle fw-bold" href="#" id="functionDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              &#9776;
            </a>

            <ul class="dropdown-menu" aria-labelledby="functionDropdown">

              <!-- Đơn hàng -->
              <li class="dropdown-submenu">
                <a class="dropdown-item dropdown-toggle" href="#">📦 Đơn hàng</a>
                <ul class="dropdown-menu">
                  <?php if ($role === 'staff' || $role === 'manager'): ?>
                    <li><a class="dropdown-item" href="/cnpm-final/OrderController/orderConfirmPage">Xác nhận đơn</a></li>
                  <?php endif; ?>
                  <?php if ($role === 'customer'): ?>
                    <li><a class="dropdown-item" href="/cnpm-final/OrderController/customerTrackOrderPage">Theo dõi đơn hàng</a></li>
                  <?php endif; ?>
                </ul>
              </li>

              <!-- Menu / Bàn -->
              <li class="dropdown-submenu">
                <a class="dropdown-item dropdown-toggle" href="#">🍽️ Menu / Bàn</a>
                <ul class="dropdown-menu">
                  <?php if ($role === 'customer'): ?>
                    <li><a class="dropdown-item" href="/cnpm-final/InventoryController/customerMenuPage">Xem menu</a></li>
                  <?php endif; ?>
                  <?php if ($role === 'staff' || $role === 'manager'): ?>
                    <li><a class="dropdown-item" href="/cnpm-final/TableController/manageTableLayout">Quản lý bàn</a></li>
                  <?php endif; ?>
                  <?php if ($role === 'manager'): ?>
                    <li><a class="dropdown-item" href="/cnpm-final/InventoryController/displayAllItem">Quản lý menu</a></li>
                  <?php endif; ?>
                </ul>
              </li>

              <!-- Khách hàng -->
              <?php if ($role === 'staff' || $role === 'manager'): ?>
                <li class="dropdown-submenu">
                  <a class="dropdown-item dropdown-toggle" href="#">👥 Khách hàng</a>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="/cnpm-final/CustomerController/staffCustomerManagePage">Quản lý khách hàng</a></li>
                  </ul>
                </li>
              <?php endif; ?>

              <!-- Nhân viên / KM -->
              <?php if ($role === 'manager'): ?>
                <li class="dropdown-submenu">
                  <a class="dropdown-item dropdown-toggle" href="#">🧑‍💼 Quản lý khác</a>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="/cnpm-final/StaffController/managerStaffManagePage">Nhân viên</a></li>
                    <li><a class="dropdown-item" href="/cnpm-final/PromotionController/managePromotionPage">Khuyến mãi</a></li>
                  </ul>
                </li>
              <?php endif; ?>

            </ul>
          </li>
        <?php endif; ?>
      </ul>

      <!-- Avatar và đăng nhập/đăng xuất -->
      <div class="d-flex align-items-center gap-3 ms-auto">
        <span class="text-muted"><?= htmlspecialchars($_SESSION['username'] ?? 'Guest') ?></span>

        <?php if ($role === 'guest'): ?>
          <a href="/cnpm-final/HomeController/loginPage" class="btn btn-outline-primary">Đăng nhập</a>
        <?php else: ?>
          <?php
            $avatar = $_SESSION['avatar'] ?? 'default.jpg';
            $imgSrc = "/cnpm-final/public/images/avatar/" . $avatar;
          ?>
          <div class="dropdown">
            <button class="btn dropdown-toggle p-0 border-0 bg-transparent" type="button" data-bs-toggle="dropdown">
              <img src="<?= $imgSrc ?>" class="rounded-circle" width="40" height="40" alt="avatar">
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="/cnpm-final/ProfileController/index">Hồ sơ</a></li>
              <li><a class="dropdown-item" href="/cnpm-final/HomeController/changePasswordPage">Đổi mật khẩu</a></li>
              <li><a class="dropdown-item" href="/cnpm-final/AuthController/logout">Đăng xuất</a></li>
            </ul>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </nav>
</div>

<?php
ob_get_contents()
?>