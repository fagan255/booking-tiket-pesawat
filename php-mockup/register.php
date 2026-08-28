<?php
require_once __DIR__ . '/includes/init.php';

$registerError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    try {
        $statement = $pdo->prepare('INSERT INTO users (name, email, password_hash) VALUES (:name, :email, :password_hash)');
        $statement->execute(['name' => $name, 'email' => $email, 'password_hash' => password_hash($password, PASSWORD_DEFAULT)]);
        $_SESSION['user'] = ['id' => $pdo->lastInsertId(), 'name' => $name, 'email' => $email];
        header('Location: index.php');
        exit;
    } catch (PDOException $error) {
        $registerError = $error->getCode() === '23000' ? 'Email sudah terdaftar.' : 'Registrasi gagal.';
    }
}

$pageTitle = "Daftar - SkyFare";
include "includes/header.php";
?>

<div class="container auth-shell">
    <div class="card auth-card">
        <h2 class="auth-title">Buat akun SkyFare</h2>
        <p class="auth-subtitle">Daftar sekarang untuk menikmati pencarian cepat dan promo eksklusif.</p>

        <?php if ($registerError): ?><p class="helper-text"><?php echo htmlspecialchars($registerError); ?></p><?php endif; ?>
        <form method="post">
            <div class="field">
                <label for="nama">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" placeholder="Contoh: Budi Santoso" required>
            </div>
            <div class="field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="nama@email.com" required>
            </div>
            <div class="field">
                <label for="password">Kata Sandi</label>
                <input type="password" id="password" name="password" placeholder="Buat kata sandi" required>
            </div>
            <button type="submit" class="btn">DAFTAR</button>
        </form>

        <p class="helper-text">
            Sudah punya akun? <a href="login.php">Masuk di sini</a>
        </p>
    </div>
</div>

<?php include "includes/footer.php"; ?>
