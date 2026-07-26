<?php
$pageTitle = "Daftar - SkyFare";
include "includes/header.php";
?>

<div class="container auth-shell">
    <div class="card auth-card">
        <h2 class="auth-title">Buat akun SkyFare</h2>
        <p class="auth-subtitle">Daftar sekarang untuk menikmati pencarian cepat dan promo eksklusif.</p>

        <form action="index.php" method="post">
            <div class="field">
                <label for="nama">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" placeholder="Contoh: Budi Santoso" required>
            </div>
            <div class="field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="nama@email.com" required>
            </div>
            <div class="field">
                <label for="password">Kata Sandi</label>
                <input type="password" id="password" name="password" placeholder="Buat kata sandi" required>
            </div>
            <button type="submit" class="btn">DAFTAR</button>
        </form>

        <p class="helper-text">
            Sudah punya akun? <a href="login.php">Masuk di sini</a>
        </p>
    </div>
</div>

<?php include "includes/footer.php"; ?>
