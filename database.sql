-- Database SkyFare
-- Import file ini melalui phpMyAdmin atau MySQL CLI.

CREATE DATABASE IF NOT EXISTS skyfare_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE skyfare_db;

CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS flights (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    airline VARCHAR(100) NOT NULL,
    flight_code VARCHAR(20) NOT NULL UNIQUE,
    aircraft VARCHAR(100) NOT NULL,
    origin_code CHAR(3) NOT NULL,
    origin_city VARCHAR(100) NOT NULL,
    destination_code CHAR(3) NOT NULL,
    destination_city VARCHAR(100) NOT NULL,
    departure_time TIME NOT NULL,
    arrival_time TIME NOT NULL,
    duration_minutes SMALLINT UNSIGNED NOT NULL,
    flight_date DATE NOT NULL,
    base_price DECIMAL(12, 2) NOT NULL,
    tax DECIMAL(12, 2) NOT NULL DEFAULT 0,
    cabin_class ENUM('Ekonomi', 'Bisnis', 'First Class') NOT NULL DEFAULT 'Ekonomi',
    baggage_kg TINYINT UNSIGNED NOT NULL DEFAULT 20,
    status ENUM('Tersedia', 'Penuh', 'Dibatalkan') NOT NULL DEFAULT 'Tersedia',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_flights_route_date (origin_code, destination_code, flight_date)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS bookings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NULL,
    flight_id INT UNSIGNED NOT NULL,
    booking_code VARCHAR(20) NOT NULL UNIQUE,
    total_amount DECIMAL(12, 2) NOT NULL,
    booking_status ENUM('Menunggu Pembayaran', 'Terbayar', 'Dibatalkan') NOT NULL DEFAULT 'Menunggu Pembayaran',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_bookings_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    CONSTRAINT fk_bookings_flight FOREIGN KEY (flight_id) REFERENCES flights(id) ON DELETE RESTRICT,
    INDEX idx_bookings_user (user_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS passengers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    booking_id INT UNSIGNED NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    seat_number VARCHAR(5) NULL,
    special_services JSON NULL,
    CONSTRAINT fk_passengers_booking FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS payments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    booking_id INT UNSIGNED NOT NULL,
    payment_method ENUM('Transfer Bank / Virtual Account', 'Kartu Kredit / Debit', 'E-Wallet', 'QRIS') NOT NULL,
    amount DECIMAL(12, 2) NOT NULL,
    payment_status ENUM('Menunggu', 'Berhasil', 'Gagal') NOT NULL DEFAULT 'Menunggu',
    paid_at DATETIME NULL,
    CONSTRAINT fk_payments_booking FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    UNIQUE KEY uq_payments_booking (booking_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS promos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    valid_until DATE NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO flights (
    airline, flight_code, aircraft, origin_code, origin_city,
    destination_code, destination_city, departure_time, arrival_time,
    duration_minutes, flight_date, base_price, tax, cabin_class, baggage_kg
) VALUES
    ('Maskapai A', 'QA123', 'Boeing 737-800', 'CGK', 'Jakarta', 'DPS', 'Denpasar', '08:00:00', '09:45:00', 105, '2026-08-12', 780000, 70000, 'Ekonomi', 20),
    ('Maskapai B', 'QB456', 'Airbus A320', 'CGK', 'Jakarta', 'DPS', 'Denpasar', '10:30:00', '12:20:00', 110, '2026-08-12', 850000, 70000, 'Ekonomi', 20),
    ('Maskapai C', 'QC789', 'Boeing 737-800', 'CGK', 'Jakarta', 'DPS', 'Denpasar', '14:00:00', '17:10:00', 190, '2026-08-12', 700000, 70000, 'Ekonomi', 20);

INSERT INTO promos (title, description, valid_until) VALUES
    ('Diskon Rute Domestik', 'Berlaku untuk rute domestik pilihan.', '2026-08-31'),
    ('Cashback 100K', 'Minimum transaksi Rp500.000.', '2026-08-31'),
    ('Rute Baru Jakarta-Bali', 'Mulai dari Rp850.000.', '2026-08-31');
