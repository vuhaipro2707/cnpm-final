<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">👥 Danh sách khách hàng</h2>

        <!-- 🔍 Nhóm tìm kiếm nhỏ bên phải -->
        <form class="d-flex" id="searchForm" onsubmit="return false;">
            <input type="text" id="searchInput" class="form-control form-control-sm me-2" placeholder="Tìm theo tên..." style="width: 200px;">
            <button class="btn btn-sm btn-outline-secondary" id="searchButton" type="button">
                🔍
            </button>
        </form>
    </div>

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

    <div class="list-group" id="customerList">
        <?php foreach ($data['customer'] as $customer): ?>
            <?php
                $imgSrc = empty($customer['avatar']) 
                    ? '/cnpm-final/public/images/avatar/default.jpg' 
                    : '/cnpm-final/public/images/avatar/' . $customer['avatar'];
            ?>
            <div class="list-group-item list-group-item-action d-flex align-items-center justify-content-between shadow-sm p-3 mb-2 bg-body rounded customer-item">
                <div class="d-flex align-items-center">
                    <img src="<?= $imgSrc ?>" class="rounded-circle me-3 border" width="60" height="60" alt="avatar">
                    <div>
                        <h5 class="mb-1 customer-name"><?= htmlspecialchars($customer['name']) ?> <span class="text-muted">@<?= htmlspecialchars($customer['username']) ?></span></h5>
                        <p class="mb-1 text-muted">📞 <?= htmlspecialchars($customer['phone']) ?></p>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-end">
                    <span class="badge bg-primary me-3 p-2">💎 <?= (int)$customer['points'] ?> điểm</span>
                    <button 
                        class="btn btn-sm btn-warning edit-btn me-2"
                        data-bs-toggle="modal"
                        data-bs-target="#editCustomerModal"
                        data-id="<?= $customer['customerId'] ?>"
                        data-name="<?= htmlspecialchars($customer['name']) ?>"
                        data-username="<?= htmlspecialchars($customer['username']) ?>"
                        data-phone="<?= htmlspecialchars($customer['phone']) ?>"
                        data-point="<?= $customer['points'] ?>"
                    >Chỉnh sửa</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="modal fade" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="/cnpm-final/CustomerController/updateCustomer">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">📝 Chỉnh sửa thông tin khách hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="customerId" id="edit-customerId">

                <div class="mb-3">
                    <label>Tên khách hàng</label>
                    <input type="text" name="name" id="edit-name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Số điện thoại</label>
                    <input type="text" name="phone" id="edit-phone" class="form-control" required>
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
    const customerIdInput = document.getElementById('edit-customerId');
    const nameInput = document.getElementById('edit-name');
    const phoneInput = document.getElementById('edit-phone');

    editButtons.forEach(button => {
        button.addEventListener('click', () => {
            customerIdInput.value = button.getAttribute('data-id');
            nameInput.value = button.getAttribute('data-name');
            phoneInput.value = button.getAttribute('data-phone');
        });
    });

    //Lọc khách hàng theo tên
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    const customerItems = document.querySelectorAll('.customer-item');
    

    function filterCustomers() {
        const keyword = searchInput.value.toLowerCase();

        customerItems.forEach(item => {
            const name = item.querySelector('.customer-name').textContent.toLowerCase();
            
            if (name.includes(keyword)) {
                item.classList.remove('d-none');
            } else {
                item.classList.add('d-none'); 
            }
        });
    }

    searchButton.addEventListener('click', filterCustomers);
    searchInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            filterCustomers();
        }
    });
</script>
