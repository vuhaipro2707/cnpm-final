<?php
    var_dump($data['order'])
?>


<div class="container-fluid mt-5">
    <div class="row">
        <!-- Sidebar hóa đơn -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Phiếu Đơn Hàng</div>
                <div class="card-body">
                    <p><strong>Bàn:</strong> <?=  $data['order']['tableNumber'] ?></p>
                    <ul class="list-group mb-3">
                        <?php
                        $total = 0;
                        foreach ($data['order']['itemsPerOrder'] as $item):
                            $subtotal = $item['itemPrice'] * $item['quantity'];
                            $total += $subtotal;
                        ?>
                            <li class="list-group-item d-flex justify-content-between">
                                <?= $item['itemName'] ?> x<?= $item['quantity'] ?>
                                <span><?= number_format($subtotal) ?>đ</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <!-- Mã khuyến mãi -->
                    <form method="POST" action="/cnpm-final/PaymentController/applyPromotion" class="mb-2">
                        <div class="input-group">
                            <input type="text" name="promotionCode" class="form-control" placeholder="Mã khuyến mãi">
                            <button class="btn btn-success">Check</button>
                        </div>
                    </form>

                    <!-- Sử dụng điểm tích luỹ -->
                    <button class="btn btn-warning w-100 mb-3" data-bs-toggle="modal" data-bs-target="#pointModal">
                        Sử dụng điểm tích luỹ
                    </button>

                    <!-- Tính toán khuyến mãi -->
                    <?php
                    $discount = $_SESSION['payment']['discountAmount'] ?? 0;
                    $pointReduce = ($_SESSION['payment']['reducePoint'] ?? 0) * 1000;
                    $finalTotal = $total - $discount - $pointReduce;
                    ?>
                    <p>Trước khuyến mãi: <?= number_format($total) ?>đ</p>
                    <?php if ($discount): ?>
                        <p>Giảm khuyến mãi: -<?= number_format($discount) ?>đ</p>
                    <?php endif; ?>
                    <?php if ($pointReduce): ?>
                        <p>Trừ điểm tích luỹ: -<?= number_format($pointReduce) ?>đ</p>
                    <?php endif; ?>
                    <h5>Tổng tiền: <strong class="text-danger"><?= number_format(max($finalTotal, 0)) ?>đ</strong></h5>
                </div>
            </div>
        </div>

        <!-- Main chọn phương thức thanh toán -->
        <div class="col-md-8 d-flex flex-column align-items-center">
            <h3 class="text-center mb-4">Hãy chọn phương thức thanh toán</h3>
            <div class="row row-cols-2 g-4 w-75">
                <div class="col">
                    <div class="card text-center p-4">
                        <h5>Thanh toán tiền mặt</h5>
                        <button class="btn btn-primary mt-2 w-100">Chọn</button>
                    </div>
                </div>
                <div class="col">
                    <div class="card text-center p-4">
                        <h5>Chuyển khoản</h5>
                        <button class="btn btn-primary mt-2 w-100">Chọn</button>
                    </div>
                </div>
                <div class="col">
                    <div class="card text-center p-4">
                        <h5>Ví điện tử</h5>
                        <button class="btn btn-primary mt-2 w-100">Chọn</button>
                    </div>
                </div>
                <div class="col">
                    <div class="card text-center p-4">
                        <h5>Quét mã QR</h5>
                        <button class="btn btn-primary mt-2 w-100">Chọn</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</di>
