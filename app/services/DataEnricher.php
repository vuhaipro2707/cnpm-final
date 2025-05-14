<?php

class DataEnricher {
    protected $customerModel;
    protected $itemModel;
    protected $promotionModel;
    protected $accountModel;

    public function __construct($modelLoader) {
        // Truyền vào callable để load model khi cần (nếu bạn dùng kiểu `$this->model()` trong controller)
        $this->customerModel = $modelLoader('Customer');
        $this->itemModel = $modelLoader('Item');
        $this->promotionModel = $modelLoader('Promotion');
        $this->accountModel = $modelLoader('Account');
    }

    public function getAllOrdersInfo(array $orders): array {
        foreach ($orders as &$order) {
            $order['customer'] = $this->customerModel->getCustomerByCustomerId($order['customerId']);
            unset($order['customerId']);

            foreach ($order['itemsPerOrder'] as &$item) {
                $itemInfo = $this->itemModel->getItemById($item['itemId']);
                unset($item['itemId']);
                $item = array_merge($item, $itemInfo);
            }
            unset($item); // clear reference
        }
        unset($order); // clear reference

        return $orders;
    }

    public function getPaymentsPromotionInfo(array $payments): array {
        foreach ($payments as &$payment) {
            $payment['promotion'] = $this->promotionModel->getPromotionByPromotionId($payment['promotionId']);
            unset($payment['promotionId']);
        }
        unset($payment); // clear reference

        return $payments;
    }

    public function getFullStaffInfo(array $staffList): array {
        foreach ($staffList as &$staff) {
            $account = $this->accountModel->getAccountInfoByUsername($staff['username']);

            // Gộp thêm thông tin từ bảng Account
            $staff['role'] = $account['role'] ?? null;
            $staff['avatar'] = $account['avatar'] ?? null;
        }
        unset($staff); // Xoá reference

        return $staffList;
    }

}
