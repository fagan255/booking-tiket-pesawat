<?php
/**
 * passenger-data.php - Halaman Data Penumpang
 */
$pageTitle = "Data Penumpang - SkyFare";

include "includes/steps.php";

$kodePenerbangan = isset($_GET['kode']) ? $_GET['kode'] : "A";

// Layanan tambahan opsional (struktur data: array sederhana untuk checkbox)
$layananTambahan = ["Pilih Kursi", "Bagasi Tambahan", "Makanan Spesial", "Asuransi Perjalanan"];

include "includes/header.php";
?>

<div class="container">

    <?php renderSteps(3); ?>

    <div class="layout-2col">

        <!-- Form Data Penumpang -->
        <div class="main">
            <form action="payment.php" method="post" class="box">
                <input type="hidden" name="kode" value="<?php echo htmlspecialchars($kodePenerbangan); ?>">

                <h3 class="box-title">Data Penumpang 1 (Dewasa)</h3>

                <div class="row">
                    <div class="field">
                        <label for="gelar">Gelar</label>
                        <select id="gelar" name="gelar">
                            <option>Tuan</option>
                            <option>Nyonya</option>
                        </select>
                    </div>
                    <div class="field">
                        <label for="nama_depan">Nama Depan</label>
                        <input type="text" id="nama_depan" name="nama_depan" placeholder="Sesuai KTP/Paspor" required>
                    </div>
                    <div class="field">
                        <label for="nama_belakang">Nama Belakang</label>
                        <input type="text" id="nama_belakang" name="nama_belakang" placeholder="Sesuai KTP/Paspor" required>
                    </div>
                </div>

                <div class="row">
                    <div class="field">
                        <label for="tgl_lahir">Tanggal Lahir</label>
                        <input type="date" id="tgl_lahir" name="tgl_lahir" required>
                    </div>
                    <div class="field">
                        <label for="no_identitas">Nomor KTP/Paspor</label>
                        <input type="text" id="no_identitas" name="no_identitas" required>
                    </div>
                    <div class="field">
                        <label for="kewarganegaraan">Kewarganegaraan</label>
                        <input type="text" id="kewarganegaraan" name="kewarganegaraan" value="Indonesia">
                    </div>
                </div>

                <hr style="border:none; border-top:1px solid #eeeeee; margin:20px 0;">
                <h4>Data Kontak</h4>

                <div class="row">
                    <div class="field">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="nama@email.com" required>
                    </div>
                    <div class="field">
                        <label for="telepon">Nomor Telepon</label>
                        <input type="text" id="telepon" name="telepon" placeholder="+62" required>
                    </div>
                </div>

                <hr style="border:none; border-top:1px solid #eeeeee; margin:20px 0;">
                <h4>Layanan Tambahan (Opsional)</h4>

                <?php
                // Loop untuk generate checkbox layanan tambahan dari array $layananTambahan
                foreach ($layananTambahan as $layanan) {
                    $value = htmlspecialchars($layanan);
                    echo '<label style="margin-right:20px; display:inline-block;">';
                    echo '  <input type="checkbox" name="layanan[]" value="' . $value . '"> ' . $value;
                    echo '</label>';
                }
                ?>

                <div style="margin-top:20px;">
                    <label>
                        <input type="checkbox" required>
                        Saya menyetujui Syarat &amp; Ketentuan
                    </label>
                </div>

                <button type="submit" class="btn" style="margin-top:20px;">Lanjut ke Pembayaran</button>
            </form>
        </div>

        <!-- Ringkasan -->
        <div class="side">
            <div class="summary-box">
                <h3>Ringkasan Perjalanan</h3>
                <p style="font-size:13px;">Jakarta (CGK) &rarr; Denpasar (DPS)</p>
                <p style="font-size:12px; color:#888888;">12 Agu 2026, 08:00 — Maskapai <?php echo htmlspecialchars($kodePenerbangan); ?></p>
                <div class="summary-total">
                    <span>Total</span>
                    <span>Rp 850.000</span>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include "includes/footer.php"; ?>
