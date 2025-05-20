<?php
    class PromotionController extends Controller {
        public function managePromotionPage() {
            $promotionModel = $this->model('Promotion');
            $promotions = $promotionModel->getAllPromotions();
            $this->view('manager/promotion_manage', ['promotion'=> $promotions]);
        } 

        public function toggleActive() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['promotionId'] ?? null;

                if (!$id) {
                    $_SESSION['message'] = ['type' => 'danger', 'text' => 'Thiếu mã khuyến mãi.'];
                    header('Location: /cnpm-final/PromotionController/managePromotionPage');
                    exit;
                }

                $model = $this->model('Promotion');
                $promotion = $model->getPromotionByPromotionId($id);

                if (!$promotion) {
                    $_SESSION['message'] = ['type' => 'danger', 'text' => 'Không tìm thấy khuyến mãi.'];
                    header('Location: /cnpm-final/PromotionController/managePromotionPage');
                    exit;
                }

                $newStatus = $promotion['active'] ? 0 : 1;
                $model->updatePromotionActive($id, $newStatus);

                $_SESSION['message'] = [
                    'type' => 'success',
                    'text' => $newStatus ? 'Đã kích hoạt khuyến mãi ' . $promotion['discountCode'] : 'Đã tạm ngừng khuyến mãi ' . $promotion['discountCode']
                ];

                header('Location: /cnpm-final/PromotionController/managePromotionPage');
                exit;
            }
        }

        public function delete() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['promotionId'] ?? null;

                if (!$id) {
                    $_SESSION['message'] = ['type' => 'danger', 'text' => 'Thiếu mã khuyến mãi để xoá.'];
                    header('Location: /cnpm-final/PromotionController/index');
                    exit;
                }

                $model = $this->model('Promotion');
                $success = $model->deletePromotion($id);

                if ($success) {
                    $_SESSION['message'] = ['type' => 'success', 'text' => 'Đã xoá khuyến mãi thành công.'];
                } else {
                    $_SESSION['message'] = ['type' => 'danger', 'text' => 'Không thể xoá khuyến mãi.'];
                }

                header('Location: /cnpm-final/PromotionController/managePromotionPage');
                exit;
            }
        }

        public function create() {
            $discountCode = $_POST['discountCode'] ?? '';
            $discountRate = (int) ($_POST['discountRate'] ?? 0);
            $startDateStr = $_POST['startDate'] ?? '';
            $endDateStr = $_POST['endDate'] ?? '';
            $active = (int) ($_POST['active'] ?? 0);


            $startDate = DateTime::createFromFormat('m/d/Y', $startDateStr);
            $startDateStr = $startDate->format('Y-m-d');
            $endDate = DateTime::createFromFormat('Y-m-d', $endDateStr);

            $promotionModel = $this->model('Promotion');

            $discountCodeCheck = $promotionModel->getPromotionByDiscountCode($discountCode);

            if ($discountCode && $discountCodeCheck) {
                $_SESSION['message'] = [
                    'type' => 'danger',
                    'text' => "Mã " . $discountCode . " đã tồn tại rồi, hãy xoá mã và thử lại."
                ];

                header('Location: /cnpm-final/PromotionController/managePromotionPage');
                exit;
            }
            
            if ($startDate > $endDate) {
                $_SESSION['message'] = [
                    'type' => 'danger',
                    'text' => 'Ngày bắt đầu không được sau ngày kết thúc.'
                ];

                header('Location: /cnpm-final/PromotionController/managePromotionPage');
                exit;
            }

            $promotionModel->createPromotion($discountCode, $discountRate, $startDateStr, $endDateStr, $active);

            $_SESSION['message'] = ['type' => 'success', 'text' => 'Đã thêm Khuyến mãi thành công! Mã khuyến mãi: ' . $discountCode];
            header('Location: /cnpm-final/PromotionController/managePromotionPage');
            exit;
        }

    }
?>