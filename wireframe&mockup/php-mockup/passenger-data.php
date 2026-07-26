<?php
/**
 * passenger-data.php - Halaman Data Penumpang (versi mockup)
 */
$pageTitle = "Data Penumpang - SkyFare";

include "includes/steps.php";

$kodePenerbangan = isset($_GET['kode']) ? $_GET['kode'] : "A";
$layananTambahan = ["Pilih Kursi", "Bagasi Tambahan", "Makanan Spesial", "Asuransi Perjalanan"];

include "includes/header.php";
?>

<div class="container">

    <?php renderSteps(3); ?>

    <div class="layout-2col">

        <div class="main">
            <form action="payment.php" method="post" class="card">
                <input type="hidden" name="kode" value="<?php echo htmlspecialchars($kodePenerbangan); ?>">

                <h3 class="card-title">Data Penumpang 1 (Dewasa)</h3>

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
                        <input type="text" id="nama_depan" name="nama_depan" placeholder="Budi" required>
                    </div>
                    <div class="field">
                        <label for="nama_belakang">Nama Belakang</label>
                        <input type="text" id="nama_belakang" name="nama_belakang" placeholder="Santoso" required>
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

                <hr style="border:none; border-top:1px solid #EFEDE5; margin:20px 0;">
                <h4 style="margin-bottom:15px;">Data Kontak</h4>

                <div class="row">
                    <div class="field">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="nama@email.com" required>
                    </div>
                    <div class="field">
                        <label for="telepon">Nomor Telepon</label>
                        <input type="text" id="telepon" name="telepon" placeholder="+62 812-xxxx-xxxx" required>
                    </div>
                </div>

                <hr style="border:none; border-top:1px solid #EFEDE5; margin:20px 0;">
                <h4 style="margin-bottom:15px;">Layanan Tambahan (Opsional)</h4>

                <?php
                foreach ($layananTambahan as $layanan) {
                    $value = htmlspecialchars($layanan);
                    echo '<label style="display:inline-block; background:var(--cream); border-radius:20px; padding:8px 16px; margin: 0 10px 10px 0; font-size:12px;">';
                    echo '  <input type="checkbox" name="layanan[]" value="' . $value . '"> ' . $value;
                    echo '</label>';
                }
                ?>

                <div style="margin-top:16px;">
                    <label style="font-size:13px;">
                        <input type="checkbox" required>
                        Saya menyetujui Syarat &amp; Ketentuan
                    </label>
                </div>

                <button type="submit" class="btn" style="margin-top:20px;">Lanjut ke Pembayaran</button>
            </form>
        </div>

        <div class="side">
            <div class="summary-box-navy">
                <h3>Ringkasan Perjalanan</h3>
                <p style="font-size:13px; font-weight:600;">Jakarta (CGK) &rarr; Denpasar (DPS)</p>
                <p style="font-size:12px; color:#AEB9CC;">12 Agu 2026, 08:00 — Maskapai <?php echo htmlspecialchars($kodePenerbangan); ?></p>
                <div class="summary-total"><span>Total</span><span class="amount">Rp 850.000</span></div>
            </div>
        </div>

    </div>
</div>

<?php include "includes/footer.php"; ?>
