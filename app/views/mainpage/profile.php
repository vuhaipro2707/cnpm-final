<?php
$role = $_SESSION['role'] ?? 'guest';
$info = ($role === 'customer') ? $data['customer'] : $data['staff'];

$imgSrc = empty($_SESSION['avatar']) 
    ? '/cnpm-final/public/images/avatar/default.jpg' 
    : '/cnpm-final/public/images/avatar/' . $_SESSION['avatar'];
?>

<div class="container mt-5">
    <div class="card mx-auto shadow-lg" style="max-width: 500px;">
        <div class="card-body text-center">
            <img src="<?= $imgSrc ?>" alt="Avatar" class="rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover;">
            <h4 class="card-title"><?= htmlspecialchars($info['name']) ?></h4>
            <p class="text-muted">@<?= htmlspecialchars($info['username']) ?></p>

            <hr>
            <div class="text-start">
                <?php if ($role === 'customer'): ?>
                    <p><strong>📞 Số điện thoại:</strong> <?= htmlspecialchars($info['phone']) ?></p>
                    <p><strong>⭐ Điểm tích lũy:</strong> <?= number_format($info['points']) ?> điểm</p>
                <?php elseif ($role === 'staff' || $role === 'manager'): ?>
                    <p><strong>📞 Số điện thoại:</strong> <?= htmlspecialchars($info['phone']) ?></p>
                    <p><strong>🏷️ Chức vụ:</strong> <?= htmlspecialchars($info['position']) ?></p>
                    <p><strong>🧑‍💼 Vị trí:</strong> <?= $role === 'manager' ? 'Quản lý' : 'Nhân viên' ?></p>
                    <p><strong>💰 Lương:</strong> <?= number_format($info['salary'], 0, ',', '.') ?> VND</p>
                <?php endif; ?>
            </div>

            <div class="d-grid gap-2 mt-3">
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal">✏️ Thay đổi thông tin</button>
                <a href="/cnpm-final/HomeController/index" class="btn btn-secondary">← Trang chủ</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cập Nhật -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" enctype="multipart/form-data" action="/cnpm-final/ProfileController/update">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Cập nhật thông tin</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="role" value="<?= $role ?>">
          <input type="hidden" name="username" value="<?= $info['username'] ?>">

          <?php if ($role === 'customer'): ?>
              <div class="mb-3">
                  <label class="form-label">Họ tên</label>
                  <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($info['name']) ?>" required>
              </div>
              <div class="mb-3">
                  <label class="form-label">Số điện thoại</label>
                  <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars($info['phone']) ?>" required>
              </div>
          <?php elseif ($role === 'staff' || $role === 'manager'): ?>
              <div class="mb-3">
                  <label class="form-label">Họ tên</label>
                  <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($info['name']) ?>" required>
              </div>
              <div class="mb-3">
                  <label class="form-label">Chức vụ</label>
                  <input type="text" class="form-control" name="position" value="<?= htmlspecialchars($info['position']) ?>" required>
              </div>
              <div class="mb-3">
                  <label class="form-label">Số điện thoại</label>
                  <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars($info['phone']) ?>" required>
              </div>
              <div class="mb-3">
                    <label class="form-label">Lương (VND)</label>
                    <input type="text" class="form-control" name="salary" id="salaryInput" 
                        value="<?= number_format($info['salary'], 0, ',', '.') ?>" required>
              </div>
          <?php endif; ?>

          <div class="mb-3">
              <label class="form-label">Ảnh đại diện</label>
              <input type="file" class="form-control" name="avatar" accept="image/*">
              <small class="text-muted">Để trống nếu không thay đổi ảnh.</small>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Lưu</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
document.getElementById('salaryInput').addEventListener('input', function (e) {
    let value = e.target.value.replace(/\D/g, ''); // Bỏ ký tự không phải số
    value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Thêm dấu chấm mỗi 3 số
    e.target.value = value;
});
</script>
