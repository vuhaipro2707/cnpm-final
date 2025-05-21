<?php
class Payment extends Database {

    public function getPaymentByOrderId($orderId) {
        $this->query("SELECT * FROM payment WHERE orderId = :orderId");
        $this->bind(':orderId', $orderId);
        return $this->single();
    }

    public function createPayment($orderId) {
        $this->query("INSERT INTO payment (orderId, status) VALUES (:orderId, 'pending')");
        $this->bind(':orderId', $orderId);
        $this->execute();
    }

    public function setPromotion($orderId, $promotionId) {
        $this->query("UPDATE payment SET promotionId = :promotionId WHERE orderId = :orderId");
        $this->bind(':promotionId', $promotionId);
        $this->bind(':orderId', $orderId);
        $this->execute();
    }

    public function setPointsApplied($orderId, $pointsApplied) {
        $this->query("UPDATE payment SET pointsApplied = :pointsApplied WHERE orderId = :orderId");
        $this->bind(':pointsApplied', $pointsApplied);
        $this->bind(':orderId', $orderId);
        $this->execute();
    }

    public function setPointsBonus($orderId, $pointsBonus) {
        $this->query("UPDATE payment SET pointsBonus = :pointsBonus WHERE orderId = :orderId");
        $this->bind(':pointsBonus', $pointsBonus);
        $this->bind(':orderId', $orderId);
        $this->execute();
    }

    public function setMethod($orderId, $method) {
        $this->query("UPDATE payment SET method = :method WHERE orderId = :orderId");
        $this->bind(':method', $method);
        $this->bind(':orderId', $orderId);
        $this->execute();
    }

    public function setTotalAmount($orderId, $total) {
        $this->query("UPDATE payment SET totalAmount = :totalAmount WHERE orderId = :orderId");
        $this->bind(':totalAmount', $total);
        $this->bind(':orderId', $orderId);
        $this->execute();
    }

    public function completePayment($orderId) {
        $this->query("UPDATE payment SET status = 'completed' WHERE orderId = :orderId");
        $this->bind(':orderId', $orderId);
        $this->execute();
    }

    public function getTotalRevenueToday() {
        $this->query("SELECT SUM(p.totalAmount) AS total_payment_today
                            FROM payment p
                            JOIN `order` o ON p.orderId = o.orderId
                            WHERE DATE(o.date) = CURDATE();
                            ");
        return $this->single();
    }
}
?>
