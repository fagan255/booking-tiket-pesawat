<?php
/**
 * index.php - Halaman Home / Cari Penerbangan
 */
$pageTitle = "Cari Penerbangan - SkyFare";

// Struktur data: array asosiatif untuk data promo (ditampilkan lewat loop)
$promoList = [
    ["judul" => "Diskon Rute Domestik",     "deskripsi" => "Berlaku s/d 31 Agustus 2026"],
    ["judul" => "Cashback 100K",            "deskripsi" => "Min. transaksi Rp500.000"],
    ["judul" => "Rute Baru Jakarta-Bali",   "deskripsi" => "Mulai Rp 850.000"],
];

// Data kelas penerbangan untuk dropdown (struktur data: array sederhana)
$kelasOptions = ["Ekonomi", "Bisnis", "First Class"];

include "includes/header.php";
?>

<div class="container">

    <!-- Hero -->
    <div style="text-align:center; padding: 40px 0;">
        <h1>Cari &amp; Pesan Tiket Pesawat</h1>
        <p style="color:#777777;">Bandingkan harga dari berbagai maskapai</p>
    </div>

    <!-- Form Pencarian -->
    <form action="search-results.php" method="get" class="box" style="margin-bottom:50px;">
        <h3 class="box-title">Form Pencarian</h3>

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
                    // Loop untuk generate <option> dari array $kelasOptions
                    foreach ($kelasOptions as $kelas) {
                        echo '<option value="' . htmlspecialchars($kelas) . '">' . htmlspecialchars($kelas) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="field" style="display:flex; align-items:flex-end;">
                <button type="submit" class="btn" style="width:100%;">CARI</button>
            </div>
        </div>
    </form>

    <!-- Promo Section -->
    <h2>Promo Pilihan</h2>
    <div class="row" style="margin-bottom:50px;">
        <?php
        // Loop untuk menampilkan setiap item promo dari array $promoList
        foreach ($promoList as $promo) {
            echo '<div class="box">';
            echo '  <p><strong>' . htmlspecialchars($promo["judul"]) . '</strong></p>';
            echo '  <p style="color:#888888; font-size:13px;">' . htmlspecialchars($promo["deskripsi"]) . '</p>';
            echo '</div>';
        }
        ?>
    </div>

</div>

<?php include "includes/footer.php"; ?>
