<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý nhân viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">👥 Danh sách nhân viên</h2>
    <a href="/cnpm-final/StaffController/create" class="btn btn-primary mb-3">+ Tạo tài khoản nhân viên</a>

    <div class="list-group">
        <?php foreach ($data['staffList'] as $staff): ?>
            <?php
                $imgSrc = empty($staff['avatar']) 
                    ? '/cnpm-final/public/images/avatar/default.jpg' 
                    : '/cnpm-final/public/images/avatar/' . $staff['avatar'];
            ?>
            <div class="list-group-item list-group-item-action d-flex align-items-center justify-content-between shadow-sm p-3 mb-2 bg-body rounded">
                <div class="d-flex align-items-center">
                    <img src="<?= $imgSrc ?>" class="rounded-circle me-3 border" width="60" height="60" alt="avatar">
                    <div>
                        <h5 class="mb-1"><?= $staff['name'] ?> <?= $staff['isManager'] ? ' <span class="badge bg-success">Quản lý</span>' : '' ?></h5>
                        <p class="mb-1 text-muted">Chức vụ: <?= $staff['position'] ?> | Username: <?= $staff['username'] ?></p>
                    </div>
                </div>
                <div>
                    <button 
                        class="btn btn-sm btn-warning edit-btn me-1"
                        data-bs-toggle="modal"
                        data-bs-target="#editModal"
                        data-id="<?= $staff['staffId'] ?>"
                        data-name="<?= htmlspecialchars($staff['name']) ?>"
                        data-position="<?= htmlspecialchars($staff['position']) ?>"
                        data-username="<?= htmlspecialchars($staff['username']) ?>"
                        data-ismanager="<?= $staff['isManager'] ?>"
                    >Chỉnh sửa</button>
                    
                    <a href="/cnpm-final/StaffController/delete/<?= $staff['staffId'] ?>" 
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Bạn có chắc chắn muốn xóa nhân viên này?')">
                       Xoá
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>


<!-- MODAL CHỈNH SỬA -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="/cnpm-final/StaffController/update">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">📝 Chỉnh sửa nhân viên</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="staffId" id="edit-staffId">
                <div class="mb-3">
                    <label>Tên nhân viên</label>
                    <input type="text" name="name" id="edit-name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Chức vụ</label>
                    <input type="text" name="position" id="edit-position" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Username</label>
                    <input type="text" name="username" id="edit-username" class="form-control" readonly>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Lưu thay đổi</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
            </div>
        </div>
    </form>
  </div>
</div>


<script>
    const editButtons = document.querySelectorAll('.edit-btn');
    const staffIdInput = document.getElementById('edit-staffId');
    const nameInput = document.getElementById('edit-name');
    const positionInput = document.getElementById('edit-position');
    const usernameInput = document.getElementById('edit-username');
    const isManagerCheckbox = document.getElementById('edit-isManager');

    editButtons.forEach(button => {
        button.addEventListener('click', () => {
            staffIdInput.value = button.getAttribute('data-id');
            nameInput.value = button.getAttribute('data-name');
            positionInput.value = button.getAttribute('data-position');
            usernameInput.value = button.getAttribute('data-username');
        });
    });
</script>
</body>
</html>
