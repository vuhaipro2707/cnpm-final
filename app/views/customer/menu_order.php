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

?>

<div class="container-fluid px-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2">
            <div class="position-sticky pt-4" style="top: 2rem;">
                <div class="text-center mb-4">
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
                <ul class="list-group">
                    <?php foreach ($data['itemsByType'] as $type => $items): ?>
                        <li class="list-group-item">
                            <a href="#<?php echo urlencode($type); ?>"><?php echo $type; ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
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
                                    <form method="POST" action="/cnpm-final/CartController/addToCart" class="mt-auto">
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

<!-- Modal -->
<div class="modal fade" id="tableModal" tabindex="-1" aria-labelledby="tableModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tableModalLabel">Chọn bàn</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>
      <div class="modal-body">
        <div class="container">
          <div class="row row-cols-4 g-3">
            <?php for ($i = 1; $i <= 12; $i++): ?>
              <div class="col">
                <form method="POST" action="/cnpm-final/CartController/setTable">
                  <input type="hidden" name="tableNumber" value="T<?php echo $i; ?>">
                  <button type="submit" class="btn btn-outline-secondary w-100">T<?php echo $i; ?></button>
                </form>
              </div>
            <?php endfor; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>



<!-- Floating cart button -->
<!-- <a href="/cnpm-final/OrderController/cart" class="btn btn-warning rounded-circle position-fixed" style="bottom: 20px; right: 20px; width: 60px; height: 60px;">
    🛒
</a> -->

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

</script>
