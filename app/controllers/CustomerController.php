<?php
    class CustomerController extends Controller {
        public function staffCustomerManagePage() {
            $customerModel = $this->model('Customer');
            $customer = $customerModel->getAllCustomers();
            $this->view('staff/customer_manage', ['customer' => $customer]);
        }

        public function updateCustomer() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Lấy dữ liệu từ form
                $customerId = $_POST['customerId'];
                $name = $_POST['name'];
                $phone = $_POST['phone'];  // Số điện thoại

                // Kiểm tra dữ liệu hợp lệ (có thể thêm các bước kiểm tra, ví dụ: kiểm tra định dạng số điện thoại)
                if (empty($customerId) || empty($name) || empty($phone)) {
                    $_SESSION['error'] = "Vui lòng điền đầy đủ thông tin!";
                    header('Location: /cnpm-final/CustomerController/staffCustomerManagePage');
                    exit;
                }

                $customerModel = $this->model('Customer');

                $customerModel->updateCustomerName($customerId, $name);
                $customerModel->updateCustomerPhone($customerId, $phone);

                $_SESSION['success'] = "Cập nhật thông tin khách hàng thành công!";
                header('Location: /cnpm-final/CustomerController/staffCustomerManagePage');
                exit;
            }
        }

    }
?>