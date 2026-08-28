<?php
/**
 * search-results.php - Halaman Hasil Pencarian Penerbangan (versi mockup)
 */
$pageTitle = "Hasil Pencarian - SkyFare";
require_once __DIR__ . '/includes/init.php';

$dari    = isset($_GET['dari']) ? $_GET['dari'] : "Jakarta (CGK)";
$ke      = isset($_GET['ke']) ? $_GET['ke'] : "Denpasar (DPS)";
$tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : "2026-08-12";
$kelas = $_GET['kelas'] ?? 'Ekonomi';
$originCode = preg_match('/\(([A-Z]{3})\)/', $dari, $originMatch) ? $originMatch[1] : 'CGK';
$destinationCode = preg_match('/\(([A-Z]{3})\)/', $ke, $destinationMatch) ? $destinationMatch[1] : 'DPS';

/**
 * Struktur data: array multidimensi daftar penerbangan.
 */
$statement = $pdo->prepare(
    "SELECT id, airline AS maskapai, flight_code AS kode, 'Penerbangan Langsung' AS tipe,
            TIME_FORMAT(departure_time, '%H:%i') AS berangkat,
            TIME_FORMAT(arrival_time, '%H:%i') AS tiba,
            CONCAT(FLOOR(duration_minutes / 60), 'j ', MOD(duration_minutes, 60), 'm') AS durasi,
            base_price + tax AS harga, origin_code, destination_code
     FROM flights
     WHERE origin_code = :origin AND destination_code = :destination
       AND flight_date = :flight_date AND cabin_class = :cabin_class AND status = 'Tersedia'
     ORDER BY departure_time"
);
$statement->execute(['origin' => $originCode, 'destination' => $destinationCode, 'flight_date' => $tanggal, 'cabin_class' => $kelas]);
$flightList = $statement->fetchAll();

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
