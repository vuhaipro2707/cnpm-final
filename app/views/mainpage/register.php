<?php $old = $_SESSION['remCusRegister'] ?? []; ?>

<div class="container mt-5" style="max-width: 500px;">
    <h3 class="mb-4 text-center">Đăng ký tài khoản khách hàng</h3>

    <?php if (!empty($data['error'])): ?>
        <div class="alert alert-danger"><?php echo $data['error']; ?></div>
    <?php endif; ?>

    <?php if (!empty($data['success'])): ?>
        <div class="alert alert-success"><?php echo $data['success']; ?></div>
    <?php endif; ?>

    <form method="POST" action="/cnpm-final/AuthController/customerRegister" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Tên khách hàng</label>
            <input type="text" name="name" class="form-control" required
                   value="<?= isset($old['name']) ? htmlspecialchars($old['name']) : '' ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Số điện thoại</label>
            <input type="tel" name="phone" pattern="[0-9]{10}" class="form-control" required
                   value="<?= isset($old['phone']) ? htmlspecialchars($old['phone']) : '' ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Tên đăng nhập</label>
            <input type="text" name="username" class="form-control" required
                   value="<?= isset($old['username']) ? htmlspecialchars($old['username']) : '' ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Mật khẩu</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Xác nhận mật khẩu</label>
            <input type="password" name="confirm_password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Ảnh đại diện (tuỳ chọn)</label>
            <input type="file" name="avatar" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn btn-success w-100">Đăng ký</button>
    </form>

    <div class="text-center mt-3">
        <a href="/cnpm-final/AuthController/login">Đã có tài khoản? Đăng nhập</a>
    </div>
</div>

<?php unset($_SESSION['remCusRegister']); ?>
