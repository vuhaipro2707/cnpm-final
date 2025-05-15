<div class="container py-5">
    <h2 class="mb-4 text-center">🎁 Quản lý mã khuyến mãi</h2>

    <?php if (!empty($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['message']['type'] ?>" role="alert">
            <?= htmlspecialchars($_SESSION['message']['text']) ?>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <div class="row">
        <!-- Danh sách khuyến mãi -->
        <div class="col-md-7">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    📋 Danh sách khuyến mãi

                    <?php
                    $today = date('Y-m-d'); 
                    $activeCount = count(array_filter($data['promotion'], function ($promo) use ($today) {
                        return $promo['active'] == 1 && $promo['endDate'] >= $today;
                    }));
                    ?>
                    <span class="badge bg-light text-dark">
                        <?= $activeCount ?> mã đang hoạt động
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Mã</th>
                                    <th>Giảm</th>
                                    <th>Thời gian</th>
                                    <th>Trạng thái</th>
                                    <th class="text-center">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if (!empty($data['promotion'])): ?>
                                <?php foreach ($data['promotion'] as $promo): ?>
                                    <tr>
                                        <td><strong>#<?= $promo['promotionId'] ?></strong></td>
                                        <td class="text-uppercase"><?= htmlspecialchars($promo['discountCode']) ?></td>
                                        <td><span class="badge bg-info"><?= $promo['discountRate'] ?>%</span></td>
                                        <td>
                                            <small><?= date('d/m/Y', strtotime($promo['startDate'])) ?></small><br>
                                            <small class="text-muted">→ <?= date('d/m/Y', strtotime($promo['endDate'])) ?></small>
                                        </td>
                                        <td>
                                            <?php
                                                $today = date('Y-m-d');
                                                $isExpired = strtotime($promo['endDate']) < strtotime($today);
                                            ?>

                                            <?php if ($isExpired): ?>
                                                <span class="badge bg-danger">Hết hạn</span>
                                            <?php elseif ($promo['active']): ?>
                                                <span class="badge bg-success">Đang hoạt động</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Tạm ngừng</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex gap-1 justify-content-center">
                                                <?php if (!$isExpired): ?>
                                                <!-- Toggle trạng thái -->
                                                <form method="POST" action="/cnpm-final/PromotionController/toggleActive">
                                                    <input type="hidden" name="promotionId" value="<?= $promo['promotionId'] ?>">
                                                    <input type="hidden" name="active" value="<?= $promo['active'] ? 0 : 1 ?>">
                                                    <button type="submit" class="btn btn-sm <?= $promo['active'] ? 'btn-outline-warning' : 'btn-outline-success' ?>" title="<?= $promo['active'] ? 'Tắt mã' : 'Kích hoạt mã' ?>">
                                                        <?= $promo['active'] ? 'Tắt' : 'Bật' ?>
                                                    </button>
                                                </form>
                                                <?php endif; ?>

                                                <!-- Xoá -->
                                                <form method="POST" action="/cnpm-final/PromotionController/delete" onsubmit="return confirm('Xoá mã này?')">
                                                    <input type="hidden" name="promotionId" value="<?= $promo['promotionId'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">🗑</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="6" class="text-center py-3 text-muted">Chưa có mã khuyến mãi nào</td></tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tạo khuyến mãi mới -->
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    ➕ Tạo mã khuyến mãi mới
                </div>
                <div class="card-body">
                    <form method="POST" action="/cnpm-final/PromotionController/create">
                        <div class="mb-3">
                            <label for="discountCode" class="form-label">Mã giảm giá</label>
                            <input type="text" class="form-control" id="discountCode" name="discountCode" maxlength="50" required>
                        </div>

                        <div class="mb-3">
                            <label for="discountRate" class="form-label">Phần trăm giảm</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="discountRate" name="discountRate" min="1" max="100" required>
                                <span class="input-group-text">%</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="startDate" class="form-label">Ngày bắt đầu</label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="startDate" 
                                name="startDate" 
                                value="<?= date('m/d/Y') ?>" 
                                readonly 
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="endDate" class="form-label">Ngày kết thúc</label>
                            <input 
                                type="date" 
                                class="form-control" 
                                id="endDate" 
                                name="endDate" 
                                required>
                        </div>


                        <div class="mb-3">
                            <label for="active" class="form-label">Trạng thái</label>
                            <select class="form-select" id="active" name="active">
                                <option value="1" selected>Đang hoạt động</option>
                                <option value="0">Tạm ngừng</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success w-100">Tạo mã mới</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
