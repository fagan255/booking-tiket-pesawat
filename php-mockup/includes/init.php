<?php
require_once __DIR__ . '/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function skyfareIsLoggedIn(): bool
{
    return !empty($_SESSION['user']['id']);
}

function skyfareGetCurrentUserName(): string
{
    return $_SESSION['user']['name'] ?? 'Tamu';
}

function skyfareGetBookings(PDO $pdo): array
{
    if (empty($_SESSION['user']['id'])) {
        return [];
    }

    $statement = $pdo->prepare(
        'SELECT b.booking_code AS kode_booking, b.booking_status AS status,
            CONCAT(p.first_name, " ", p.last_name) AS nama_penumpang,
            CONCAT(f.origin_city, " (", f.origin_code, ") -> ", f.destination_city, " (", f.destination_code, ")") AS rute,
            f.airline AS maskapai, f.flight_date AS tanggal, f.cabin_class AS kelas,
            p.seat_number AS kursi
         FROM bookings b JOIN flights f ON f.id = b.flight_id
         LEFT JOIN passengers p ON p.booking_id = b.id
         WHERE b.user_id = :user_id ORDER BY b.created_at DESC'
    );
    $statement->execute(['user_id' => $_SESSION['user']['id']]);
    return $statement->fetchAll();
}
