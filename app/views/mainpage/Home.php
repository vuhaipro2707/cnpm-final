<?php
$role = $_SESSION['role'] ?? 'guest';
?>

<div class="container my-4">
    <div class="card shadow-sm rounded">
        <div class="card-body">
            <h4 class="card-title mb-4">Trang chủ</h4>
            <div class="row row-cols-1 row-cols-md-3 g-3">

                <?php if ($role === 'staff' || $role === 'manager'): ?>
                    <div class="col">
                        <a href="/cnpm-final/OrderController/orderConfirmPage" class="btn btn-outline-primary w-100 py-3">
                            📦 Xác nhận đơn hàng
                        </a>
                        
                    </div>
                    <div class="col">
                        <a href="/cnpm-final/TableController/manageTableLayout" class="btn btn-outline-primary w-100 py-3">
                            Quản lý bàn
                        </a>
                    </div>
                    
                <?php endif; ?>

                <?php if ($role === 'customer'): ?>
                    <div class="col">
                        <a href="/cnpm-final/InventoryController/customerMenuPage" class="btn btn-outline-success w-100 py-3">
                            📋 Xem Menu
                        </a>
                    </div>
                    <div class="col">
                        <a href="/cnpm-final/OrderController/customerTrackOrderPage" class="btn btn-outline-info w-100 py-3">
                            🚚 Theo dõi đơn hàng
                        </a>
                    </div>
                <?php endif; ?>

                <?php if ($role === 'manager'): ?>
                    <div class="col">
                        <a href="/cnpm-final/InventoryController/displayAllItem" class="btn btn-outline-dark w-100 py-3">
                            🍽️ Quản lý Menu
                        </a>
                    </div>
                    <div class="col">
                        <a href="/cnpm-final/StaffController/managerStaffManagePage" class="btn btn-outline-warning w-100 py-3">
                            🧑‍💼 Quản lý Nhân viên
                        </a>
                    </div>
                    <div class="col">
                        <a href="/cnpm-final/PromotionController/managePromotionPage" class="btn btn-outline-danger w-100 py-3">
                            🎁 Quản lý Khuyến mãi
                        </a>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>
