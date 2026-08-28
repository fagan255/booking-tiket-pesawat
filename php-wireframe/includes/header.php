<?php
/**
 * includes/header.php
 * Header partial - dipakai di semua halaman lewat include()
 * Menerima variabel opsional: $pageTitle
 */
require_once __DIR__ . '/init.php';

if (!isset($pageTitle)) {
    $pageTitle = "SkyFare - Booking Tiket Pesawat";
}
$loggedIn = skyfareIsLoggedIn();
$userName = skyfareGetCurrentUserName();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="assets/wireframe.css">
</head>
<body>

<div class="container">
    <header class="header">
        <div class="logo">LOGO</div>
        <nav>
            <a href="index.php">Home</a>
            <a href="booking.php">Booking Saya</a>
            <a href="promo.php">Promo</a>
            <a href="bantuan.php">Bantuan</a>
        </nav>
        <div class="actions">
            <?php if ($loggedIn): ?>
                <span style="font-size:13px; color:#555555; margin-right:10px;">Halo, <?php echo htmlspecialchars($userName); ?></span>
                <a href="logout.php" class="primary">Keluar</a>
            <?php else: ?>
                <a href="login.php">Masuk</a>
                <a href="register.php" class="primary">Daftar</a>
            <?php endif; ?>
        </div>
    </header>
</div>
