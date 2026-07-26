<?php
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
    return $_SESSION['bookings'] ?? [];
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
