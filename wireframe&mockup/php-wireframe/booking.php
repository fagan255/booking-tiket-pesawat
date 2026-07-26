<?php
/**
 * booking.php - Halaman Booking Saya
 */
require_once __DIR__ . '/includes/init.php';

$pageTitle = "Booking Saya - SkyFare";
$bookings = skyfareGetBookings();

include "includes/header.php";
?>

<div class="container">
    <div class="box">
        <h3 class="box-title">Booking Saya</h3>

        <?php if (empty($bookings)): ?>
            <p>Tidak ada booking saat ini. <a href="index.php">Cari penerbangan sekarang</a> untuk memesan tiket.</p>
        <?php else: ?>
            <?php foreach ($bookings as $booking): ?>
                <div class="box" style="margin-bottom:20px;">
                    <h4 style="margin-top:0;">Kode Booking: <?php echo htmlspecialchars($booking['kode_booking'] ?? 'N/A'); ?></h4>
                    <div class="summary-row"><span>Penumpang</span><span><?php echo htmlspecialchars(trim(($booking['nama_depan'] ?? '') . ' ' . ($booking['nama_belakang'] ?? ''))); ?></span></div>
                    <div class="summary-row"><span>Rute</span><span><?php echo htmlspecialchars($booking['asal'] ?? 'CGK'); ?> → <?php echo htmlspecialchars($booking['tujuan'] ?? 'DPS'); ?></span></div>
                    <div class="summary-row"><span>Tanggal</span><span><?php echo htmlspecialchars($booking['tanggal'] ?? '12 Agu 2026'); ?></span></div>
                    <div class="summary-row"><span>Kelas</span><span><?php echo htmlspecialchars($booking['kelas'] ?? 'Ekonomi'); ?></span></div>
                    <div class="summary-row"><span>Metode Bayar</span><span><?php echo htmlspecialchars($booking['metode_bayar'] ?? '-'); ?></span></div>
                    <div class="summary-row"><span>Status</span><span>Berhasil</span></div>
                    <?php if (!empty($booking['email'])): ?><div class="summary-row"><span>Email</span><span><?php echo htmlspecialchars($booking['email']); ?></span></div><?php endif; ?>
                    <?php if (!empty($booking['telepon'])): ?><div class="summary-row"><span>Telepon</span><span><?php echo htmlspecialchars($booking['telepon']); ?></span></div><?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include "includes/footer.php"; ?>
