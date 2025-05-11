<?php
    $role = $_SESSION['role'] ?? 'guest';
    if ($role == 'staff' || $role == 'manager') { ?>
        <a class="btn" href="/cnpm-final/InventoryController/displayAllItem">Menu</a>
    <?php }
?>