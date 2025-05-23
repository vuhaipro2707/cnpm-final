<div class="container py-4">
    <div class="mb-3">
        <a href="/cnpm-final/InventoryController/customerMenuPage" class="btn btn-outline-secondary">
            ← Về lại menu
        </a>
    </div>

    
    <h2 class="mb-4 text-center">Đơn hàng của bạn</h2>

    <?php if (!empty($data['error'])): ?>
        <div class="alert alert-danger"><?php echo $data['error']; ?></div>
    <?php endif; ?>

    <?php if (!empty($data['success'])): ?>
        <div class="alert alert-success"><?php echo $data['success']; ?></div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['message']['type'] ?>" role="alert">
            <?= htmlspecialchars($_SESSION['message']['text']) ?>
        </div>
        <?php unset($_SESSION['message']); ?>
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

                // Tính tổng tiền
                $totalPrice = 0;
                foreach ($order['itemsPerOrder'] as $item) {
                    $totalPrice += $item['price'] * $item['quantity'];
                }
            ?>

            <div class="col">
                <div class="card shadow <?= $cardClass ?>" style="<?= $bgCard ?>">
                    <div class="card-header <?= $headerClass ?> fw-bold">
                        Đơn #<?= $order['orderId'] ?> - Bàn <?= $order['tableNumber'] ?>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-primary"><?= $statusText ?></h5>

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

                        <div class="d-flex justify-content-between">
                            <em class="text-muted">Thời gian: <?= htmlspecialchars($order['date']) ?></em>
                            <strong class="text-end text-success">Tổng: <?= number_format($totalPrice, 0, ',', '.') ?>đ</strong>
                        </div>
                    </div>

                    <?php if ($order['status'] == 'success'): ?>
                        <div class="card-footer text-end">
                            <form method="POST" action="/cnpm-final/PaymentController/customerChoosePaymentPage" class="d-inline">
                                <input type="hidden" name="orderId" value="<?= $order['orderId'] ?>">
                                <input type="hidden" name="totalPrice" value="<?=$totalPrice?>">
                                <button type="submit" class="btn btn-primary btn-sm">Thanh toán</button>
                            </form>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

