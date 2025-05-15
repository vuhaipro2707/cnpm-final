<div class="container mt-5" style="max-width: 500px;">
    <h2 class="mb-4 text-center">Đổi mật khẩu</h2>

    <?php 
    if (!empty($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['message']['type'] ?>" role="alert">
            <?= htmlspecialchars($_SESSION['message']['text']) ?>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <form method="post" action="/cnpm-final/AuthController/changePassword">
        <div class="mb-3">
            <label for="old_password" class="form-label">Mật khẩu cũ</label>
            <input type="password" class="form-control" id="old_password" name="old_password" required>
        </div>

        <div class="mb-3">
            <label for="new_password" class="form-label">Mật khẩu mới</label>
            <input type="password" class="form-control" id="new_password" name="new_password" required>
        </div>

        <div class="mb-3">
            <label for="confirm_password" class="form-label">Xác nhận mật khẩu mới</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Đổi mật khẩu</button>
    </form>
</div>
