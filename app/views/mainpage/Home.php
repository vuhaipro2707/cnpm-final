<?php
$role = $_SESSION['role'] ?? 'guest';
?>

<?php if($role == 'guest' || $role == 'customer') {?>
<!-- Carousel -->
<div class="container-fluid px-5">
<div id="mainCarousel" class="carousel slide mb-4 mt-3" data-bs-ride="carousel">
  <div class="carousel-inner rounded-4 shadow">
    <div class="carousel-item active">
      <img src="/cnpm-final/public/images/carousel/banner1.jpg" class="d-block w-100" alt="Cửa hàng 1">
    </div>
    <div class="carousel-item">
      <img src="/cnpm-final/public/images/carousel/banner2.jpg" class="d-block w-100" alt="Cửa hàng 2">
    </div>
    <div class="carousel-item">
      <img src="/cnpm-final/public/images/carousel/banner3.webp" class="d-block w-100" alt="Cửa hàng 3">
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>
</div>
</div>

<?php
if ($role == 'guest') {
    $link = '/cnpm-final/HomeController/loginPage';
} else if ($role == 'customer') {
    $link = '/cnpm-final/InventoryController/customerMenuPage';
}
?>
<!-- Nút đặt món -->
<div class="text-center mb-5">
  <a href="<?= $link ?>" class="btn btn-primary btn-lg px-5 py-2 shadow">Đặt món ngay</a>
</div>


<div class="container mb-5">
    <h3 class="mb-4">Món nổi bật</h3>
    <div id="coffeeCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">

        <?php
        $items = $data['item'];
        $chunks = array_chunk($items, 5); // chia mỗi nhóm 5 món
        foreach ($chunks as $index => $chunk):
        ?>
        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
            <div class="d-flex justify-content-center gap-4">
            <?php foreach ($chunk as $item):
                $defaultImage = 'https://picsum.photos/300/300';
                $imageLink = !empty($item['image']) ? ('/cnpm-final/public/images/productCard/' . $item['image']) : $defaultImage;
            ?>
                <div class="card" style="width: 220px; height: 350px;">
                <img src="<?= $imageLink ?>" class="card-img-top" alt="<?= htmlspecialchars($item['name']) ?>" style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($item['name']) ?></h5>
                    <p class="card-text text-muted"><?= htmlspecialchars($item['note']) ?></p>
                    <p class="fw-bold"><?= number_format($item['price']) ?>đ</p>
                </div>
                </div>
            <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>

    </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#coffeeCarousel" data-bs-slide="prev" 
        style="width: 44px; height: 44px; border-radius: 50%; background: transparent; border: 2px solid black; top: 50%; transform: translateY(-50%);">
            <span class="carousel-control-prev-icon" aria-hidden="true" style="filter: invert(1) brightness(0) saturate(100%)"></span>
            <span class="visually-hidden">Previous</span>
        </button>

        <button class="carousel-control-next" type="button" data-bs-target="#coffeeCarousel" data-bs-slide="next" 
        style="width: 44px; height: 44px; border-radius: 50%; background: transparent; border: 2px solid black; top: 50%; transform: translateY(-50%);">
            <span class="carousel-control-next-icon" aria-hidden="true" style="filter: invert(1) brightness(0) saturate(100%)"></span>
            <span class="visually-hidden">Next</span>
        </button>

    </div>
</div>
<?php } elseif ($role == 'staff' || $role == 'manager') {?>


<div class="container mt-4">
  <div class="row g-4">
    <!-- Card lớn 1: Đơn hàng -->
    <div class="col-md-6">
      <div class="card h-100">
        <div class="card-header fw-bold text-primary">📦 Đơn hàng</div>
        <div class="card-body">
          <div class="row g-3">
            <?php if ($role === 'staff' || $role === 'manager'): ?>
              <div class="col-6">
                <div class="card h-100 text-center">
                  <a href="/cnpm-final/OrderController/orderConfirmPage" class="text-decoration-none text-dark">
                    <img src="/cnpm-final/public/images/icons/confirm_order_button.webp" class="card-img-top mx-auto" style="width: auto; height: 100px" alt="Xác nhận đơn">
                    <div class="card-body p-2">
                      <p class="card-text">Xác nhận đơn</p>
                    </div>
                  </a>
                </div>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Card lớn 2: Menu / Bàn -->
    <div class="col-md-6">
      <div class="card h-100">
        <div class="card-header fw-bold text-success">🍽️ Menu / Bàn</div>
        <div class="card-body">
          <div class="row g-3">
            <?php if ($role === 'staff' || $role === 'manager'): ?>
              <div class="col-6">
                <div class="card h-100 text-center">
                  <a href="/cnpm-final/TableController/manageTableLayout" class="text-decoration-none text-dark">
                    <img src="/cnpm-final/public/images/icons/table.jpg" class="card-img-top mx-auto" style="width: auto; height: 100px" alt="Quản lý bàn">
                    <div class="card-body p-2">
                      <p class="card-text">Quản lý bàn</p>
                    </div>
                  </a>
                </div>
              </div>
            <?php endif; ?>
            <?php if ($role === 'manager'): ?>
              <div class="col-6">
                <div class="card h-100 text-center">
                  <a href="/cnpm-final/InventoryController/displayAllItem" class="text-decoration-none text-dark">
                    <img src="/cnpm-final/public/images/icons/manage_menu.jpg" class="card-img-top mx-auto" style="width: auto; height: 100px" alt="Quản lý menu">
                    <div class="card-body p-2">
                      <p class="card-text">Quản lý menu</p>
                    </div>
                  </a>
                </div>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Card lớn 3: Khách hàng -->
    <?php if ($role === 'staff' || $role === 'manager'): ?>
      <div class="col-md-6">
        <div class="card h-100">
          <div class="card-header fw-bold text-warning">👥 Khách hàng</div>
          <div class="card-body">
            <div class="row g-3">
              <div class="col-6">
                <div class="card h-100 text-center">
                  <a href="/cnpm-final/CustomerController/staffCustomerManagePage" class="text-decoration-none text-dark">
                    <img src="/cnpm-final/public/images/icons/customer_manage.jpg" class="card-img-top mx-auto" style="width: auto; height: 100px" alt="Quản lý khách hàng">
                    <div class="card-body p-2">
                      <p class="card-text">Quản lý khách hàng</p>
                    </div>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <!-- Card lớn 4: Quản lý khác -->
    <?php if ($role === 'manager'): ?>
      <div class="col-md-6">
        <div class="card h-100">
          <div class="card-header fw-bold text-danger">🧑‍💼 Quản lý khác</div>
          <div class="card-body">
            <div class="row g-3">
              <div class="col-6">
                <div class="card h-100 text-center">
                  <a href="/cnpm-final/StaffController/managerStaffManagePage" class="text-decoration-none text-dark">
                    <img src="/cnpm-final/public/images/icons/staff_manage.avif" class="card-img-top mx-auto" style="width: auto; height: 100px" alt="Nhân viên">
                    <div class="card-body p-2">
                      <p class="card-text">Quản lý nhân viên</p>
                    </div>
                  </a>
                </div>
              </div>
              <div class="col-6">
                <div class="card h-100 text-center">
                  <a href="/cnpm-final/PromotionController/managePromotionPage" class="text-decoration-none text-dark">
                    <img src="/cnpm-final/public/images/icons/promotion_manage.jpg" class="card-img-top mx-auto" style="width: auto; height: 100px" alt="Khuyến mãi">
                    <div class="card-body p-2">
                      <p class="card-text">Quản lý khuyến mãi</p>
                    </div>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php } ?>



<script>
  const container = document.getElementById('cardContainer');
  const scrollAmount = 220 + 16; // width of one card + gap

  document.getElementById('scrollLeft').addEventListener('click', () => {
    container.scrollBy({ left: -scrollAmount * 5, behavior: 'smooth' });
  });

  document.getElementById('scrollRight').addEventListener('click', () => {
    container.scrollBy({ left: scrollAmount * 5, behavior: 'smooth' });
  });
</script>
