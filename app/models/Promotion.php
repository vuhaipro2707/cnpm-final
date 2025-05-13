<?php
class Promotion extends Database {

    // Hàm lấy thông tin khuyến mãi từ mã giảm giá
    public function getPromotionAvailableByDiscountCode($discountCode) {
        $this->query("SELECT * FROM promotion WHERE discountCode = :discountCode AND startDate <= CURDATE() AND endDate >= CURDATE()");
        $this->bind(':discountCode', $discountCode);
        
        return $this->single();
    }

    public function getPromotionByPromotionId($promotionId) {
        $this->query("SELECT * FROM promotion WHERE promotionId = :promotionId");
        $this->bind(':promotionId', $promotionId);
        
        return $this->single();
    }



    // // Hàm lấy tất cả các khuyến mãi đang có
    // public function getAll() {
    //     $this->query("SELECT * FROM promotions WHERE status = 'active'");
    //     return $this->resultSet();  // Lấy nhiều kết quả
    // }

    // // Hàm áp dụng khuyến mãi cho một đơn hàng
    // public function applyPromotion($orderId, $promotionId) {
    //     $this->query("UPDATE payment SET promotionId = :promotionId WHERE orderId = :orderId");
    //     $this->bind(':promotionId', $promotionId);
    //     $this->bind(':orderId', $orderId);
    //     return $this->execute();  // Cập nhật vào bảng payment
    // }

    // // Hàm tạo mới một khuyến mãi
    // public function createPromotion($code, $discount, $status = 'active') {
    //     $this->query("INSERT INTO promotions (code, discount, status) VALUES (:code, :discount, :status)");
    //     $this->bind(':code', $code);
    //     $this->bind(':discount', $discount);
    //     $this->bind(':status', $status);
    //     return $this->execute();  // Thêm mới vào bảng promotions
    // }

    // // Hàm vô hiệu hóa khuyến mãi
    // public function deactivatePromotion($promotionId) {
    //     $this->query("UPDATE promotions SET status = 'inactive' WHERE promotionId = :promotionId");
    //     $this->bind(':promotionId', $promotionId);
    //     return $this->execute();  // Cập nhật trạng thái khuyến mãi
    // }
}
?>
