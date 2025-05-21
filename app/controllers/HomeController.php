<?php
    class HomeController extends Controller {
        public function index() {
            $itemModel = $this->model('Item');
            $paymentModel = $this->model('Payment');
            $staffModel = $this->model('Staff');
            $orderModel = $this->model('Order');
            $accountModel = $this->model('Account');

            $bestItemSale = $itemModel->getMostPopularItem(3);
            $randomItem = $itemModel->getRandomItem(15);

            $totalRevenueToday = $paymentModel->getTotalRevenueToday();

            $bestStaff = $staffModel->getbestStaff();

            $ordersRaw = $orderModel->countTotalOrderByStatus();

            $accounts = $accountModel->getStaffAndAdminAccount();

            $ordersByStatus = [];

            foreach ($ordersRaw as $row) {
                $ordersByStatus[$row['status']] = (int)$row['totalOrders'];
            }

            $this->view('mainpage/Home', ['item'=>$randomItem, 'bestItemSale'=>$bestItemSale, 'totalRevenueToday'=>$totalRevenueToday, 'bestStaff'=>$bestStaff, 'ordersByStatus'=>$ordersByStatus, 'accounts'=>$accounts]);
        }

        public function loginPage() {
            $this->view('mainpage/login');
        }

        public function registerPage() {
            $this->view('mainpage/register');
        }

        public function staffRegisterPage() {
            if ($_SESSION['role'] == 'manager') {
                $this->view('mainpage/staff_register');
            } else {
                $this->view('mainpage/Home');
            }
        }

        public function changePasswordPage() {
            $this->view('mainpage/change_password');
        }
    }
?>