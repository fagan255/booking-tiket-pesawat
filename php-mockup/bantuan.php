<?php
/**
 * bantuan.php - Halaman Bantuan
 */
$pageTitle = "Bantuan - SkyFare";

$faqList = [
    [
        "pertanyaan" => "Cara mencari penerbangan?",
        "jawaban" => "Gunakan halaman utama untuk memasukkan kota asal, tujuan, tanggal, jumlah penumpang, dan kelas.",
    ],
    [
        "pertanyaan" => "Bagaimana cara membayar?",
        "jawaban" => "Setelah mengisi data penumpang, pilih metode pembayaran pada halaman pembayaran kemudian klik Bayar Sekarang.",
    ],
    [
        "pertanyaan" => "Di mana melihat tiket saya?",
        "jawaban" => "Setelah pembayaran selesai, tiket akan tersimpan di halaman Booking Saya.",
    ],
];

include "includes/header.php";
?>

<div class="container page-shell">
    <div class="card page-card">
        <h2 class="page-title">Bantuan</h2>
        <p class="page-subtitle">Jawaban untuk pertanyaan umum terkait pemesanan tiket.</p>

        <div class="info-grid">
            <?php foreach ($faqList as $faq) : ?>
                <div class="info-card">
                    <h4><?php echo htmlspecialchars($faq['pertanyaan']); ?></h4>
                    <p><?php echo htmlspecialchars($faq['jawaban']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <p class="page-subtitle">Masih membutuhkan bantuan? Hubungi Customer Service SkyFare.</p>
    </div>
</div>

<?php include "includes/footer.php"; ?>
