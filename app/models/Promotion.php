<?php
    class Promotion extends Database {

        // Hàm lấy thông tin khuyến mãi từ mã giảm giá
        public function getPromotionAvailableByDiscountCode($discountCode) {
            $this->query("SELECT * FROM promotion WHERE discountCode = :discountCode AND startDate <= CURDATE() AND endDate >= CURDATE() AND active = 1");
            $this->bind(':discountCode', $discountCode);
            
            return $this->single();
        }

        public function getPromotionByDiscountCode($discountCode) {
            $this->query("SELECT * FROM promotion WHERE discountCode = :discountCode");
            $this->bind(':discountCode', $discountCode);
            
            return $this->single();
        }

        public function getPromotionAvailableByPromotionId($promotionId) {
            $this->query("SELECT * FROM promotion WHERE promotionId = :promotionId AND startDate <= CURDATE() AND endDate >= CURDATE() AND active = 1");
            $this->bind(':promotionId', $promotionId);
            
            return $this->single();
        }

        public function getPromotionByPromotionId($promotionId) {
            $this->query("SELECT * FROM promotion WHERE promotionId = :promotionId");
            $this->bind(':promotionId', $promotionId);
            
            return $this->single();
        }

        public function getAllPromotions() {
            $this->query("SELECT * FROM promotion ORDER BY endDate DESC");
            return $this->resultSet();
        }

        public function updatePromotionActive($promotionId, $active) {
            $this->query("UPDATE promotion SET active = :active WHERE promotionId = :promotionId");
            $this->bind(':promotionId', $promotionId);
            $this->bind(':active', $active);
            return $this->execute();
        }

        public function deletePromotion($promotionId) {
            $this->query("DELETE FROM promotion WHERE promotionId = :promotionId");
            $this->bind(':promotionId', $promotionId);
            return $this->execute();
        }

        public function createPromotion($discountCode, $discountRate, $startDate, $endDate, $active) {
            $this->query("INSERT INTO promotion (discountCode, discountRate, startDate, endDate, active) 
                        VALUES (:discountCode, :discountRate, :startDate, :endDate, :active)");

            $this->bind(':discountCode', $discountCode);
            $this->bind(':discountRate', $discountRate);
            $this->bind(':startDate', $startDate);
            $this->bind(':endDate', $endDate);
            $this->bind(':active', $active);

            return $this->execute();
        }

    }
?>
