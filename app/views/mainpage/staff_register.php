<?php $old = $_SESSION['remStaRegister'] ?? []; ?>

<div class="container mt-5">
    <h2 class="mb-4 text-center">➕ Tạo tài khoản nhân viên</h2>

    <div class="col-md-6 col-lg-5 mx-auto">
        <form action="/cnpm-final/AuthController/staffRegister" method="POST" enctype="multipart/form-data" class="shadow p-4 bg-body rounded">

            <div class="mb-3">
                <label for="name" class="form-label">Họ tên nhân viên</label>
                <input type="text" class="form-control" id="name" name="name" required value="<?= htmlspecialchars($old['name'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label for="position" class="form-label">Chức vụ</label>
                <input type="text" class="form-control" id="position" name="position" required value="<?= htmlspecialchars($old['position'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label for="salary" class="form-label">Lương</label>
                <input type="text" class="form-control" id="salary" name="salary" required oninput="formatSalary(this)" value="<?= htmlspecialchars($old['salary'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Số điện thoại</label>
                <input type="text" class="form-control" id="phone" name="phone" required value="<?= htmlspecialchars($old['phone'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label for="username" class="form-label">Tên đăng nhập</label>
                <input type="text" class="form-control" id="username" name="username" required value="<?= htmlspecialchars($old['username'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="mb-3">
                <label for="confirmPassword" class="form-label">Xác nhận mật khẩu</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
            </div>

            <div class="mb-3">
                <label for="avatar" class="form-label">Ảnh đại diện (tuỳ chọn)</label>
                <input class="form-control" type="file" id="avatar" name="avatar" accept="image/*">
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="isManager" name="isManager" <?= !empty($old['isManager']) ? 'checked' : '' ?>>
                <label class="form-check-label" for="isManager">Quản lý?</label>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <button type="submit" class="btn btn-success w-100">Tạo tài khoản</button>
            <a href="/cnpm-final/StaffController/managerStaffManagePage" class="btn btn-secondary w-100 mt-2">Quay lại</a>
        </form>
    </div>
</div>

<script>
    function formatSalary(input) {
        let value = input.value.replace(/\D/g, '');
        value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        input.value = value;
    }
</script>

<?php unset($_SESSION['remStaRegister']); ?>
