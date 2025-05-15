<div class="container py-5">
    <h2 class="mb-4 text-center">🍜 Quản lý thực đơn</h2>

    <?php if (!empty($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['message']['type'] ?>" role="alert">
            <?= htmlspecialchars($_SESSION['message']['text']) ?>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <div class="row">
        <!-- Danh sách món ăn -->
        <div class="col-md-7">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    📋 Danh sách món ăn hiện tại
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Ảnh</th>
                                <th>Tên</th>
                                <th>Giá</th>
                                <th>Loại</th>
                                <th>SL</th>
                                <th>Số lượng</th>
                                <th colspan="3" class="text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($data as $item): ?>
                            <tr>
                                <td><?= $item['itemId']; ?></td>
                                <td>
                                    <?php
                                    $defaultImage = 'https://picsum.photos/300/300';
                                    $imageLink = !empty($item['image']) ? ('/cnpm-final/public/images/productCard/' . $item['image']) : $defaultImage;
                                    ?>
                                    <img src="<?= $imageLink ?>" alt="ảnh" width="50" height="35" style="object-fit:cover; border-radius: 5px;">
                                </td>
                                <td>
                                    <strong><?= htmlspecialchars($item['name']) ?></strong><br>
                                    <small class="text-muted"><?= htmlspecialchars($item['note']) ?></small>
                                </td>
                                <td><?= number_format($item['price'], 0, ',', '.') ?>₫</td>
                                <td><span class="badge bg-info text-dark"><?= htmlspecialchars($item['type']) ?></span></td>
                                <td><?= $item['quantity']; ?></td>
                                <td style="width: 60px;">
                                    <input id="quantity-<?= $item['itemId'] ?>" type="number" min="1" max="<?= $item['quantity'] ?>" value="1" class="form-control form-control-sm" style="width:70px;">
                                </td>

                                <td>
                                    <form id="form-subtract-<?= $item['itemId'] ?>" method="POST" action="/cnpm-final/InventoryController/subtractItem" style="display:inline;">
                                        <input type="hidden" name="itemId" value="<?= $item['itemId']; ?>">
                                        <input type="hidden" name="quantity" id="subtract-quantity-<?= $item['itemId'] ?>" value="1">
                                        <button type="submit" class="btn btn-sm btn-outline-secondary" title="Trừ hàng">➖</button>
                                    </form>
                                </td>

                                <td>
                                    <form id="form-restock-<?= $item['itemId'] ?>" method="POST" action="/cnpm-final/InventoryController/restockItem" style="display:inline;">
                                        <input type="hidden" name="itemId" value="<?= $item['itemId']; ?>">
                                        <input type="hidden" name="quantity" id="restock-quantity-<?= $item['itemId'] ?>" value="1">
                                        <button type="submit" class="btn btn-sm btn-outline-success" title="Nhập thêm">➕</button>
                                    </form>
                                </td>
                                <script>
                                    (function(){
                                        const quantityInput = document.getElementById('quantity-<?= $item['itemId'] ?>');
                                        const subtractInput = document.getElementById('subtract-quantity-<?= $item['itemId'] ?>');
                                        const restockInput = document.getElementById('restock-quantity-<?= $item['itemId'] ?>');

                                        quantityInput.addEventListener('input', () => {
                                            let val = quantityInput.value;
                                            if(val === '' || val < 1) {
                                                val = 1;
                                                quantityInput.value = val;
                                            }

                                            subtractInput.value = val;
                                            restockInput.value = val;
                                        });
                                    })();
                                </script>
                                <td>
                                    <!-- Form xoá mặt hàng -->
                                    <form method="POST" action="/cnpm-final/InventoryController/deleteItem" onsubmit="return confirm('Bạn có chắc muốn xoá mặt hàng này?')">
                                        <input type="hidden" name="itemId" value="<?= $item['itemId']; ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">🗑</button>
                                    </form>
                                </td>
                            </tr>

                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <!-- Thêm mới + Nhập hàng -->
        <div class="col-md-5">
            <!-- Form thêm món mới -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    ➕ Thêm món mới vào menu
                </div>
                <div class="card-body">
                    <form method="POST" action="/cnpm-final/InventoryController/createItem" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên món</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Giá (VND)</label>
                            <input type="number" class="form-control" id="price" name="price" min="0" required>
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Số lượng ban đầu</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">Loại món (ví dụ: nước, ăn vặt, chính...)</label>
                            <input type="text" class="form-control" id="type" name="type" required>
                        </div>
                        <div class="mb-3">
                            <label for="note" class="form-label">Mô tả món</label>
                            <textarea class="form-control" id="note" name="note" rows="2" placeholder="Thêm ghi chú..." requ></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Ảnh món ăn <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Thêm món mới</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


