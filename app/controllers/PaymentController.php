<?php
    class PaymentController extends Controller {
        public function customerChoosePaymentPage($orderIdInput = null) {
            $orderId = null;

            if (!empty($orderIdInput)) {
                $orderId = $orderIdInput;
            }

            if($_SERVER['REQUEST_METHOD'] === "POST") {
                $orderId = $_POST['orderId'];
            }

            $orderModel = $this->model('Order');

            
            $orderRaw = $orderModel->getOrderById($orderId);
            $paymentRaw = $this->initPayment($orderId);

            $enricher = new DataEnricher([$this, 'model']);
            $order = $enricher->getAllOrdersInfo([$orderRaw])[0]; // bọc lại thành mảng vì hàm nhận mảng order
            if ($paymentRaw['promotionId']) {
                $payment = $enricher->getPaymentsPromotionInfo([$paymentRaw])[0];
            } else {
                $payment = $paymentRaw;
            }
            
            $this->view('customer/choose_payment_method', ['order'=> $order, 'payment'=> $payment]);
        }

        private function initPayment($orderId) {
            $paymentModel = $this->model('Payment');
            $existingPayment = $paymentModel->getPaymentByOrderId($orderId);

            if (!$existingPayment) {
                $paymentModel->createPayment($orderId);
                $existingPayment = $paymentModel->getPaymentByOrderId($orderId);
            }
            return $existingPayment;
        }

        
        public function applyPromotion() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $orderId = $_POST['orderId'] ?? null;
                $discountCode = $_POST['discountCode'] ?? null;

                if (!$orderId || !$discountCode) {
                    $_SESSION['payment']['promoError'] = 'Thiếu thông tin đơn hàng hoặc mã khuyến mãi.';
                    header("Location: /cnpm-final/PaymentController/customerChoosePaymentPage/" . $orderId);
                    exit;
                }

                $promotionModel = $this->model('Promotion');
                $promotion = $promotionModel->getPromotionAvailableByDiscountCode($discountCode);

                if ($promotion) {
                    $paymentModel = $this->model('Payment');
                    $paymentModel->setPromotion($orderId, $promotion['promotionId']); 
                    if ($paymentModel->getPaymentByOrderId($orderId)['pointsApplied'] !== NULL) {
                        $paymentModel->setPointsApplied($orderId, NULL);
                    }
                } else {
                    $_SESSION['payment']['promoError'] = 'Mã khuyến mãi không hợp lệ.';
                }

                // Quay lại trang thanh toán
                header("Location: /cnpm-final/PaymentController/customerChoosePaymentPage/" . $orderId);
                exit;
            }
        }

        public function removePromotion() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $orderId = $_POST['orderId'] ?? null;

                if (!$orderId) {
                    $_SESSION['payment']['promoError'] = 'Thiếu thông tin đơn hàng.';
                    header("Location: /cnpm-final/PaymentController/customerChoosePaymentPage/");
                    exit;
                }

                $paymentModel = $this->model('Payment');
                $paymentModel->setPromotion($orderId, null);

                header("Location: /cnpm-final/PaymentController/customerChoosePaymentPage/" . $orderId);
                exit;
            }
        }


        public function applyPoints() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $orderId = $_POST['orderId'];
                $points = $_POST['points'];
                $customerPoints = $_POST['customerPoints'];
                $totalAfterDiscount = $_POST['totalAfterDiscount'];

                if ($customerPoints < $points) {
                    $_SESSION['payment']['promoError'] = 'Bạn không đủ điểm tích lũy, hãy thử lại.';
                    header("Location: /cnpm-final/PaymentController/customerChoosePaymentPage/" . $orderId);
                    exit;
                }

                if ($totalAfterDiscount < $points * 1000) {
                    $_SESSION['payment']['promoError'] = 'Số điểm tích lũy sử dụng đã vượt quá số tiền, hãy thử lại.';
                    header("Location: /cnpm-final/PaymentController/customerChoosePaymentPage/" . $orderId);
                    exit;
                }

                $paymentModel = $this->model('Payment');
                $paymentModel->setPointsApplied($orderId, $points);

                // Quay lại trang thanh toán
                header("Location: /cnpm-final/PaymentController/customerChoosePaymentPage/" . $orderId);
                exit;
            }
        }

        public function removeApplyPoints() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $orderId = $_POST['orderId'] ?? null;

                if (!$orderId) {
                    $_SESSION['payment']['promoError'] = 'Thiếu thông tin đơn hàng.';
                    header("Location: /cnpm-final/PaymentController/customerChoosePaymentPage/");
                    exit;
                }

                $paymentModel = $this->model('Payment');
                $paymentModel->setPointsApplied($orderId, null);

                header("Location: /cnpm-final/PaymentController/customerChoosePaymentPage/" . $orderId);
                exit;
            }
        }

        public function confirmPayment() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $orderId = $_POST['orderId'] ?? null;
                $method = $_POST['method'] ?? null;
                $totalAmount = $_POST['total'] ?? null;
                $pointsBonus = $_POST['pointsBonus'] ?? null;
                $pointsLefts = $_POST['pointsLefts'] ?? null;

                $paymentModel = $this->model('Payment');
                $orderModel = $this->model('Order');
                $customerModel = $this->model('customer');


                $paymentModel->setMethod($orderId, $method);
                $paymentModel->setTotalAmount($orderId, $totalAmount);
                $paymentModel->setPointsBonus($orderId, $pointsBonus);
                $paymentModel->completePayment($orderId);

                $customer = $customerModel->getCustomerByOrderId($orderId);

                $customerModel->updateCustomerPoints($customer['customerId'], $pointsLefts + $pointsBonus);

                $orderModel->confirmOrder($orderId, 'paid');

                $this->view("customer/success_payment", ["orderId"=> $orderId]);
                exit;
            }
        }
    }
?>