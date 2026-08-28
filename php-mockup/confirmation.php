<?php
/**
 * confirmation.php - Halaman Konfirmasi & E-Tiket (versi mockup / boarding pass)
 */
require_once __DIR__ . '/includes/init.php';
$pageTitle = "Konfirmasi Booking - SkyFare";

$namaDepan = isset($_POST['nama_depan']) ? $_POST['nama_depan'] : "Budi";
$kodePenerbangan = isset($_POST['kode']) ? $_POST['kode'] : "A";
$metodeBayarIndex = (int) ($_POST['metode_bayar'] ?? 0);
$metodeNames = [
    'Transfer Bank / Virtual Account',
    'Kartu Kredit / Debit',
    'E-Wallet',
    'QRIS',
];
$metodeBayar = $metodeNames[$metodeBayarIndex] ?? $metodeNames[0];

function generateKodeBooking(): string
{
    $tahun = date("Y");
    $angkaAcak = str_pad((string) rand(0, 9999), 4, "0", STR_PAD_LEFT);
    return "QA-" . $tahun . "-" . $angkaAcak;
}
$kodeBooking = generateKodeBooking();

$tiket = [
    "penumpang" => $namaDepan . " Santoso",
    "asal" => "CGK", "asal_kota" => "Jakarta, 08:00",
    "tujuan" => "DPS", "tujuan_kota" => "Denpasar, 09:45",
    "tanggal" => "12 Agu 2026",
    "kursi" => "14A", "kelas" => "Ekonomi", "bagasi" => "20kg",
    "flight_no" => "QA123", "gate" => "G7",
];

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
        $passengerStatement->execute(['booking_id' => $bookingId, 'first_name' => $namaDepan, 'last_name' => $_POST['nama_belakang'] ?? 'Santoso', 'email' => $_POST['email'] ?? '', 'phone' => $_POST['telepon'] ?? '', 'seat_number' => '14A', 'services' => json_encode($_POST['layanan'] ?? [])]);
        $paymentStatement = $pdo->prepare('INSERT INTO payments (booking_id, payment_method, amount, payment_status, paid_at) VALUES (:booking_id, :payment_method, :amount, "Berhasil", NOW())');
        $paymentStatement->execute(['booking_id' => $bookingId, 'payment_method' => $metodeBayar, 'amount' => $flight['total_amount']]);
        $pdo->commit();
    } catch (Throwable $error) {
        $pdo->rollBack();
    }
}

if (!isset($_SESSION['bookings'])) {
    $_SESSION['bookings'] = [];
}

$_SESSION['bookings'][] = [
    'kode_booking' => $kodeBooking,
    'nama_penumpang' => $tiket['penumpang'],
    'rute' => 'Jakarta (CGK) → Denpasar (DPS)',
    'tanggal' => $tiket['tanggal'],
    'status' => 'Terbayar',
    'kelas' => $tiket['kelas'],
    'kursi' => $tiket['kursi'],
    'maskapai' => 'Maskapai ' . $kodePenerbangan,
];

$_SESSION['booking_message'] = 'Pembayaran berhasil! Booking Anda sudah ditambahkan ke halaman Booking Saya.';

/**
 * Contoh integrasi library pihak ketiga: mail() bawaan PHP
 * (dinonaktifkan karena server ini tidak dikonfigurasi SMTP)
 */
if (isset($_POST['kirim_email'])) {
    // mail("penumpang@email.com", "E-Tiket SkyFare - " . $kodeBooking, "Kode booking: " . $kodeBooking);
    $emailTerkirim = true;
}

include "includes/header.php";
?>

<div class="container" style="max-width:960px;">

    <div style="text-align:center; margin: 40px 0 20px 0;">
        <div class="success-icon">&#10003;</div>
        <h2 style="margin-bottom:4px;">Pembayaran Berhasil!</h2>
        <p style="color:var(--slate); margin:0;">Kode Booking</p>
        <div class="time-chip" style="display:inline-block; margin-top:8px; font-size:15px;"><?php echo htmlspecialchars($kodeBooking); ?></div>

        <?php if (isset($emailTerkirim)) : ?>
            <p style="color:var(--green); font-size:13px; margin-top:12px;">&#10003; E-tiket sudah dikirim ke email kamu.</p>
        <?php endif; ?>
    </div>

    <!-- Boarding Pass -->
    <div class="boarding-pass" id="boarding-pass">
        <div class="stub-main">
            <div class="bp-header">
                <span>BOARDING PASS</span>
                <span style="color: var(--amber);">ECONOMY</span>
            </div>

            <div class="route">
                <div>
                    <div class="code"><?php echo htmlspecialchars($tiket["asal"]); ?></div>
                    <div class="sub"><?php echo htmlspecialchars($tiket["asal_kota"]); ?></div>
                </div>
                <div class="arrow">&#9992;<br>1j 45m — Langsung</div>
                <div>
                    <div class="code"><?php echo htmlspecialchars($tiket["tujuan"]); ?></div>
                    <div class="sub"><?php echo htmlspecialchars($tiket["tujuan_kota"]); ?></div>
                </div>
                <div style="text-align:right;">
                    <div class="sub">TANGGAL</div>
                    <div style="font-weight:700;"><?php echo htmlspecialchars($tiket["tanggal"]); ?></div>
                </div>
            </div>

            <div class="pax-row">
                <div>
                    <div class="label">PENUMPANG</div>
                    <div class="value"><?php echo htmlspecialchars($tiket["penumpang"]); ?></div>
                </div>
                <div>
                    <div class="label">KURSI</div>
                    <div class="value"><span class="seat-chip"><?php echo htmlspecialchars($tiket["kursi"]); ?></span></div>
                </div>
                <div>
                    <div class="label">KELAS</div>
                    <div class="value"><?php echo htmlspecialchars($tiket["kelas"]); ?></div>
                </div>
                <div>
                    <div class="label">BAGASI</div>
                    <div class="value"><?php echo htmlspecialchars($tiket["bagasi"]); ?></div>
                </div>
            </div>

            <div class="notch top"></div>
            <div class="notch bottom"></div>
            <div class="tear-line" style="right:0; left:auto;"></div>
        </div>

        <div class="stub-side">
            <div class="label">FLIGHT</div>
            <div class="value"><?php echo htmlspecialchars($tiket["flight_no"]); ?></div>

            <div class="label">GATE</div>
            <div class="value" style="color:#ffffff;"><?php echo htmlspecialchars($tiket["gate"]); ?></div>

            <div class="barcode">
                <?php
                // Loop untuk generate garis-garis barcode dengan lebar acak (dekoratif)
                for ($i = 0; $i < 26; $i++) {
                    $width = rand(2, 4);
                    echo '<div style="width:' . $width . 'px;"></div>';
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Aksi -->
    <div style="display:flex; gap:15px; justify-content:center; margin:30px 0;">
        <form method="post">
            <input type="hidden" name="kode" value="<?php echo htmlspecialchars($kodePenerbangan); ?>">
            <input type="hidden" name="nama_depan" value="<?php echo htmlspecialchars($namaDepan); ?>">
            <button type="submit" name="kirim_email" value="1" class="btn btn-outline">Kirim ke Email</button>
        </form>

        <a class="btn btn-outline" target="_blank"
           href="https://wa.me/?text=<?php echo urlencode('E-tiket saya: ' . $kodeBooking); ?>">
           Bagikan (WhatsApp)
        </a>

        <button type="button" class="btn btn-outline" onclick="window.print()">Cetak / Simpan PDF</button>
    </div>

    <div style="text-align:center; margin-bottom:40px;">
        <a href="booking.php" class="btn">Lihat Booking Saya</a>
        <p style="color:var(--slate); font-size:12px; margin-top:15px;">Butuh bantuan? Hubungi Customer Service kami 24/7</p>
    </div>

</div>

<style>
    @media print {
        .header, .footer, form, .btn, a.btn { display: none !important; }
        #boarding-pass { box-shadow: none; border: 2px solid var(--navy); }
    }
</style>

<?php include "includes/footer.php"; ?>
