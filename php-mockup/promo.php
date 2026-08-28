<?php
/**
 * promo.php - Halaman Promo
 */
$pageTitle = "Promo - SkyFare";

$promoList = [
    [
        "badge" => "HEMAT 25%",
        "judul" => "Diskon Rute Domestik",
        "deskripsi" => "Hingga 25% untuk penerbangan dalam negeri.",
        "gradient" => "gradient-1",
    ],
    [
        "badge" => "CASHBACK 150K",
        "judul" => "Cashback Bayar Online",
        "deskripsi" => "Dapatkan cashback hingga Rp 150.000.",
        "gradient" => "gradient-2",
    ],
    [
        "badge" => "GRUP 4+ ORANG",
        "judul" => "Pemesanan Grup",
        "deskripsi" => "Harga spesial untuk grup 4 orang ke atas.",
        "gradient" => "gradient-3",
    ],
];

include "includes/header.php";
?>

<div class="container page-shell">
    <div class="card page-card">
        <h2 class="page-title">Promo Pilihan</h2>
        <p class="page-subtitle">Penawaran terbaik untuk perjalanan Anda.</p>

        <div class="promo-grid">
            <?php foreach ($promoList as $promo) : ?>
                <div class="promo-card">
                    <div class="photo <?php echo htmlspecialchars($promo['gradient']); ?>">
                        <span class="badge"><?php echo htmlspecialchars($promo['badge']); ?></span>
                    </div>
                    <div class="body">
                        <h4><?php echo htmlspecialchars($promo['judul']); ?></h4>
                        <p><?php echo htmlspecialchars($promo['deskripsi']); ?></p>
                        <span class="link">Lihat Promo &rarr;</span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <p class="page-subtitle">Temukan promo lain dengan mencari penerbangan di halaman utama.</p>
    </div>
</div>

<?php include "includes/footer.php"; ?>
