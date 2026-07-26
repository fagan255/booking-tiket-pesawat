<?php
/**
 * payment.php - Halaman Pembayaran (versi mockup)
 */
$pageTitle = "Pembayaran - SkyFare";

include "includes/steps.php";

$namaDepan = isset($_POST['nama_depan']) ? $_POST['nama_depan'] : "Budi";
$kodePenerbangan = isset($_POST['kode']) ? $_POST['kode'] : "A";

$metodePembayaran = [
    ["nama" => "Transfer Bank / Virtual Account", "detail" => "BCA, Mandiri, BNI, BRI",     "checked" => true],
    ["nama" => "Kartu Kredit / Debit",             "detail" => "Visa, Mastercard",           "checked" => false],
    ["nama" => "E-Wallet",                         "detail" => "GoPay, OVO, Dana",           "checked" => false],
    ["nama" => "QRIS",                             "detail" => "Scan dengan aplikasi apapun","checked" => false],
];

$hargaTiket = 780000;
$pajak = 70000;
$layananTambahan = 0;
$totalBayar = $hargaTiket + $pajak + $layananTambahan;

function rupiah(int $angka): string
{
    return "Rp " . number_format($angka, 0, ",", ".");
}

include "includes/header.php";
?>

<div class="container">

    <?php renderSteps(4); ?>

    <div class="layout-2col">

        <div class="main">
            <form action="confirmation.php" method="post" class="card">
                <input type="hidden" name="kode" value="<?php echo htmlspecialchars($kodePenerbangan); ?>">
                <input type="hidden" name="nama_depan" value="<?php echo htmlspecialchars($namaDepan); ?>">

                <h3 class="card-title">Metode Pembayaran</h3>

                <?php
                foreach ($metodePembayaran as $index => $metode) {
                    $checkedAttr = $metode["checked"] ? "checked" : "";
                    $selectedClass = $metode["checked"] ? "selected" : "";
                    echo '<div class="payment-option ' . $selectedClass . '">';
                    echo '  <label>';
                    echo '    <input type="radio" name="metode_bayar" value="' . $index . '" ' . $checkedAttr . '>';
                    echo '    <strong>' . htmlspecialchars($metode["nama"]) . '</strong>';
                    echo '  </label>';
                    echo '  <div class="sub">' . htmlspecialchars($metode["detail"]) . '</div>';
                    echo '</div>';
                }
                ?>

                <button type="submit" class="btn" style="width:100%; margin-top:10px;">Bayar Sekarang</button>
            </form>
        </div>

        <div class="side">
            <div class="summary-box-navy">
                <h3>Ringkasan Pembayaran</h3>
                <div class="summary-row"><span>Harga Tiket (1 org)</span><span style="color:#fff;"><?php echo rupiah($hargaTiket); ?></span></div>
                <div class="summary-row"><span>Pajak &amp; Biaya</span><span style="color:#fff;"><?php echo rupiah($pajak); ?></span></div>
                <div class="summary-row"><span>Layanan Tambahan</span><span style="color:#fff;"><?php echo rupiah($layananTambahan); ?></span></div>
                <div class="summary-total"><span>Total Bayar</span><span class="amount"><?php echo rupiah($totalBayar); ?></span></div>

                <div class="countdown-box">
                    <div class="label">BATAS WAKTU PEMBAYARAN</div>
                    <div class="value">23:59:59</div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include "includes/footer.php"; ?>
