<?php
/**
 * search-results.php - Halaman Hasil Pencarian Penerbangan
 */
$pageTitle = "Hasil Pencarian - SkyFare";
require_once __DIR__ . '/includes/init.php';

// Ambil parameter pencarian dari form (GET) dengan nilai default jika kosong
$dari     = isset($_GET['dari']) ? $_GET['dari'] : "Jakarta (CGK)";
$ke       = isset($_GET['ke']) ? $_GET['ke'] : "Denpasar (DPS)";
$tanggal  = isset($_GET['tanggal']) ? $_GET['tanggal'] : "2026-08-12";
$kelas = $_GET['kelas'] ?? 'Ekonomi';
$originCode = preg_match('/\(([A-Z]{3})\)/', $dari, $originMatch) ? $originMatch[1] : 'CGK';
$destinationCode = preg_match('/\(([A-Z]{3})\)/', $ke, $destinationMatch) ? $destinationMatch[1] : 'DPS';

/**
 * Struktur data: array multidimensi untuk daftar penerbangan.
 * Setiap penerbangan disimpan sebagai array asosiatif.
 * Data ini nantinya bisa diganti dengan hasil query database.
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

/**
 * Fungsi untuk format angka jadi Rupiah.
 * Contoh pemrograman terstruktur: logika dipisah jadi fungsi tersendiri.
 */
function formatRupiah(int $angka): string
{
    return "Rp " . number_format($angka, 0, ",", ".");
}

include "includes/header.php";
?>

<div class="container">

    <!-- Ringkasan pencarian -->
    <div class="box" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
        <div>
            <?php echo htmlspecialchars($dari) . " &rarr; " . htmlspecialchars($ke); ?>
            | <?php echo htmlspecialchars($tanggal); ?> | 1 Dewasa | Ekonomi
        </div>
        <a href="index.php" class="btn btn-outline">Ubah Pencarian</a>
    </div>

    <div class="layout-2col">

        <!-- Sidebar Filter -->
        <div class="side">
            <div class="filter-box">
                <h3>Filter</h3>

                <div class="filter-group">
                    <h4>Maskapai</h4>
                    <?php
                    // Loop untuk generate checkbox filter maskapai secara dinamis
                    // dari data $flightList (tidak hardcode manual)
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
            <p><strong><?php echo count($flightList); ?> penerbangan ditemukan</strong></p>

            <?php
            // Loop utama: render satu flight-card untuk setiap data di $flightList
            foreach ($flightList as $flight) {
                echo '<div class="flight-card">';

                echo '  <div class="airline-info">';
                echo '    <div class="logo-box">' . htmlspecialchars($flight["kode"]) . '</div>';
                echo '    <div>';
                echo '      <div>' . htmlspecialchars($flight["maskapai"]) . ' — Ekonomi</div>';
                echo '      <div style="font-size:11px;color:#888888;">' . htmlspecialchars($flight["tipe"]) . '</div>';
                echo '    </div>';
                echo '  </div>';

                echo '  <div class="time-block">';
                echo '    <div class="time">' . htmlspecialchars($flight["berangkat"]) . '</div>';
                echo '    <div class="code">CGK</div>';
                echo '  </div>';

                echo '  <div class="duration">' . htmlspecialchars($flight["durasi"]) . '</div>';

                echo '  <div class="time-block">';
                echo '    <div class="time">' . htmlspecialchars($flight["tiba"]) . '</div>';
                echo '    <div class="code">DPS</div>';
                echo '  </div>';

                echo '  <div class="price">';
                echo '    <div class="amount">' . formatRupiah($flight["harga"]) . '</div>';
                echo '    <a href="flight-detail.php?kode=' . urlencode($flight["kode"]) . '" class="btn" style="margin-top:10px; display:inline-block;">Pilih</a>';
                echo '  </div>';

                echo '</div>';
            }
            ?>
        </div>

    </div>
</div>

<?php include "includes/footer.php"; ?>
