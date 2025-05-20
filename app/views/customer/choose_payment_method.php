<div class="container-fluid mt-5">
    <div class="row">
        <!-- Sidebar hóa đơn -->
        <div class="col-md-4">
            <?php if (!empty($_SESSION['message'])): ?>
                <div class="alert alert-<?= $_SESSION['message']['type'] ?>" role="alert">
                    <?= htmlspecialchars($_SESSION['message']['text']) ?>
                </div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>
            <div class="card shadow rounded mx-auto" style="max-width: 450px;">
                
                
                <div class="card-header bg-light text-dark fw-bold">
                    Phiếu Đơn Hàng
                </div>
                <div class="card-body">
                    <!-- Bàn -->
                    <p><strong>Bàn:</strong> <?= $data['order']['tableNumber'] ?></p>

                    <!-- Khách hàng -->
                    <?php $customer = $data['order']['customer']; ?>
                    <?php $promotion = $data['payment']['promotion'] ?? null; ?>
                    <p><strong>Khách:</strong> <?= $customer['name'] ?> (<?= $customer['phone'] ?>)</p>
                    <p><strong>Điểm tích lũy:</strong> <?= $customer['points'] ?> điểm</p>

                    <!-- Danh sách món -->
                    <ul class="list-group mb-3">
                        <?php
                        $total = 0;
                        foreach ($data['order']['itemsPerOrder'] as $item):
                            $subtotal = $item['price'] * $item['quantity'];
                            $total += $subtotal;
                        ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div><?= $item['name'] ?> <span class="badge bg-secondary">x<?= $item['quantity'] ?></span></div>
                                <span><?= number_format($subtotal) ?>đ</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <!-- Mã khuyến mãi -->
                    <div class="mb-3">
                        <label class="form-label">Mã khuyến mãi:</label>
                        <div class="d-flex align-items-center gap-2">
                            <form method="POST" action="/cnpm-final/PaymentController/applyPromotion" class="d-flex w-100 gap-2">
                                <input type="hidden" name="orderId" value="<?= $data['order']['orderId'] ?>">
                                <input type="text" name="discountCode" class="form-control" placeholder="Nhập mã" value="<?= $promotion['discountCode'] ?? '' ?>" style="max-width: 250px;">
                                <button type="submit" class="btn btn-success">Áp dụng</button>
                            </form>

                            <?php if (!empty($promotion['discountCode'])): ?>
                                <form method="POST" action="/cnpm-final/PaymentController/removePromotion">
                                    <input type="hidden" name="orderId" value="<?= $data['order']['orderId'] ?>">
                                    <button type="submit" class="btn btn-outline-danger">Xóa</button>
                                </form>
                            <?php endif; ?>
                        </div>

                        <!-- Thông báo lỗi nếu có -->
                        <?php if (!empty($_SESSION['payment']['promoError'])): ?>
                            <div class="text-danger mt-2"><?= $_SESSION['payment']['promoError'] ?></div>
                            <?php unset($_SESSION['payment']['promoError']); ?>
                        <?php endif; ?>
                    </div>


                    <!-- Sử dụng điểm tích luỹ -->
                    <button class="btn btn-warning w-100 mb-3" data-bs-toggle="modal" data-bs-target="#pointModal">
                        Sử dụng điểm tích luỹ
                    </button>

                    <!-- Tính toán -->
                    <?php
                    $pointsApplied = $data['payment']['pointsApplied'] ?? 0;
                    $discountRate = (int)($promotion['discountRate'] ?? 0);
                    $discount = round($discountRate / 100 * $total);
                    $totalAfterDiscount = $total - $discount;
                    $pointReduce = $pointsApplied * 1000;
                    $finalTotal = $total - $discount - $pointReduce;
                    $pointsBonus = round($finalTotal / 10000);
                    $pointsLefts = $customer['points'] - $pointsApplied;
                
                    ?>

                    <!-- Hiển thị tổng kết -->
                    <div class="border-top pt-3">
                        <p>Trước khuyến mãi: <strong><?= number_format($total) ?>đ</strong></p>
                        <?php if ($discount): ?>
                            <p>Giảm khuyến mãi: <span class="text-success">-<?= number_format($discount) ?>đ</span> (Mã: <?= $promotion['discountCode'] ?>)</p>
                        <?php endif; ?>
                        <?php if ($pointReduce): ?>
                            <p>Giảm giá điểm tích luỹ: <span class="text-success">-<?= number_format($pointReduce) ?>đ</span> (<?= $pointsApplied ?> điểm, còn lại: <?= $pointsLefts ?>)</p>
                        <?php endif; ?>
                        <h5 class="mt-3">Tổng tiền: <strong class="text-danger"><?= number_format(max($finalTotal, 0)) ?>đ</strong></h5>
                        <p>Điểm tích luỹ nhận thêm: <strong><?= $pointsBonus ?> điểm</strong></p>
                    </div>
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
                        <button class="btn btn-primary mt-2 w-100" data-bs-toggle="modal" data-bs-target="#cashPaymentModal">Chọn</button>
                    </div>
                </div>
                <div class="col">
                    <div class="card text-center p-4">
                        <h5>Chuyển khoản</h5>
                        <button class="btn btn-primary mt-2 w-100" data-bs-toggle="modal" data-bs-target="#bankTransferModal">Chọn</button>
                    </div>
                </div>
                <div class="col">
                    <div class="card text-center p-4">
                        <h5>Ví điện tử Momo</h5>
                        <button class="btn btn-primary mt-2 w-100" data-bs-toggle="modal" data-bs-target="#momoPaymentModal">Chọn</button>
                    </div>
                </div>
                <div class="col">
                    <div class="card text-center p-4">
                        <h5>Quét mã QR</h5>
                        <button class="btn btn-primary mt-2 w-100" data-bs-toggle="modal" data-bs-target="#qrPaymentModal">Chọn</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="pointModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <!-- Form áp dụng điểm -->
      <form method="POST" action="/cnpm-final/PaymentController/applyPoints">
        <input type="hidden" name="orderId" value="<?= $data['order']['orderId'] ?>">
        <input type="hidden" name="customerPoints" value="<?= $customer['points'] ?>">
        <input type="hidden" name="totalAfterDiscount" value="<?= $totalAfterDiscount ?>">
        <div class="modal-header">
          <h5 class="modal-title">Sử dụng điểm tích luỹ</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center">
          <p>Điểm hiện tại: <?= $customer['points'] ?? 0 ?></p>
          <div class="btn-group-vertical w-100">
            <?php foreach ([10, 20, 50, 100] as $points): ?>
              <button name="points" value="<?= $points ?>" class="btn btn-outline-primary mb-2">
                Dùng <?= $points ?> điểm (<?= number_format($points * 1000) ?>đ)
              </button>
            <?php endforeach; ?>
          </div>
          <p class="text-muted mt-2">1 điểm = 1.000 VND</p>
        </div>
      </form>

      <!-- Form không sử dụng điểm -->
      <form method="POST" action="/cnpm-final/PaymentController/removeApplyPoints">
        <input type="hidden" name="orderId" value="<?= $data['order']['orderId'] ?>">
        <div class="modal-footer justify-content-center">
          <button type="submit" class="btn btn-outline-secondary">Không sử dụng điểm</button>
        </div>
      </form>

    </div>
  </div>
</div>


<!-- Modal Tiền mặt -->
<div class="modal fade" id="cashPaymentModal" tabindex="-1" aria-labelledby="cashPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="/cnpm-final/PaymentController/confirmPayment">
                <div class="modal-header">
                    <h5 class="modal-title" id="cashPaymentModalLabel">Thanh toán tiền mặt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn đã chọn phương thức thanh toán: Tiền mặt</p>
                    <input type="hidden" name="customerPoints" value="<?=$customer['points']?>">
                    <input type="hidden" name="pointsLefts" value="<?=$pointsLefts?>">
                    <input type="hidden" name="pointsBonus" value="<?=$pointsBonus?>">
                    <input type="hidden" name="total" value="<?=$finalTotal?>">
                    <input type="hidden" name="method" value="cash">
                    <input type="hidden" name="orderId" value="<?= $data['order']['orderId'] ?>">
                    <p>Vui lòng kiểm tra thông tin thanh toán và nhấn "Xác nhận" để hoàn tất.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Xác nhận thanh toán</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Chuyển khoản -->
<div class="modal fade" id="bankTransferModal" tabindex="-1" aria-labelledby="bankTransferModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="/cnpm-final/PaymentController/confirmPayment">
                <div class="modal-header">
                    <h5 class="modal-title" id="bankTransferModalLabel">Thanh toán chuyển khoản</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn đã chọn phương thức thanh toán: Chuyển khoản</p>
                    <input type="hidden" name="customerPoints" value="<?=$customer['points']?>">
                    <input type="hidden" name="pointsLefts" value="<?=$pointsLefts?>">
                    <input type="hidden" name="pointsBonus" value="<?=$pointsBonus?>">
                    <input type="hidden" name="total" value="<?=$finalTotal?>">
                    <input type="hidden" name="method" value="transfer">
                    <input type="hidden" name="orderId" value="<?= $data['order']['orderId'] ?>">
                    <p>Vui lòng kiểm tra thông tin thanh toán và nhấn "Xác nhận" để hoàn tất.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Xác nhận thanh toán</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Momo -->
<div class="modal fade" id="momoPaymentModal" tabindex="-1" aria-labelledby="momoPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="/cnpm-final/PaymentController/confirmPayment">
                <div class="modal-header">
                    <h5 class="modal-title" id="momoPaymentModalLabel">Thanh toán qua Momo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn đã chọn phương thức thanh toán: Ví điện tử Momo</p>
                    <input type="hidden" name="customerPoints" value="<?=$customer['points']?>">
                    <input type="hidden" name="pointsLefts" value="<?=$pointsLefts?>">
                    <input type="hidden" name="pointsBonus" value="<?=$pointsBonus?>">
                    <input type="hidden" name="total" value="<?=$finalTotal?>">
                    <input type="hidden" name="method" value="momo">
                    <input type="hidden" name="orderId" value="<?= $data['order']['orderId'] ?>">
                    <p>Vui lòng kiểm tra thông tin thanh toán và nhấn "Xác nhận" để hoàn tất.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Xác nhận thanh toán</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal QR -->
<div class="modal fade" id="qrPaymentModal" tabindex="-1" aria-labelledby="qrPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="/cnpm-final/PaymentController/confirmPayment">
                <div class="modal-header">
                    <h5 class="modal-title" id="qrPaymentModalLabel">Thanh toán qua QR</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn đã chọn phương thức thanh toán: Quét mã QR</p>
                    <input type="hidden" name="customerPoints" value="<?=$customer['points']?>">
                    <input type="hidden" name="pointsLefts" value="<?=$pointsLefts?>">
                    <input type="hidden" name="pointsBonus" value="<?=$pointsBonus?>">
                    <input type="hidden" name="total" value="<?=$finalTotal?>">
                    <input type="hidden" name="method" value="qr">
                    <input type="hidden" name="orderId" value="<?= $data['order']['orderId'] ?>">
                    <p>Vui lòng kiểm tra thông tin thanh toán và nhấn "Xác nhận" để hoàn tất.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Xác nhận thanh toán</button>
                </div>
            </form>
        </div>
    </div>
</div>

