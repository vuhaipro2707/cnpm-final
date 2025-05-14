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
    <a href="/cnpm-final/HomeController/staffRegisterPage" class="btn btn-primary mb-3">+ Tạo tài khoản nhân viên</a>

    <?php
    if (isset($_SESSION['success'])) {
        echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
        unset($_SESSION['success']);
    }

    if (isset($_SESSION['error'])) {
        echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']);
    }
    ?>

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
                        <h5 class="mb-1"><?= $staff['name'] ?> <span class="text-muted">@<?= $staff['username'] ?></span> <?= $staff['isManager'] ? ' <span class="badge bg-success">Quản lý</span>' : '' ?></h5>
                        <p class="mb-1 text-muted">Chức vụ: <?= $staff['position'] ?></p>
                        <p class="mb-1 text-muted">Số điện thoại: <?= $staff['phone'] ?></p>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <p class="fw-bold mb-0" style="min-width: 100px; text-align: right; padding-right: 10px;">Lương: <?= number_format($staff['salary'], 0, ',', '.') ?> VND</p>
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
                            data-salary="<?= $staff['salary'] ?>"
                            data-phone="<?= $staff['phone'] ?>"
                        >Chỉnh sửa</button>

                        <form method="POST" action="/cnpm-final/StaffController/deleteStaff" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa nhân viên này?')">
                            <input type="hidden" name="staffId" value="<?= $staff['staffId'] ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Xoá</button>
                        </form>
                    </div>
                </div>

            </div>
        <?php endforeach; ?>
    </div>
</div>



<!-- MODAL CHỈNH SỬA -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="/cnpm-final/StaffController/updateStaff">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">📝 Chỉnh sửa nhân viên</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="staffId" id="edit-staffId">
                <input type="hidden" name="isManager" id="edit-isManager">
                <div class="mb-3">
                    <label>Tên nhân viên</label>
                    <input type="text" name="name" id="edit-name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Chức vụ</label>
                    <input type="text" name="position" id="edit-position" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Số điện thoại</label>
                    <input type="text" name="phone" id="edit-phone" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Lương</label>
                    <input type="text" name="salary" id="edit-salary" class="form-control" required oninput="formatSalary(this)">
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
    const isManagerInput = document.getElementById('edit-isManager');

    editButtons.forEach(button => {
        button.addEventListener('click', () => {
            staffIdInput.value = button.getAttribute('data-id');
            nameInput.value = button.getAttribute('data-name');
            positionInput.value = button.getAttribute('data-position');
            isManagerInput.value = button.getAttribute('data-ismanager');
            document.getElementById('edit-phone').value = button.getAttribute('data-phone');
            document.getElementById('edit-salary').value = formatNumber(button.getAttribute('data-salary'));
        });
    });

    function formatNumber(value) {
        value = value.replace(/\D/g, '');
        return value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function formatSalary(input) {
        let value = input.value;
        value = value.replace(/\D/g, '');
        input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }
</script>

</body>
</html>
