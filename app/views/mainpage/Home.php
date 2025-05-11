<?php
    $role = $_SESSION['role'] ?? 'guest';
    if ($role == 'staff' || $role == 'manager') { ?>
        <a class="btn" href="/cnpm-final/InventoryController/displayAllItem">Menu</a>
        <a class="btn" href="/cnpm-final/OrderController/orderConfirmPage">Order Confirm</a>
    <?php }
?>