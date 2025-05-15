<style>
    body {
        background-color: #f8f9fa;
    }
    .success-container {
        margin-top: 100px;
    }
    .countdown {
        font-size: 1.2rem;
    }
</style>
<meta http-equiv="refresh" content="5;url=/cnpm-final/HomeController/index"> <!-- Tự động chuyển sau 5 giây -->

<div class="container text-center success-container">
    <div class="alert alert-success p-5 shadow rounded">
        <h1 class="mb-3">🎉 Thanh toán thành công!</h1>
        <p class="mb-2">Cảm ơn bạn đã sử dụng dịch vụ tại quán của chúng tôi. Mã đơn hàng: <?= $data['orderId'] ?></p>
        <p class="countdown">Bạn sẽ được chuyển về trang chủ sau <span id="countdown">5</span> giây...</p>
        <p class="mt-3">Nếu không được chuyển tự động, <a href="/cnpm-final/HomeController/index" class="text-decoration-underline">nhấn vào đây</a>.</p>
    </div>
</div>

<script>
    let seconds = 5;
    const countdownEl = document.getElementById("countdown");
    const interval = setInterval(() => {
        seconds--;
        countdownEl.textContent = seconds;
        if (seconds <= 0) clearInterval(interval);
    }, 1000);
</script>

