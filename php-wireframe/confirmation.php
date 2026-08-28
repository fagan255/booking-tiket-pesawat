<?php
/**
 * confirmation.php - Halaman Konfirmasi & E-Tiket
 * Menunjukkan contoh integrasi pihak ketiga:
 *  - Kirim Email (fungsi mail() bawaan PHP)
 *  - Bagikan ke WhatsApp (link wa.me)
 *  - Cetak / Simpan PDF (window.print() bawaan browser)
 */
require_once __DIR__ . '/includes/init.php';

$pageTitle = "Konfirmasi Booking - SkyFare";

$namaDepan = isset($_POST['nama_depan']) ? $_POST['nama_depan'] : "Budi";
$namaBelakang = isset($_POST['nama_belakang']) ? $_POST['nama_belakang'] : "Santoso";
$kodePenerbangan = isset($_POST['kode']) ? $_POST['kode'] : "A";
$metodeBayarIndex = isset($_POST['metode_bayar']) ? (int) $_POST['metode_bayar'] : 0;
$metodeNames = [
    "Transfer Bank / Virtual Account",
    "Kartu Kredit / Debit",
    "E-Wallet",
    "QRIS",
];
$metodeBayar = $metodeNames[$metodeBayarIndex] ?? $metodeNames[0];

/**
 * Fungsi untuk generate kode booking unik.
 * Contoh sederhana: prefix + tahun + angka acak.
 * Di aplikasi nyata, kode ini disimpan ke database agar tidak duplikat.
 */
function generateKodeBooking(): string
{
    $tahun = date("Y");
    $angkaAcak = str_pad((string) rand(0, 9999), 4, "0", STR_PAD_LEFT);
    return "QA-" . $tahun . "-" . $angkaAcak;
}

$kodeBooking = generateKodeBooking();

$tiket = [
    "penumpang" => $namaDepan . " " . $namaBelakang,
    "asal"      => "CGK",
    "asal_kota" => "Jakarta, 08:00",
    "tujuan"    => "DPS",
    "tujuan_kota" => "Denpasar, 09:45",
    "tanggal"   => "12 Agu 2026",
    "kursi"     => "14A",
    "kelas"     => "Ekonomi",
    "bagasi"    => "20kg",
    "flight_no" => "QA123",
    "gate"      => "G7",
];

$bookingData = [
    'kode_booking'     => $kodeBooking,
    'kode_penerbangan' => $kodePenerbangan,
    'nama_depan'       => $namaDepan,
    'nama_belakang'    => $namaBelakang,
    'email'            => $_POST['email'] ?? '',
    'telepon'          => $_POST['telepon'] ?? '',
    'metode_bayar'     => $metodeBayar,
    'layanan'          => $_POST['layanan'] ?? [],
    'asal'             => 'CGK',
    'tujuan'           => 'DPS',
    'tanggal'          => '12 Agu 2026',
    'kelas'            => 'Ekonomi',
    'status'           => 'Berhasil',
    'hash'             => md5($kodePenerbangan . '|' . $namaDepan . '|' . $namaBelakang . '|' . ($_POST['email'] ?? '') . '|' . ($_POST['telepon'] ?? '') . '|' . '12 Agu 2026'),
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['kirim_email'])) {
    skyfareAddBooking($bookingData);

    $flightCodes = ['A' => 'QA123', 'B' => 'QB456', 'C' => 'QC789'];
    $databaseFlightCode = $flightCodes[$kodePenerbangan] ?? $kodePenerbangan;
    $flightStatement = $pdo->prepare('SELECT id, base_price + tax AS total_amount FROM flights WHERE flight_code = :flight_code LIMIT 1');
    $flightStatement->execute(['flight_code' => $databaseFlightCode]);
    $flight = $flightStatement->fetch();
    if ($flight) {
        $pdo->beginTransaction();
        try {
            $bookingStatement = $pdo->prepare('INSERT INTO bookings (user_id, flight_id, booking_code, total_amount, booking_status) VALUES (:user_id, :flight_id, :booking_code, :total_amount, "Terbayar")');
            $bookingStatement->execute(['user_id' => $_SESSION['user']['id'] ?? null, 'flight_id' => $flight['id'], 'booking_code' => $kodeBooking, 'total_amount' => $flight['total_amount']]);
            $bookingId = $pdo->lastInsertId();
            $passengerStatement = $pdo->prepare('INSERT INTO passengers (booking_id, first_name, last_name, email, phone, seat_number, special_services) VALUES (:booking_id, :first_name, :last_name, :email, :phone, :seat_number, :services)');
            $passengerStatement->execute(['booking_id' => $bookingId, 'first_name' => $namaDepan, 'last_name' => $namaBelakang, 'email' => $_POST['email'] ?? '', 'phone' => $_POST['telepon'] ?? '', 'seat_number' => '14A', 'services' => json_encode($_POST['layanan'] ?? [])]);
            $paymentStatement = $pdo->prepare('INSERT INTO payments (booking_id, payment_method, amount, payment_status, paid_at) VALUES (:booking_id, :payment_method, :amount, "Berhasil", NOW())');
            $paymentStatement->execute(['booking_id' => $bookingId, 'payment_method' => $metodeBayar, 'amount' => $flight['total_amount']]);
            $pdo->commit();
        } catch (Throwable $error) {
            $pdo->rollBack();
        }
    }
}

/**
 * ------------------------------------------------------------------
 * CONTOH INTEGRASI LIBRARY / KOMPONEN PIHAK KETIGA (Kelompok Pekerjaan 2)
 * ------------------------------------------------------------------
 * Ini menunjukkan DI MANA logika pengiriman email dipasang.
 * mail() adalah fungsi bawaan PHP untuk kirim email lewat mail server
 * (butuh konfigurasi SMTP di php.ini / server, contoh: sendmail, XAMPP+Mercury).
 * Untuk produksi biasanya pakai library pihak ketiga seperti PHPMailer.
 */
if (isset($_POST['kirim_email'])) {
    $tujuanEmail = "penumpang@email.com"; // seharusnya diambil dari data form sebelumnya
    $subjek = "E-Tiket SkyFare - " . $kodeBooking;
    $pesan  = "Terima kasih telah booking. Kode booking Anda: " . $kodeBooking;
    $headers = "From: no-reply@skyfare.com";

    // mail($tujuanEmail, $subjek, $pesan, $headers);
    // Baris di atas di-nonaktifkan karena server ini tidak dikonfigurasi SMTP.
    // Aktifkan setelah XAMPP/LAMPP kamu disetting Mercury Mail / SMTP relay.

    $emailTerkirim = true;
}

include "includes/header.php";
?>

<div class="container" style="max-width:900px;">

    <div style="text-align:center; margin: 30px 0;">
        <div style="width:60px;height:60px;border:2px solid #999999;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 15px auto;font-size:24px;">✓</div>
        <h2>Pembayaran Berhasil!</h2>
        <p style="color:#888888;">Kode Booking</p>
        <p style="font-size:16px; background:#eeeeee; display:inline-block; padding:6px 20px; border:1px solid #999999;">
            <?php echo htmlspecialchars($kodeBooking); ?>
        </p>

        <?php if (isset($emailTerkirim)) : ?>
            <p style="color:#2E7D5B; font-size:13px;">E-tiket sudah dikirim ke email kamu.</p>
        <?php endif; ?>
    </div>

    <!-- Boarding pass -->
    <div class="box" id="boarding-pass">
        <h3 class="box-title">Boarding Pass</h3>

        <div style="display:flex; justify-content:space-between;">
            <div>
                <div style="font-size:28px;"><?php echo htmlspecialchars($tiket["asal"]); ?></div>
                <div style="font-size:12px; color:#888888;"><?php echo htmlspecialchars($tiket["asal_kota"]); ?></div>
            </div>
            <div style="align-self:center; color:#888888; font-size:12px;">1j 45m — Langsung</div>
            <div>
                <div style="font-size:28px;"><?php echo htmlspecialchars($tiket["tujuan"]); ?></div>
                <div style="font-size:12px; color:#888888;"><?php echo htmlspecialchars($tiket["tujuan_kota"]); ?></div>
            </div>
            <div style="text-align:right;">
                <div style="font-size:11px; color:#888888;">TANGGAL</div>
                <div style="font-size:15px;"><?php echo htmlspecialchars($tiket["tanggal"]); ?></div>
            </div>
        </div>

        <hr style="border:none; border-top:1px solid #eeeeee; margin:20px 0;">

        <div class="row" style="font-size:13px;">
            <div>
                <div style="color:#888888; font-size:11px;">PENUMPANG</div>
                <div><strong><?php echo htmlspecialchars($tiket["penumpang"]); ?></strong></div>
            </div>
            <div>
                <div style="color:#888888; font-size:11px;">KURSI</div>
                <div><strong><?php echo htmlspecialchars($tiket["kursi"]); ?></strong></div>
            </div>
            <div>
                <div style="color:#888888; font-size:11px;">KELAS</div>
                <div><strong><?php echo htmlspecialchars($tiket["kelas"]); ?></strong></div>
            </div>
            <div>
                <div style="color:#888888; font-size:11px;">BAGASI</div>
                <div><strong><?php echo htmlspecialchars($tiket["bagasi"]); ?></strong></div>
            </div>
            <div>
                <div style="color:#888888; font-size:11px;">FLIGHT</div>
                <div><strong><?php echo htmlspecialchars($tiket["flight_no"]); ?></strong></div>
            </div>
        </div>
    </div>

    <!-- Aksi: Email, WhatsApp, Cetak PDF -->
    <div style="display:flex; gap:15px; justify-content:center; margin:30px 0;">

        <!-- Kirim Email: submit form ke halaman ini sendiri (self-submit) -->
        <form method="post">
            <input type="hidden" name="kode" value="<?php echo htmlspecialchars($kodePenerbangan); ?>">
            <input type="hidden" name="nama_depan" value="<?php echo htmlspecialchars($namaDepan); ?>">
            <button type="submit" name="kirim_email" value="1" class="btn btn-outline">Kirim ke Email</button>
        </form>

        <!-- Bagikan WhatsApp: pakai link resmi wa.me, tidak perlu API key -->
        <a class="btn btn-outline"
           target="_blank"
           href="https://wa.me/?text=<?php echo urlencode('E-tiket saya: ' . $kodeBooking); ?>">
           Bagikan (WhatsApp)
        </a>

        <!-- Cetak / Simpan PDF: pakai window.print() bawaan browser -->
        <button type="button" class="btn btn-outline" onclick="window.print()">Cetak / Simpan PDF</button>
    </div>

    <div style="text-align:center;">
        <a href="booking.php" class="btn">Lihat Booking Saya</a>
    </div>

</div>

<style>
    /* Saat dicetak (window.print), sembunyikan header/footer, tampilkan boarding pass saja */
    @media print {
        .header, .footer, form, .btn, a.btn { display: none !important; }
        #boarding-pass { border: 2px solid #333333; }
    }
</style>

<?php include "includes/footer.php"; ?>
