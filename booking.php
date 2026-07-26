<?php
session_start();
$pageTitle = "Booking Saya - SkyFare";
include "includes/header.php";

$bookings = isset($_SESSION['bookings']) ? $_SESSION['bookings'] : [];
$message = isset($_SESSION['booking_message']) ? $_SESSION['booking_message'] : '';
unset($_SESSION['booking_message']);
?>

<div class="container page-shell">
    <div class="card page-card">
        <h2 class="page-title">Booking Saya</h2>
        <p class="page-subtitle">Lihat riwayat penerbangan dan status tiket Anda di sini.</p>

        <?php if ($message !== '') : ?>
            <div style="background:var(--amber-soft); border:1px solid var(--amber); color:var(--navy); padding:12px 14px; border-radius:10px; margin-bottom:20px;">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($bookings)) : ?>
            <div class="info-grid">
                <?php foreach ($bookings as $booking) : ?>
                    <div class="info-card">
                        <h4><?php echo htmlspecialchars($booking['maskapai'] ?? 'Maskapai Tidak Diketahui'); ?></h4>
                        <p><strong><?php echo htmlspecialchars($booking['kode_booking'] ?? 'N/A'); ?></strong></p>
                        <p style="margin-top:8px;"><?php echo htmlspecialchars($booking['rute'] ?? 'Rute tidak tersedia'); ?></p>
                        <p style="margin-top:8px;">Tanggal: <?php echo htmlspecialchars($booking['tanggal'] ?? 'Tanggal tidak tersedia'); ?></p>
                        <p style="margin-top:8px;"><span class="info-pill"><?php echo htmlspecialchars($booking['status'] ?? 'Belum Dikonfirmasi'); ?></span></p>
                        <p style="margin-top:8px;">Kursi: <?php echo htmlspecialchars($booking['kursi'] ?? '-'); ?> • <?php echo htmlspecialchars($booking['kelas'] ?? '-'); ?></p>
                        <p style="margin-top:8px;">Penumpang: <?php echo htmlspecialchars($booking['nama_penumpang'] ?? 'Nama tidak tersedia'); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <div class="info-grid">
                <div class="info-card">
                    <h4>Belum ada booking</h4>
                    <p>Silakan lakukan pencarian dan pembayaran terlebih dahulu untuk melihat pesanan Anda di sini.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include "includes/footer.php"; ?>
