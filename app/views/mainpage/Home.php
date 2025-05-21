<?php
$role = $_SESSION['role'] ?? 'guest';
?>

<!-- Carousel -->
<div class="container-fluid px-5">
<div id="mainCarousel" class="carousel slide mb-4 mt-3" data-bs-ride="carousel">
  <div class="carousel-inner rounded-4 shadow">
    <div class="carousel-item active">
      <img src="/cnpm-final/public/images/carousel/banner1.jpg" class="d-block w-100" alt="Cửa hàng 1">
    </div>
    <div class="carousel-item">
      <img src="/cnpm-final/public/images/carousel/banner2.jpg" class="d-block w-100" alt="Cửa hàng 2">
    </div>
    <div class="carousel-item">
      <img src="/cnpm-final/public/images/carousel/banner3.webp" class="d-block w-100" alt="Cửa hàng 3">
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>
</div>
</div>

<?php
if ($role == 'guest') {
    $link = '/cnpm-final/HomeController/loginPage';
} else if ($role == 'customer') {
    $link = '/cnpm-final/InventoryController/customerMenuPage';
}
?>
<!-- Nút đặt món -->
<div class="text-center mb-5">
  <a href="<?= $link ?>" class="btn btn-primary btn-lg px-5 py-2 shadow">Đặt món ngay</a>
</div>


<div class="container mb-5">
    <h3 class="mb-4">Món nổi bật</h3>
    <div id="coffeeCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">

        <?php
        $items = $data['item'];
        $chunks = array_chunk($items, 5); // chia mỗi nhóm 5 món
        foreach ($chunks as $index => $chunk):
        ?>
        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
            <div class="d-flex justify-content-center gap-4">
            <?php foreach ($chunk as $item):
                $defaultImage = 'https://picsum.photos/300/300';
                $imageLink = !empty($item['image']) ? ('/cnpm-final/public/images/productCard/' . $item['image']) : $defaultImage;
            ?>
                <div class="card" style="width: 220px; height: 350px;">
                <img src="<?= $imageLink ?>" class="card-img-top" alt="<?= htmlspecialchars($item['name']) ?>" style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($item['name']) ?></h5>
                    <p class="card-text text-muted"><?= htmlspecialchars($item['note']) ?></p>
                    <p class="fw-bold"><?= number_format($item['price']) ?>đ</p>
                </div>
                </div>
            <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>

    </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#coffeeCarousel" data-bs-slide="prev" 
        style="width: 44px; height: 44px; border-radius: 50%; background: transparent; border: 2px solid black; top: 50%; transform: translateY(-50%);">
            <span class="carousel-control-prev-icon" aria-hidden="true" style="filter: invert(1) brightness(0) saturate(100%)"></span>
            <span class="visually-hidden">Previous</span>
        </button>

        <button class="carousel-control-next" type="button" data-bs-target="#coffeeCarousel" data-bs-slide="next" 
        style="width: 44px; height: 44px; border-radius: 50%; background: transparent; border: 2px solid black; top: 50%; transform: translateY(-50%);">
            <span class="carousel-control-next-icon" aria-hidden="true" style="filter: invert(1) brightness(0) saturate(100%)"></span>
            <span class="visually-hidden">Next</span>
        </button>

    </div>
</div>




<script>
  const container = document.getElementById('cardContainer');
  const scrollAmount = 220 + 16; // width of one card + gap

  document.getElementById('scrollLeft').addEventListener('click', () => {
    container.scrollBy({ left: -scrollAmount * 5, behavior: 'smooth' });
  });

  document.getElementById('scrollRight').addEventListener('click', () => {
    container.scrollBy({ left: scrollAmount * 5, behavior: 'smooth' });
  });
</script>
