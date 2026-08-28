<?php
require_once __DIR__ . '/database.php';

/**
 * includes/init.php
 * Inisialisasi session dan helper fungsi aplikasi SkyFare.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function skyfareIsLoggedIn(): bool
{
    return !empty($_SESSION['user']['name']);
}

function skyfareGetCurrentUserName(): string
{
    return $_SESSION['user']['name'] ?? 'Tamu';
}

function skyfareLogout(): void
{
    unset($_SESSION['user']);
}

function skyfareGetBookings(): array
{
    global $pdo;
    if (empty($_SESSION['user']['id'])) {
        return $_SESSION['bookings'] ?? [];
    }

    $statement = $pdo->prepare(
        'SELECT b.booking_code AS kode_booking, b.total_amount, b.booking_status AS status,
                p.first_name AS nama_depan, p.last_name AS nama_belakang, p.email, p.phone AS telepon,
                f.origin_code AS asal, f.destination_code AS tujuan, f.flight_date AS tanggal,
                f.cabin_class AS kelas, pay.payment_method AS metode_bayar
         FROM bookings b
         JOIN flights f ON f.id = b.flight_id
         LEFT JOIN passengers p ON p.booking_id = b.id
         LEFT JOIN payments pay ON pay.booking_id = b.id
         WHERE b.user_id = :user_id ORDER BY b.created_at DESC'
    );
    $statement->execute(['user_id' => $_SESSION['user']['id']]);
    return $statement->fetchAll();
}

function skyfareFindBookingByHash(string $hash): ?array
{
    foreach (skyfareGetBookings() as $booking) {
        if (isset($booking['hash']) && $booking['hash'] === $hash) {
            return $booking;
        }
    }
    return null;
}

function skyfareAddBooking(array $booking): void
{
    if (!isset($_SESSION['bookings'])) {
        $_SESSION['bookings'] = [];
    }

    $hash = $booking['hash'] ?? md5(json_encode($booking, JSON_UNESCAPED_UNICODE));
    foreach ($_SESSION['bookings'] as $existingBooking) {
        if (isset($existingBooking['hash']) && $existingBooking['hash'] === $hash) {
            return;
        }
    }

    $booking['hash'] = $hash;
    $booking['created_at'] = date('Y-m-d H:i:s');
    $_SESSION['bookings'][] = $booking;
}
