<?php
/**
 * search-results.php - Halaman Hasil Pencarian Penerbangan (versi mockup)
 */
$pageTitle = "Hasil Pencarian - SkyFare";

$dari    = isset($_GET['dari']) ? $_GET['dari'] : "Jakarta (CGK)";
$ke      = isset($_GET['ke']) ? $_GET['ke'] : "Denpasar (DPS)";
$tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : "2026-08-12";

/**
 * Struktur data: array multidimensi daftar penerbangan.
 */
$flightList = [
    [
        "maskapai" => "Maskapai A", "kode" => "A", "tipe" => "Penerbangan Langsung",
        "berangkat" => "08:00", "tiba" => "09:45", "durasi" => "1j 45m", "harga" => 850000,
    ],
    [
        "maskapai" => "Maskapai B", "kode" => "B", "tipe" => "Penerbangan Langsung",
        "berangkat" => "10:30", "tiba" => "12:20", "durasi" => "1j 50m", "harga" => 920000,
    ],
    [
        "maskapai" => "Maskapai C", "kode" => "C", "tipe" => "1x Transit",
        "berangkat" => "14:00", "tiba" => "17:10", "durasi" => "3j 10m", "harga" => 700000,
    ],
];

function formatRupiah(int $angka): string
{
    return "Rp " . number_format($angka, 0, ",", ".");
}

include "includes/header.php";
?>

<div class="summary-bar">
    <div class="container">
        <div>
            <span class="code-chip">CGK</span> &rarr;
            <span class="code-chip">DPS</span>
            <?php echo htmlspecialchars($tanggal); ?> | 1 Dewasa | Ekonomi
        </div>
        <a href="index.php" class="btn btn-outline">Ubah Pencarian</a>
    </div>
</div>

<div class="container">
    <div class="layout-2col">

        <!-- Sidebar Filter -->
        <div class="side">
            <div class="filter-box">
                <h3 style="margin-top:0;">Filter</h3>

                <div class="filter-group">
                    <h4>Maskapai</h4>
                    <?php
                    $daftarMaskapai = array_unique(array_column($flightList, "maskapai"));
                    foreach ($daftarMaskapai as $nama) {
                        echo '<label><input type="checkbox"> ' . htmlspecialchars($nama) . '</label>';
                    }
                    ?>
                </div>

                <div class="filter-group">
                    <h4>Waktu Berangkat</h4>
                    <label><input type="checkbox"> Pagi (00-12)</label>
                    <label><input type="checkbox"> Siang (12-18)</label>
                    <label><input type="checkbox"> Malam (18-24)</label>
                </div>
            </div>
        </div>

        <!-- Daftar Penerbangan -->
        <div class="main">
            <p style="font-weight:700;"><?php echo count($flightList); ?> penerbangan ditemukan</p>

            <?php
            foreach ($flightList as $flight) {
                $inisial = htmlspecialchars($flight["kode"]);
                echo '<div class="flight-card">';

                echo '  <div class="airline-info">';
                echo '    <div class="logo-circle">' . $inisial . '</div>';
                echo '    <div>';
                echo '      <div style="font-weight:700;">' . htmlspecialchars($flight["maskapai"]) . ' — Ekonomi</div>';
                echo '      <div class="sub">' . htmlspecialchars($flight["tipe"]) . '</div>';
                echo '    </div>';
                echo '  </div>';

                echo '  <div>';
                echo '    <div class="time-chip">' . htmlspecialchars($flight["berangkat"]) . '</div>';
                echo '    <div class="time-code">CGK</div>';
                echo '  </div>';

                echo '  <div class="duration">' . htmlspecialchars($flight["durasi"]) . '</div>';

                echo '  <div>';
                echo '    <div class="time-chip">' . htmlspecialchars($flight["tiba"]) . '</div>';
                echo '    <div class="time-code">DPS</div>';
                echo '  </div>';

                echo '  <div class="price">';
                echo '    <div class="amount">' . formatRupiah($flight["harga"]) . '</div>';
                echo '    <div class="unit">/orang</div>';
                echo '    <a href="flight-detail.php?kode=' . urlencode($flight["kode"]) . '" class="btn" style="margin-top:10px; display:inline-block;">Pilih</a>';
                echo '  </div>';

                echo '</div>';
            }
            ?>
        </div>

    </div>
</div>

<?php include "includes/footer.php"; ?>
