<?php
/**
 * bantuan.php - Halaman Bantuan
 */
$pageTitle = "Bantuan - SkyFare";
include "includes/header.php";
?>

<div class="container">
    <div class="box">
        <h3 class="box-title">Bantuan</h3>
        <p>Jika kamu memerlukan bantuan terkait pemesanan tiket, silakan lihat jawaban dari pertanyaan umum berikut ini.</p>
        <div class="box" style="margin-bottom:20px;">
            <strong>Cara mencari penerbangan?</strong>
            <p>Gunakan halaman utama untuk memasukkan kota asal, tujuan, tanggal, jumlah penumpang, dan kelas.</p>
        </div>
        <div class="box" style="margin-bottom:20px;">
            <strong>Bagaimana cara membayar?</strong>
            <p>Setelah mengisi data penumpang, pilih metode pembayaran pada halaman pembayaran kemudian klik Bayar Sekarang.</p>
        </div>
        <div class="box">
            <strong>Dimana melihat tiket saya?</strong>
            <p>Setelah pembayaran selesai, tiket akan tersimpan di halaman <a href="booking.php">Booking Saya</a>.</p>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>
