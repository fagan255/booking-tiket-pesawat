<?php
/**
 * index.php - Halaman Home / Cari Penerbangan (versi mockup berwarna)
 */
$pageTitle = "Cari Penerbangan - SkyFare";
require_once __DIR__ . '/includes/init.php';

// Struktur data: array asosiatif untuk promo, dirender lewat loop + styling gradient berbeda
$promoStatement = $pdo->query("SELECT title AS judul, description AS deskripsi FROM promos WHERE is_active = 1 AND (valid_until IS NULL OR valid_until >= CURDATE()) ORDER BY id DESC");
$promoList = [];
foreach ($promoStatement->fetchAll() as $promo) {
    $promoList[] = $promo + ['badge' => 'PROMO', 'gradient' => 'gradient-1'];
}

$kelasOptions = ["Ekonomi", "Bisnis", "First Class"];

include "includes/header.php";
?>

<div class="hero">
    <h1>Terbang ke Mana Hari Ini?</h1>
    <p>Bandingkan harga dari lebih dari 30 maskapai dalam satu pencarian</p>
</div>

<div class="container">

    <!-- Search Card -->
    <form action="search-results.php" method="get" class="card search-card">
        <div class="tabs">
            <div class="tab active">Sekali Jalan</div>
            <div class="tab">Pulang Pergi</div>
        </div>

        <div class="row">
            <div class="field">
                <label for="dari">Dari</label>
                <input type="text" id="dari" name="dari" value="Jakarta (CGK)">
            </div>
            <div class="field">
                <label for="ke">Ke</label>
                <input type="text" id="ke" name="ke" value="Denpasar (DPS)">
            </div>
            <div class="field">
                <label for="tanggal">Tanggal Berangkat</label>
                <input type="date" id="tanggal" name="tanggal" value="2026-08-12">
            </div>
        </div>

        <div class="row">
            <div class="field">
                <label for="penumpang">Penumpang</label>
                <input type="number" id="penumpang" name="penumpang" value="1" min="1">
            </div>
            <div class="field">
                <label for="kelas">Kelas</label>
                <select id="kelas" name="kelas">
                    <?php
                    foreach ($kelasOptions as $kelas) {
                        echo '<option value="' . htmlspecialchars($kelas) . '">' . htmlspecialchars($kelas) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="field" style="display:flex; align-items:flex-end;">
                <button type="submit" class="btn" style="width:100%;">CARI TIKET</button>
            </div>
        </div>
    </form>

    <!-- Promo Section -->
    <h2 style="margin-bottom:4px;">Promo Pilihan</h2>
    <p style="color:var(--slate); margin-top:0;">Penawaran terbaik minggu ini, khusus untukmu</p>

    <div class="promo-grid">
        <?php
        foreach ($promoList as $promo) {
            echo '<div class="promo-card">';
            echo '  <div class="photo ' . $promo["gradient"] . '"><span class="badge">' . htmlspecialchars($promo["badge"]) . '</span></div>';
            echo '  <div class="body">';
            echo '    <h4>' . htmlspecialchars($promo["judul"]) . '</h4>';
            echo '    <p>' . htmlspecialchars($promo["deskripsi"]) . '</p>';
            echo '    <span class="link">Lihat Promo &rarr;</span>';
            echo '  </div>';
            echo '</div>';
        }
        ?>
    </div>

</div>

<?php include "includes/footer.php"; ?>
