<?php
/**
 * promo.php - Halaman Promo
 */
$pageTitle = "Promo - SkyFare";

$promoList = [
    ["judul" => "Diskon Rute Domestik", "deskripsi" => "Hingga 25% untuk penerbangan dalam negeri."],
    ["judul" => "Cashback Bayar Online", "deskripsi" => "Dapatkan cashback hingga Rp 150.000."],
    ["judul" => "Pemesanan Grup", "deskripsi" => "Harga spesial untuk grup 4 orang ke atas."],
];

include "includes/header.php";
?>

<div class="container">
    <div class="box">
        <h3 class="box-title">Promo Pilihan</h3>
        <?php foreach ($promoList as $promo): ?>
            <div class="box" style="margin-bottom:15px;">
                <h4 style="margin-top:0;"><?php echo htmlspecialchars($promo['judul']); ?></h4>
                <p><?php echo htmlspecialchars($promo['deskripsi']); ?></p>
            </div>
        <?php endforeach; ?>
        <p>Temukan promo lain dengan mencari penerbangan di halaman utama.</p>
    </div>
</div>

<?php include "includes/footer.php"; ?>
