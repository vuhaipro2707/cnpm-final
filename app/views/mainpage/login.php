<div class="container mt-5" style="max-width: 500px;">
    <!-- Card container với bo góc -->
    <div class="card shadow-sm" style="border-radius: 15px;">
        <div class="card-body">
            <h3 class="mb-4 text-center">Đăng nhập</h3>

            <!-- Error alert -->
            <?php if (!empty($data['error'])): ?>
                <div class="alert alert-danger" style="border-radius: 10px;"><?php echo $data['error']; ?></div>
            <?php endif; ?>

            <!-- Success alert -->
            <?php if (!empty($data['success'])): ?>
                <div class="alert alert-success" style="border-radius: 10px;"><?php echo $data['success']; ?></div>
            <?php endif; ?>

            <!-- Form -->
            <form method="POST" action="/cnpm-final/AuthController/login">
                <div class="mb-3">
                    <label class="form-label">Tên đăng nhập</label>
                    <input type="text" name="username" class="form-control" required style="border-radius: 10px;">
                </div>

                <div class="mb-3">
                    <label class="form-label">Mật khẩu</label>
                    <input type="password" name="password" class="form-control" required style="border-radius: 10px;">
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
                    <button type="submit" class="btn btn-primary" style="border-radius: 10px;">Đăng nhập</button>
                    <a href="/cnpm-final/HomeController/registerPage" class="btn btn-outline-secondary" style="border-radius: 10px;">Đăng ký</a>
                </div>
            </form>
        </div>
    </div>
</div>
