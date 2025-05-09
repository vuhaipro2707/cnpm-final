<!-- File: manager/track_inventory.php -->
<h1>Danh sách sản phẩm</h1>
<table border="1" cellpadding="10" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Tên sản phẩm</th>
        <th>Giá</th>
        <th>Số lượng còn</th>
        <th>Nhập số lượng cần xóa</th>
        <th>Thao tác</th>
    </tr>

    <?php foreach ($data as $item): ?>
        <tr>
            <form method="POST" action="/cnpm-final/InventoryController/deleteItem">
                <td><?= $item['id']; ?></td>
                <td><?= $item['name']; ?></td>
                <td><?= number_format($item['price'], 0, ',', '.') ?>₫</td>
                <td><?= $item['quantity']; ?></td>
                
                <td>
                    <input type="hidden" name="id" value="<?= $item['id']; ?>">
                    <input type="number" name="quantity" min="1" max="<?= $item['quantity']; ?>" required>
                </td>

                <td>
                    <button type="submit" onclick="return confirm('Xóa sản phẩm này?')">Xóa</button>
                </td>
            </form>
        </tr>
    <?php endforeach; ?>
</table>
