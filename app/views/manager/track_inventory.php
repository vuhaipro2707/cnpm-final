
<!-- File: manager/track_inventory.php -->
<h1>Danh sách sản phẩm</h1>
<table>
    <tr>
        <th>ID</th>
        <th>Tên sản phẩm</th>
        <th>Giá</th>
        <th>Số lượng</th>
    </tr>
    <!-- Duyệt qua mảng $data để hiển thị danh sách các sản phẩm -->
    <?php foreach ($data as $item): ?>
        <tr>
            <td><?php echo $item['id']; ?></td>
            <td><?php echo $item['name']; ?></td>
            <td><?php echo $item['price']; ?></td>
            <td><?php echo $item['quantity']; ?></td>
        </tr>
    <?php endforeach; ?>
</table>