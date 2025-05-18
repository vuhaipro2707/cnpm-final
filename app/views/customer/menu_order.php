<?php
if (isset($_SESSION['readyOrder']['cart'])) {
    $cartItems = $_SESSION['readyOrder']['cart'];
} else {
    $cartItems = [];
}

$totalPrice = 0;
foreach ($cartItems as $item) {
    $totalPrice += $item['itemPrice'] * $item['quantity'];
}

$tablesByPos = [];
foreach ($data['table'] as $table) {
    $tablesByPos[$table['layoutPosition']] = $table;
}

?>

<div class="container-fluid px-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2">
            <div class="position-sticky pt-4" style="top: 2rem;">
                <div class="text-center mb-4">
                    <a href="/cnpm-final/OrderController/customerTrackOrderPage" class="btn btn-outline-dark w-100 mb-2">
                        🧾 Xem tất cả order
                    </a>
                    <button type="button" class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#tableModal">
                        Chọn bàn
                    </button>
                    <?php if (isset($_SESSION['readyOrder']['tableNumber'])): ?>
                        <div class="mt-2 small text-success">
                            Bàn: <?php echo $_SESSION['readyOrder']['tableNumber']; ?>
                        </div> 
                    <?php endif; ?>
                </div>

                <!-- Danh sách loại món -->
                <h5 class="mb-3">Loại món</h5>
                <div class="list-group">
                    <?php foreach ($data['itemsByType'] as $type => $items): ?>
                        <a href="#<?php echo urlencode($type); ?>"
                        class="list-group-item list-group-item-action">
                            <?php echo htmlspecialchars($type); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>


        <!-- Main content -->
        <div class="col-md-10">
            <?php if (!empty($data['error'])): ?>
                <div class="alert alert-danger"><?php echo $data['error']; ?></div>
            <?php endif; ?>

            <?php if (!empty($data['success'])): ?>
                <div class="alert alert-success"><?php echo $data['success']; ?></div>
            <?php endif; ?>

            <?php foreach ($data['itemsByType'] as $type => $items): ?>
                <h3 id="<?php echo urlencode($type); ?>" class="mt-5"><?php echo $type; ?></h3>
                <div class="row g-5">
                    <?php foreach ($items as $index => $item): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <?php
                                $defaultImage = 'https://picsum.photos/300/300';
                                $imageLink = !empty($item['image']) ? ('/cnpm-final/public/images/productCard/' . $item['image']) : $defaultImage;
                                ?>
                                <img src="<?= $imageLink ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?php echo $item['name']; ?></h5>
                                    <p class="card-text"><?php echo $item['note']; ?></p>
                                    <form method="POST" action="/cnpm-final/CartController/addToCart" class="mt-auto add-to-cart-form">
                                        <input type="hidden" name="itemId" value="<?php echo $item['itemId']; ?>">
                                        <input type="hidden" name="name" value="<?php echo $item['name']; ?>">
                                        <input type="hidden" name="price" value="<?php echo $item['price']; ?>">
                                        <input type="number" name="quantity" value="1" min="1" class="form-control mb-2">
                                        <button type="submit" class="btn btn-primary btn-block">Thêm vào giỏ</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Modal chọn bàn -->
<div class="modal fade" id="tableModal" tabindex="-1" aria-labelledby="tableModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tableModalLabel">Chọn bàn</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <!-- Grid bàn (trái) -->
          <div class="col-lg-9">
            <div class="container-fluid">
              <div class="row row-cols-6 g-2">
                <?php for ($row = 0; $row < 6; $row++): ?>
                  <?php for ($col = 0; $col < 6; $col++):
                    $pos = "{$row}_{$col}";
                    if (!isset($tablesByPos[$pos])) {
                      echo '<div class="col d-grid">
                              <button class="btn btn-outline-secondary w-100 py-4" disabled>&nbsp;</button>
                            </div>';
                      continue;
                    }

                    $table = $tablesByPos[$pos];
                    $status = $table['status'];
                    $tableNumber = htmlspecialchars($table['tableNumber']);

                    if ($status == 'inactive') {
                        echo '<div class="col d-grid">
                              <button class="btn btn-outline-secondary w-100 py-4" disabled>&nbsp;</button>
                            </div>';
                      continue;
                    }
                    if ($status === 'serving') {
                      echo '<div class="col d-grid">
                              <form method="POST" action="/cnpm-final/CartController/setTable" class="d-grid" onsubmit="return confirmServingTable(\'' . $tableNumber . '\')">
                                <input type="hidden" name="tableNumber" value="' . $tableNumber . '">
                                <button type="submit" class="btn btn-danger w-100 py-4">' . $tableNumber . '</button>
                              </form>
                            </div>';
                    } elseif ($status === 'empty') {
                      echo '<div class="col d-grid">
                              <form method="POST" action="/cnpm-final/CartController/setTable" class="d-grid">
                                <input type="hidden" name="tableNumber" value="' . $tableNumber . '">
                                <button type="submit" class="btn btn-info w-100 py-4">' . $tableNumber . '</button>
                              </form>
                            </div>';
                    }
                  endfor; ?>
                <?php endfor; ?>
              </div>
            </div>
          </div>

          <!-- Chú thích (phải) -->
          <div class="col-lg-3">
            <p><strong>Chú thích:</strong></p>
            <div class="mb-2 d-flex align-items-center">
              <button class="btn btn-danger btn-sm me-2" style="width: 2rem; height: 2rem;"></button>
              <span>Bàn đang phục vụ</span>
            </div>
            <div class="mb-2 d-flex align-items-center">
              <button class="btn btn-info btn-sm me-2" disabled style="width: 2rem; height: 2rem;"></button>
              <span>Bàn trống</span>
            </div>
            <div class="mb-2 d-flex align-items-center">
              <button class="btn btn-outline-secondary btn-sm me-2" disabled style="width: 2rem; height: 2rem;"></button>
              <span>Không có bàn</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>



<!-- Nút giỏ hàng nổi -->
<a href="#" class="btn btn-warning rounded-circle position-fixed" style="bottom: 20px; right: 20px; width: 60px; height: 60px;" data-bs-toggle="modal" data-bs-target="#cartModal">
    🛒
</a>

<!-- Modal Giỏ Hàng -->
<div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cartModalLabel">Giỏ Hàng</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul id="cartItemsList" class="list-group">
          <?php foreach ($cartItems as $index => $item): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <div>
                <strong><?= $item['itemName'] ?></strong><br>
                <small><?= number_format($item['itemPrice'], 0, ',', '.') ?> VNĐ</small>
              </div>
              <div class="input-group input-group-sm" style="width: 120px;">
                <input type="number" class="form-control quantity-input" data-index="<?= $index ?>" value="<?= $item['quantity'] ?>" min="1">
                <button class="btn btn-danger btn-sm delete-item" data-index="<?= $index ?>">&times;</button>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
        <hr>
        <p><strong>Tổng tiền:</strong> <span id="cartTotal"><?= number_format($totalPrice, 0, ',', '.') ?> VNĐ</span></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
        <form method="POST" action="/cnpm-final/OrderController/createOrder">
            <button type="submit" class="btn btn-primary">Xác nhận</button>
        </form>
      </div>
    </div>
  </div>
</div>





<!-- Nút Kéo lên đầu trang (góc trái dưới, tròn, xám nhẹ) -->
<button onclick="scrollToTop()" id="scrollTopBtn" class="position-fixed" 
    style="
        bottom: 20px;
        left: 20px;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background-color: #e0e0e0;
        color: #333;
        border: none;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        display: none;
        z-index: 999;
    ">
    ⬆️
</button>



<script>
    function updateCartUI(cartItems, totalPrice) {
        const cartList = document.getElementById('cartItemsList');
        cartList.innerHTML = '';  // Clear current cart items list

        cartItems.forEach((item, index) => {
            const li = document.createElement('li');
            li.className = 'list-group-item d-flex justify-content-between align-items-center';
            li.innerHTML = `
                <div>
                    <strong>${item.itemName}</strong><br>
                    <small>${item.itemPrice.toLocaleString()} VNĐ</small>
                </div>
                <div class="input-group input-group-sm" style="width: 120px;">
                    <input type="number" class="form-control quantity-input" data-index="${index}" value="${item.quantity}" min="1">
                    <button class="btn btn-danger btn-sm delete-item" data-index="${index}">&times;</button>
                </div>
            `;
            cartList.appendChild(li);
        });
        // Cập nhật tổng tiền trong phần cart summary
        document.getElementById('cartTotal').innerText = totalPrice.toLocaleString() + ' VNĐ';

        // Rebind event listeners for delete and update actions
        attachHandlers();  // Re-bind event handlers for delete and quantity change
    }

    function attachHandlers() {
        // Xử lý nút xóa món
        document.querySelectorAll('.delete-item').forEach(button => {
            button.onclick = () => {
                const index = button.getAttribute('data-index');
                fetch('/cnpm-final/CartController/removeItem', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'  // Đảm bảo server trả về JSON
                    },
                    body: JSON.stringify({ index }) // Chuyển index món cần xóa
                })
                .then(res => res.json())  // Parse kết quả trả về từ server
                .then(data => {
                    // Cập nhật lại giỏ hàng UI với dữ liệu mới
                    updateCartUI(data.cartItems, data.totalPrice);
                })
            };
        });

        // Xử lý thay đổi số lượng món trong giỏ hàng
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.onchange = () => {
                const index = input.getAttribute('data-index');
                const quantity = input.value;
                fetch('/cnpm-final/CartController/updateQuantity', {  // Đường dẫn thay đổi số lượng
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'  // Đảm bảo server trả về JSON
                    },
                    body: JSON.stringify({ index, quantity })  // Chuyển thông tin cần thay đổi
                })
                .then(res => res.json())  // Parse kết quả trả về từ server
                .then(data => {
                    // Cập nhật lại giỏ hàng UI với dữ liệu mới
                    updateCartUI(data.cartItems, data.totalPrice);
                })
            };
        });
    }


    attachHandlers();

    // Khôi phục scroll sớm, tránh nhảy giật sau load
    const scrollPos = sessionStorage.getItem("scrollPosition");
    if (scrollPos !== null) {
      window.scrollTo(0, parseInt(scrollPos)); // cuộn lại ngay lập tức
      sessionStorage.removeItem("scrollPosition");
    }

    // Lưu lại scroll trước khi submit form
    window.addEventListener("DOMContentLoaded", () => {
      document.querySelectorAll("form.add-to-cart-form").forEach(form => {
        form.addEventListener("submit", () => {
          sessionStorage.setItem("scrollPosition", window.scrollY);
        });
      });
    });

     // Hiện nút khi kéo xuống 200px
    window.onscroll = function () {
        const scrollBtn = document.getElementById("scrollTopBtn");
        if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 100) {
            scrollBtn.style.display = "block";
        } else {
            scrollBtn.style.display = "none";
        }
    };

    // Kéo lên đầu trang
    function scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    }

  function confirmServingTable(tableNumber) {
    return confirm("Bàn số " + tableNumber + " đang được phục vụ. Bạn có chắc muốn chọn bàn này không?");
  }
</script>
