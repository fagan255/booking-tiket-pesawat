<?php
/**
 * payment.php - Halaman Pembayaran
 */
$pageTitle = "Pembayaran - SkyFare";

include "includes/steps.php";

// Data dari form sebelumnya (dikirim via POST dari passenger-data.php)
$namaDepan = isset($_POST['nama_depan']) ? $_POST['nama_depan'] : "Budi";
$kodePenerbangan = isset($_POST['kode']) ? $_POST['kode'] : "A";

/**
 * Struktur data: array metode pembayaran, di-loop supaya
 * tidak menulis HTML yang sama berulang-ulang (DRY principle).
 */
$metodePembayaran = [
    ["nama" => "Transfer Bank / Virtual Account", "detail" => "BCA, Mandiri, BNI, BRI", "checked" => true],
    ["nama" => "Kartu Kredit / Debit",             "detail" => "Visa, Mastercard",        "checked" => false],
    ["nama" => "E-Wallet",                         "detail" => "GoPay, OVO, Dana",        "checked" => false],
    ["nama" => "QRIS",                             "detail" => "Scan dengan aplikasi apapun", "checked" => false],
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

        <!-- Metode Pembayaran -->
        <div class="main">
            <form action="confirmation.php" method="post" class="box">
                <input type="hidden" name="kode" value="<?php echo htmlspecialchars($kodePenerbangan); ?>">
                <input type="hidden" name="nama_depan" value="<?php echo htmlspecialchars($namaDepan); ?>">
                <input type="hidden" name="nama_belakang" value="<?php echo htmlspecialchars($_POST['nama_belakang'] ?? ''); ?>">
                <input type="hidden" name="tgl_lahir" value="<?php echo htmlspecialchars($_POST['tgl_lahir'] ?? ''); ?>">
                <input type="hidden" name="no_identitas" value="<?php echo htmlspecialchars($_POST['no_identitas'] ?? ''); ?>">
                <input type="hidden" name="kewarganegaraan" value="<?php echo htmlspecialchars($_POST['kewarganegaraan'] ?? ''); ?>">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                <input type="hidden" name="telepon" value="<?php echo htmlspecialchars($_POST['telepon'] ?? ''); ?>">
                <?php if (!empty($_POST['layanan']) && is_array($_POST['layanan'])):
                    foreach ($_POST['layanan'] as $layanan): ?>
                        <input type="hidden" name="layanan[]" value="<?php echo htmlspecialchars($layanan); ?>">
                <?php endforeach; endif; ?>

                <h3 class="box-title">Metode Pembayaran</h3>

                <?php
                // Loop untuk render radio button dari array $metodePembayaran
                foreach ($metodePembayaran as $index => $metode) {
                    $checkedAttr = $metode["checked"] ? "checked" : "";
                    echo '<div class="box" style="margin-bottom:15px; padding:15px;">';
                    echo '  <label>';
                    echo '    <input type="radio" name="metode_bayar" value="' . $index . '" ' . $checkedAttr . '>';
                    echo '    <strong>' . htmlspecialchars($metode["nama"]) . '</strong>';
                    echo '  </label>';
                    echo '  <div style="font-size:11px; color:#888888; margin-left:24px;">' . htmlspecialchars($metode["detail"]) . '</div>';
                    echo '</div>';
                }
                ?>

                <button type="submit" class="btn" style="width:100%; margin-top:10px;">Bayar Sekarang</button>
            </form>
        </div>

        <!-- Ringkasan Pembayaran -->
        <div class="side">
            <div class="summary-box">
                <h3>Ringkasan Pembayaran</h3>
                <div class="summary-row"><span>Harga Tiket (1 org)</span><span><?php echo rupiah($hargaTiket); ?></span></div>
                <div class="summary-row"><span>Pajak &amp; Biaya</span><span><?php echo rupiah($pajak); ?></span></div>
                <div class="summary-row"><span>Layanan Tambahan</span><span><?php echo rupiah($layananTambahan); ?></span></div>
                <div class="summary-total"><span>Total Bayar</span><span><?php echo rupiah($totalBayar); ?></span></div>

                <p style="font-size:11px; color:#888888; margin-top:15px;">Batas waktu pembayaran:</p>
                <p style="font-size:16px;">23:59:59</p>
            </div>
        </div>

    </div>
</div>

<?php include "includes/footer.php"; ?>
