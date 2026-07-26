<?php
$pageTitle = "Masuk - SkyFare";
include "includes/header.php";
?>

<div class="container auth-shell">
    <div class="card auth-card">
        <h2 class="auth-title">Masuk ke akun Anda</h2>
        <p class="auth-subtitle">Akses booking, tiket, dan promo terbaru dengan satu akun.</p>

        <form action="index.php" method="post">
            <div class="field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="nama@email.com" required>
            </div>
            <div class="field">
                <label for="password">Kata Sandi</label>
                <input type="password" id="password" name="password" placeholder="Masukkan kata sandi" required>
            </div>
            <button type="submit" class="btn">MASUK</button>
        </form>

        <p class="helper-text">
            Belum punya akun? <a href="register.php">Daftar sekarang</a>
        </p>
    </div>
</div>

<?php include "includes/footer.php"; ?>
