<div class="container mt-5" style="max-width: 500px;">
    <h3 class="mb-4 text-center">Đăng nhập</h3>

    <?php if (!empty($data['error'])): ?>
        <div class="alert alert-danger"><?php echo $data['error']; ?></div>
    <?php endif; ?>

    <?php if (!empty($data['success'])): ?>
        <div class="alert alert-success"><?php echo $data['success']; ?></div>
    <?php endif; ?>

    <form method="POST" action="/cnpm-final/AuthController/login">
        <div class="mb-3">
            <label class="form-label">Tên đăng nhập</label>
            <input type="text" name="username" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Mật khẩu</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label me-3">Vai trò:</label>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="role" value="staff" id="staff" checked>
                <label class="form-check-label" for="staff">Nhân viên</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="role" value="customer" id="customer">
                <label class="form-check-label" for="customer">Khách hàng</label>
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Đăng nhập</button>
            <a href="/cnpm-final/HomeController/registerPage" class="btn btn-outline-secondary">Đăng ký</a>
        </div>
    </form>
</div>