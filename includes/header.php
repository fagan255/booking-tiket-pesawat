<?php
/**
 * includes/header.php
 * Header partial - dipakai di semua halaman lewat include()
 */
if (!isset($pageTitle)) {
    $pageTitle = "SkyFare - Booking Tiket Pesawat";
}
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="assets/mockup.css">
</head>
<body>

<header class="header">
    <div class="container">
        <div class="logo">SKY<span>FARE</span></div>
        <nav>
            <a href="index.php" class="<?php echo $currentPage === 'index.php' ? 'active' : ''; ?>">Beranda</a>
            <a href="booking.php" class="<?php echo $currentPage === 'booking.php' ? 'active' : ''; ?>">Booking Saya</a>
            <a href="#">Promo</a>
            <a href="#">Bantuan</a>
        </nav>
        <div class="actions">
            <a href="login.php" class="ghost">Masuk</a>
            <a href="register.php" class="primary">Daftar</a>
        </div>
    </div>
</header>
