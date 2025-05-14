<?php
    class StaffController extends Controller {
        public function managerStaffManagePage() {
            if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'manager') {
                header('Location: /cnpm-final/HomeController/index');
                exit;
            }

            $staffModel = $this->model('Staff');
            $staffListRaw = $staffModel->getAllStaff();
            $enricher = new DataEnricher([$this, 'model']);
            $staffList = $enricher->getFullStaffInfo($staffListRaw);

            $this->view('manager/staff_manage', ['staffList' => $staffList]);
        }

        public function deleteStaff() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $staffId = $_POST['staffId'];

                $staffModel = $this->model('Staff');
                $staff = $staffModel->getStaffByStaffId($staffId);

                if ($staff) {
                    $accountModel = $this->model('Account');
                    $staffModel->deleteStaff($staffId);
                    $accountModel->deleteAccount($staff['username']);
                }
                $_SESSION['success'] = "Xóa nhân viên thành công!";
                header('Location: /cnpm-final/StaffController/managerStaffManagePage');
                exit;
            }
        }


        public function updateStaff() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Lấy dữ liệu từ form
                $staffId = $_POST['staffId'];
                $name = $_POST['name'];
                $position = $_POST['position'];
                $phone = $_POST['phone'];  // Số điện thoại
                $salary = str_replace('.', '', $_POST['salary']);  // Lương
                $isManager = isset($_POST['isManager']) ? 1 : 0;

                // Kiểm tra dữ liệu hợp lệ (có thể thêm các bước kiểm tra, ví dụ: kiểm tra định dạng số điện thoại)
                if (empty($staffId) || empty($name) || empty($position) || empty($phone) || empty($salary)) {
                    $_SESSION['error'] = "Vui lòng điền đầy đủ thông tin!";
                    header('Location: /cnpm-final/StaffController/managerStaffManagePage');
                    exit;
                }

                // Đảm bảo rằng lương là số hợp lệ
                if (!is_numeric($salary)) {
                    $_SESSION['error'] = "Lương phải là một số hợp lệ!";
                    header('Location: /cnpm-final/StaffController/managerStaffManagePage');
                    exit;
                }

                $staffModel = $this->model('Staff');

                $staffModel->updateStaffInfoByStaffId($staffId, $name, $position, $phone, $salary, $isManager);

                $_SESSION['success'] = "Cập nhật thông tin nhân viên thành công!";
                header('Location: /cnpm-final/StaffController/managerStaffManagePage');
                exit;
            }
        }


    }
?>