<?php
    $role = $_SESSION['role'] ?? 'guest';
    if ($role == 'staff' || $role == 'manager') { ?>
        <a class="btn" href="/cnpm-final/InventoryController/displayAllItem">Storage</a>
        <a class="btn" href="/cnpm-final/OrderController/orderConfirmPage">Order Confirm</a>
    <?php } elseif ($role == 'customer') { ?>
        <a class="btn" href="/cnpm-final/InventoryController/customerMenuPage">Menu</a>
        <a class="btn" href="/cnpm-final/OrderController/customerTrackOrderPage">Track Order</a>
    <?php }
?>