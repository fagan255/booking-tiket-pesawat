<?php
/**
 * search-results.php - Halaman Hasil Pencarian Penerbangan
 */
$pageTitle = "Hasil Pencarian - SkyFare";

// Ambil parameter pencarian dari form (GET) dengan nilai default jika kosong
$dari     = isset($_GET['dari']) ? $_GET['dari'] : "Jakarta (CGK)";
$ke       = isset($_GET['ke']) ? $_GET['ke'] : "Denpasar (DPS)";
$tanggal  = isset($_GET['tanggal']) ? $_GET['tanggal'] : "2026-08-12";

/**
 * Struktur data: array multidimensi untuk daftar penerbangan.
 * Setiap penerbangan disimpan sebagai array asosiatif.
 * Data ini nantinya bisa diganti dengan hasil query database.
 */
$flightList = [
    [
        "maskapai" => "Maskapai A",
        "kode"     => "A",
        "tipe"     => "Penerbangan Langsung",
        "berangkat"=> "08:00",
        "tiba"     => "09:45",
        "durasi"   => "1j 45m",
        "harga"    => 850000,
    ],
    [
        "maskapai" => "Maskapai B",
        "kode"     => "B",
        "tipe"     => "Penerbangan Langsung",
        "berangkat"=> "10:30",
        "tiba"     => "12:20",
        "durasi"   => "1j 50m",
        "harga"    => 920000,
    ],
    [
        "maskapai" => "Maskapai C",
        "kode"     => "C",
        "tipe"     => "1x Transit",
        "berangkat"=> "14:00",
        "tiba"     => "17:10",
        "durasi"   => "3j 10m",
        "harga"    => 700000,
    ],
];

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
