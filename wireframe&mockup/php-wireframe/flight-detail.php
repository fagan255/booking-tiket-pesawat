<?php
/**
 * flight-detail.php - Halaman Detail Penerbangan
 */
$pageTitle = "Detail Penerbangan - SkyFare";

include "includes/steps.php";

// Ambil kode penerbangan dari query string, contoh: flight-detail.php?kode=A
$kodePenerbangan = isset($_GET['kode']) ? $_GET['kode'] : "A";

/**
 * Di aplikasi nyata, data ini akan diambil dari database berdasarkan $kodePenerbangan.
 * Untuk contoh wireframe, kita pakai data statis dulu.
 */
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

$total = $flight["harga_dasar"] + $flight["pajak"]; // logika penjumlahan sederhana

function rupiah(int $angka): string
{
    return "Rp " . number_format($angka, 0, ",", ".");
}

include "includes/header.php";
?>

<div class="container">

    <?php renderSteps(2); // panggil komponen reusable, tandai step ke-2 aktif ?>

    <div class="layout-2col">

        <!-- Detail utama -->
        <div class="main">
            <div class="box">
                <h3 class="box-title">Rincian Penerbangan</h3>

                <p><strong><?php echo htmlspecialchars($flight["maskapai"] . " — " . $flight["nomor"]); ?></strong></p>
                <p style="color:#888888; font-size:12px;">Ekonomi | <?php echo htmlspecialchars($flight["pesawat"]); ?></p>

                <hr style="border:none; border-top:1px solid #eeeeee; margin:20px 0;">

                <p><strong><?php echo htmlspecialchars($flight["berangkat"]); ?></strong> — <?php echo htmlspecialchars($flight["asal"]); ?></p>
                <p style="color:#888888; font-size:12px; margin-left:20px;"><?php echo htmlspecialchars($flight["asal_kota"]); ?></p>

                <p style="color:#888888; font-size:12px;">Durasi <?php echo htmlspecialchars($flight["durasi"]); ?></p>

                <p><strong><?php echo htmlspecialchars($flight["tiba"]); ?></strong> — <?php echo htmlspecialchars($flight["tujuan"]); ?></p>
                <p style="color:#888888; font-size:12px; margin-left:20px;"><?php echo htmlspecialchars($flight["tujuan_kota"]); ?></p>

                <hr style="border:none; border-top:1px solid #eeeeee; margin:20px 0;">

                <p><strong>Fasilitas</strong></p>
                <p style="font-size:13px; color:#555555;">Bagasi 20kg &nbsp;|&nbsp; Bagasi Kabin 7kg &nbsp;|&nbsp; Snack</p>

                <p><strong>Syarat &amp; Ketentuan Tiket</strong></p>
                <div style="background:#f5f5f5; padding:15px; font-size:12px; color:#888888;">
                    — Tiket dapat dijadwal ulang dengan biaya tambahan<br>
                    — Pembatalan dikenakan potongan sesuai kebijakan maskapai<br>
                    — Refund diproses dalam 14 hari kerja
                </div>
            </div>
        </div>

        <!-- Ringkasan harga -->
        <div class="side">
            <div class="summary-box">
                <h3>Ringkasan Harga</h3>
                <div class="summary-row">
                    <span>Harga Dasar</span>
                    <span><?php echo rupiah($flight["harga_dasar"]); ?></span>
                </div>
                <div class="summary-row">
                    <span>Pajak &amp; Biaya</span>
                    <span><?php echo rupiah($flight["pajak"]); ?></span>
                </div>
                <div class="summary-total">
                    <span>Total</span>
                    <span><?php echo rupiah($total); ?></span>
                </div>

                <form action="passenger-data.php" method="get">
                    <input type="hidden" name="kode" value="<?php echo htmlspecialchars($kodePenerbangan); ?>">
                    <button type="submit" class="btn" style="width:100%; margin-top:20px;">Lanjut ke Data Penumpang</button>
                </form>
            </div>
        </div>

    </div>
</div>

<?php include "includes/footer.php"; ?>
