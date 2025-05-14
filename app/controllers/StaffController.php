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
    }
?>