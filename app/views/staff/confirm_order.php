<div class="container py-4">
    
    <h2 class="mb-4 text-center">Đơn hàng đang chờ</h2>

    <?php if (!empty($data['error'])): ?>
        <div class="alert alert-danger"><?php echo $data['error']; ?></div>
    <?php endif; ?>

    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php foreach ($data['orders'] as $order): ?>
            <?php
                // Xác định class màu sắc theo trạng thái đơn hàng
                switch ($order['status']) {
                    case 'success':
                        $cardClass = 'border-success';
                        $headerClass = 'bg-success text-white';
                        $bgCard = 'background-color: #e9fbe8;';
                        $statusText = 'Hoàn thành - Đợi thanh toán';
                        break;
                    case 'failed':
                        $cardClass = 'border-danger';
                        $headerClass = 'bg-danger text-white';
                        $bgCard = 'background-color: #fdecea;';
                        $statusText = 'Đã hủy';
                        break;
                    case 'paid':
                        $cardClass = 'border-primary';
                        $headerClass = 'bg-primary text-white';
                        $bgCard = 'background-color:rgb(255, 255, 255);';
                        $statusText = 'Đã thanh toán';
                        break;
                    default:
                        $cardClass = 'border-warning';
                        $headerClass = 'bg-warning text-dark';
                        $bgCard = 'background-color: #fffbe6;';
                        $statusText = 'Đang xác nhận';
                        break;
                }
            ?>

            <div class="col">
                <div class="card shadow <?= $cardClass ?>" style="<?= $bgCard ?>">
                    <div class="card-header <?= $headerClass ?> fw-bold">
                        Đơn #<?= $order['orderId'] ?> - Bàn <?= $order['tableNumber'] ?>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-primary"><?= htmlspecialchars($statusText) ?></h5>

                        <p class="card-text">
                            <strong>Khách hàng:</strong> <?= htmlspecialchars($order['customer']['name']) ?>
                        </p>

                        <p class="card-text"><strong>Món đã đặt:</strong></p>
                        <ul class="mb-3 ps-3">
                            <?php foreach ($order['itemsPerOrder'] as $item): ?>
                                <li>
                                    <?= htmlspecialchars($item['name']) ?> (x<?= $item['quantity'] ?>)
                                    - <?= number_format($item['price'], 0, ',', '.') ?>đ
                                    <?php if (!empty($item['note'])): ?>
                                        <br><small class="text-muted">Ghi chú: <?= htmlspecialchars($item['note']) ?></small>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>

                        <p class="card-text text-muted">
                            <em>Thời gian: <?= htmlspecialchars($order['date']) ?></em>
                        </p>
                    </div>
                    
                    <?php if ($order['status'] == 'pending'): ?>
                        <div class="card-footer d-flex justify-content-between align-items-center">
                            <form method="POST" action="/cnpm-final/OrderController/confirmOrder" class="m-0 p-0">
                                <input type="hidden" name="username" value="<?= $_SESSION['username'] ?>">
                                <input type="hidden" name="orderId" value="<?= $order['orderId'] ?>">
                                <input type="hidden" name="status" value="success">
                                <button type="submit" class="btn btn-success btn-sm">Hoàn tất</button>
                            </form>

                            <form method="POST" action="/cnpm-final/OrderController/confirmOrder" class="m-0 p-0">
                                <input type="hidden" name="username" value="<?= $_SESSION['username'] ?>">
                                <input type="hidden" name="orderId" value="<?= $order['orderId'] ?>">
                                <input type="hidden" name="status" value="failed">
                                <button type="submit" class="btn btn-danger btn-sm">Thất bại</button>
                            </form>
                        </div>
                    <?php endif; ?>
                    
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
