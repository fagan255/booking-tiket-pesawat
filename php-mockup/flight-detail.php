<?php
/**
 * flight-detail.php - Halaman Detail Penerbangan (versi mockup)
 */
$pageTitle = "Detail Penerbangan - SkyFare";
require_once __DIR__ . '/includes/init.php';

include "includes/steps.php";

$kodePenerbangan = isset($_GET['kode']) ? $_GET['kode'] : "A";

$flight = [
    "maskapai"  => "Maskapai A",
    "nomor"     => "QA123",
    "pesawat"   => "Boeing 737-800",
    "asal"      => "Bandara Soekarno-Hatta (CGK)",
    "asal_kota" => "Terminal 2, Jakarta",
    "tujuan"    => "Bandara Ngurah Rai (DPS)",
    "tujuan_kota" => "Denpasar, Bali",
    "berangkat" => "08:00",
    "tiba"      => "09:45",
    "durasi"    => "1j 45m — Langsung",
    "harga_dasar" => 780000,
    "pajak"     => 70000,
];

$flightStatement = $pdo->prepare("SELECT airline AS maskapai, flight_code AS nomor, aircraft AS pesawat,
    CONCAT('Bandara ', origin_city, ' (', origin_code, ')') AS asal,
    CONCAT('Terminal 1, ', origin_city) AS asal_kota,
    CONCAT('Bandara ', destination_city, ' (', destination_code, ')') AS tujuan,
    CONCAT(destination_city, ', Indonesia') AS tujuan_kota,
    TIME_FORMAT(departure_time, '%H:%i') AS berangkat, TIME_FORMAT(arrival_time, '%H:%i') AS tiba,
    CONCAT(FLOOR(duration_minutes / 60), 'j ', MOD(duration_minutes, 60), 'm - Langsung') AS durasi,
    base_price AS harga_dasar, tax AS pajak FROM flights WHERE flight_code = :flight_code LIMIT 1");
$flightStatement->execute(['flight_code' => $kodePenerbangan]);
$databaseFlight = $flightStatement->fetch();
if ($databaseFlight) {
    $flight = $databaseFlight;
}

$total = $flight["harga_dasar"] + $flight["pajak"];

function rupiah(int $angka): string
{
    return "Rp " . number_format($angka, 0, ",", ".");
}

include "includes/header.php";
?>

<div class="container">

    <?php renderSteps(2); ?>

    <div class="layout-2col">

        <div class="main">
            <div class="card">
                <h3 class="card-title">Rincian Penerbangan</h3>

                <div style="display:flex; align-items:center; gap:15px; margin-bottom:20px;">
                    <div class="logo-circle" style="width:52px;height:52px;">A</div>
                    <div>
                        <div style="font-weight:700; font-size:15px;"><?php echo htmlspecialchars($flight["maskapai"] . " — " . $flight["nomor"]); ?></div>
                        <div style="font-size:12px; color:var(--slate);">Ekonomi | <?php echo htmlspecialchars($flight["pesawat"]); ?></div>
                    </div>
                </div>

                <div style="display:flex; gap:15px; margin-bottom:10px;">
                    <div class="time-chip"><?php echo htmlspecialchars($flight["berangkat"]); ?></div>
                    <div>
                        <div style="font-weight:600;"><?php echo htmlspecialchars($flight["asal"]); ?></div>
                        <div style="font-size:12px; color:var(--slate);"><?php echo htmlspecialchars($flight["asal_kota"]); ?></div>
                    </div>
                </div>
                <div style="font-size:12px; color:var(--slate); margin:8px 0 8px 65px;">Durasi <?php echo htmlspecialchars($flight["durasi"]); ?></div>

                <div style="display:flex; gap:15px; margin-bottom:20px;">
                    <div class="time-chip"><?php echo htmlspecialchars($flight["tiba"]); ?></div>
                    <div>
                        <div style="font-weight:600;"><?php echo htmlspecialchars($flight["tujuan"]); ?></div>
                        <div style="font-size:12px; color:var(--slate);"><?php echo htmlspecialchars($flight["tujuan_kota"]); ?></div>
                    </div>
                </div>

                <hr style="border:none; border-top:1px solid #EFEDE5; margin:20px 0;">

                <p style="font-weight:700; margin-bottom:10px;">Fasilitas</p>
                <span style="background:var(--amber-soft); color:#7A5A17; padding:6px 14px; border-radius:17px; font-size:12px; margin-right:8px;">Bagasi 20kg</span>
                <span style="background:var(--amber-soft); color:#7A5A17; padding:6px 14px; border-radius:17px; font-size:12px; margin-right:8px;">Kabin 7kg</span>
                <span style="background:var(--amber-soft); color:#7A5A17; padding:6px 14px; border-radius:17px; font-size:12px;">Snack</span>

                <p style="font-weight:700; margin:20px 0 10px 0;">Syarat &amp; Ketentuan Tiket</p>
                <div style="background:var(--cream); padding:16px; border-radius:12px; font-size:12px; color:var(--slate);">
                    — Tiket dapat dijadwal ulang dengan biaya tambahan<br>
                    — Pembatalan dikenakan potongan sesuai kebijakan maskapai<br>
                    — Refund diproses dalam 14 hari kerja
                </div>
            </div>
        </div>

        <div class="side">
            <div class="summary-box-navy">
                <h3>Ringkasan Harga</h3>
                <div class="summary-row"><span>Harga Dasar</span><span style="color:#fff;"><?php echo rupiah($flight["harga_dasar"]); ?></span></div>
                <div class="summary-row"><span>Pajak &amp; Biaya</span><span style="color:#fff;"><?php echo rupiah($flight["pajak"]); ?></span></div>
                <div class="summary-total"><span>Total</span><span class="amount"><?php echo rupiah($total); ?></span></div>

                <form action="passenger-data.php" method="get">
                    <input type="hidden" name="kode" value="<?php echo htmlspecialchars($kodePenerbangan); ?>">
                    <button type="submit" class="btn" style="width:100%; margin-top:20px;">Lanjut ke Data Penumpang</button>
                </form>
            </div>
        </div>

    </div>
</div>

<?php include "includes/footer.php"; ?>
