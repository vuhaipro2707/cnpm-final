<?php
    class PaymentController extends Controller {
        public function customerChoosePaymentPage() {
            if($_SERVER['REQUEST_METHOD'] = "POST") {
                $orderId = $_POST['orderId'];
                $orderModel = $this->model('Order');
                $order = $orderModel->getOrderById($orderId);
                $this->view('customer/choose_payment_method', ['order'=> $order]);
            }
        }
    }
?>