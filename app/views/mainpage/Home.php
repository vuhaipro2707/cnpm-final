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
<?php
$order = $data['ordersByStatus'];
$data['totalOrders'] = 
    ($order['pending'] ?? 0) + 
    ($order['success'] ?? 0) + 
    ($order['failed'] ?? 0) + 
    ($order['paid'] ?? 0);
?>

  <div class="container mt-5">
    <h2 class="mb-4">Thống kê tổng quan</h2>

    <div class="row g-4">
        <!-- Best Selling Item -->
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body">
                    <h5 class="card-title">📊 Top 3 món bán chạy</h5>
                    <canvas id="topItemsChart" height="200"></canvas>
                </div>
            </div>
        </div>

        

        <!-- Tổng số đơn hàng -->
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm border-0 rounded-4 h-100 bg-light-subtle">
                <div class="card-body">
                    <h5 class="card-title">🧾 Tổng số đơn hàng</h5>
                    <p class="card-text fs-4 fw-bold"><?= $data['totalOrders'] ?? 0 ?> đơn</p>

                    <ul class="list-group list-group-flush mt-3">
                        <li class="list-group-item">
                            <strong>Pending:</strong> <?= $data['ordersByStatus']['pending'] ?? 0 ?> đơn 
                            <small class="text-muted">(Đang đợi món)</small>
                        </li>
                        <li class="list-group-item">
                            <strong>Success:</strong> <?= $data['ordersByStatus']['success'] ?? 0 ?> đơn 
                            <small class="text-muted">(Đã hoàn thành món, chưa tính tiền)</small>
                        </li>
                        <li class="list-group-item">
                            <strong>Fail:</strong> <?= $data['ordersByStatus']['failed'] ?? 0 ?> đơn 
                            <small class="text-muted">(Bị từ chối)</small>
                        </li>
                        <li class="list-group-item">
                            <strong>Paid:</strong> <?= $data['ordersByStatus']['paid'] ?? 0 ?> đơn 
                            <small class="text-muted">(Đã thanh toán)</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
        </div>

        

        <!-- Staff Performance -->
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body">
                    <h5 class="card-title">👨‍💼 Nhân viên xuất sắc</h5>
                    <p class="card-text fs-5 fw-bold"><?php echo isset($data['bestStaff']['totalOrders']) && $data['bestStaff']['totalOrders'] > 0
                                                          ? $data['bestStaff']['name']
                                                          : 'Không có dữ liệu';?></p>
                    <p class="text-muted mb-0">Số đơn xử lý: <?= $data['bestStaff']['totalOrders'] ?? 0 ?></p>
                </div>
            </div>
        </div>

        <!-- Today’s Revenue -->
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm border-0 rounded-4 h-100 bg-light">
                <div class="card-body">
                    <h5 class="card-title">💰 Doanh thu hôm nay</h5>
                    <p class="card-text fs-4 fw-bold text-success"><?= number_format($data['totalRevenueToday']['total_payment_today'] ?? 0, 0, ',', '.') ?> VND</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
        </div>

    </div>
</div>

<div class="container mt-4 mb-4">
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

<?php } else if ($role == 'admin') {?>

<?php

$data['feedback'] = [
  ['errorId' => 101, 'errorName' => 'Lỗi đăng nhập', 'errorPlace' => 'Trang Login', 'note' => 'Không thể đăng nhập bằng Google', 'date' => '2025-05-20'],
  ['errorId' => 102, 'errorName' => 'Lỗi hiển thị', 'errorPlace' => 'Trang Dashboard', 'note' => 'Giao diện bị lệch trên Chrome', 'date' => '2025-05-19'],
  ['errorId' => 103, 'errorName' => 'Lỗi tải dữ liệu', 'errorPlace' => 'API /users', 'note' => 'Timeout khi gọi API', 'date' => '2025-05-18'],
];
?>

<div class="container my-5">
  <div class="row gy-4">
    
    <!-- Cột trái: chỉnh role -->
    <div class="col-md-6">
      <h2 class="mb-4 text-center">Chỉnh sửa Role cho tài khoản</h2>
      <?php 
    if (!empty($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['message']['type'] ?>" role="alert">
            <?= htmlspecialchars($_SESSION['message']['text']) ?>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

      <form method="post" action="/cnpm-final/AuthController/processRoles" class="p-4 bg-white rounded shadow-sm">
        <table class="table table-bordered rounded">
          <thead class="table-light">
            <tr>
              <th>Username</th>
              <th>Role</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($data['accounts'] as $index => $account): ?>
              <tr>
                <td>
                  <input type="hidden" name="accounts[<?= $index ?>][username]" value="<?= htmlspecialchars($account['username']) ?>" />
                  <?= htmlspecialchars($account['username']) ?>
                </td>
                <td>
                  <select class="form-select rounded" name="accounts[<?= $index ?>][role]" required>
                    <option value="manager" <?= $account['role'] === 'manager' ? 'selected' : '' ?>>Manager</option>
                    <option value="staff" <?= $account['role'] === 'staff' ? 'selected' : '' ?>>Staff</option>
                  </select>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

        <button type="submit" class="btn btn-primary w-100">Lưu thay đổi</button>
      </form>
    </div>

    <!-- Cột phải: danh sách feedback lỗi -->
    <div class="col-md-6">
      <h2 class="mb-4 text-center">Danh sách Feedback lỗi</h2>
      
      <table class="table table-bordered table-hover rounded shadow-sm bg-white">
        <thead class="table-light">
          <tr>
            <th>Error ID</th>
            <th>Error Name</th>
            <th>Location</th>
            <th>Note</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($data['feedback'])): ?>
            <?php foreach ($data['feedback'] as $fb): ?>
              <tr>
                <td><?= htmlspecialchars($fb['errorId']) ?></td>
                <td><?= htmlspecialchars($fb['errorName']) ?></td>
                <td><?= htmlspecialchars($fb['errorPlace']) ?></td>
                <td><?= htmlspecialchars($fb['note']) ?></td>
                <td><?= htmlspecialchars($fb['date']) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="5" class="text-center">Không có feedback lỗi nào</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php } ?>



<script>
  const itemNames = <?= json_encode(array_column($data['bestItemSale'], 'name')) ?>;
  const quantities = <?= json_encode(array_column($data['bestItemSale'], 'total_quantity')) ?>;
  console.log('haha');
  const ctx = document.getElementById('topItemsChart').getContext('2d');
  new Chart(ctx, {
      type: 'bar',
      data: {
          labels: itemNames,
          datasets: [{
              label: 'Số lượng đã bán',
              data: quantities,
              backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
              borderRadius: 10,
          }]
      },
      options: {
          responsive: true,
          scales: {
              y: {
                  beginAtZero: true,
                  ticks: {
                      precision: 0
                  }
              }
          },
          plugins: {
              legend: { display: false }
          }
      }
  });
</script>
