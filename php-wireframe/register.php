<?php
/**
 * register.php - Form Daftar pengguna
 */
require_once __DIR__ . '/includes/init.php';

if (skyfareIsLoggedIn()) {
    header('Location: booking.php');
    exit;
}

$registerError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($name === '' || $email === '' || $password === '') {
        $registerError = 'Semua kolom harus diisi.';
    } else {
        try {
            $statement = $pdo->prepare('INSERT INTO users (name, email, password_hash) VALUES (:name, :email, :password_hash)');
            $statement->execute(['name' => $name, 'email' => $email, 'password_hash' => password_hash($password, PASSWORD_DEFAULT)]);
            $_SESSION['user'] = ['id' => $pdo->lastInsertId(), 'name' => $name, 'email' => $email];
            header('Location: booking.php');
            exit;
        } catch (PDOException $error) {
            $registerError = $error->getCode() === '23000' ? 'Email sudah terdaftar.' : 'Registrasi gagal.';
        }
    }
}

$pageTitle = "Daftar - SkyFare";
include "includes/header.php";
?>

<div class="container" style="max-width:600px;">
    <div class="box">
        <h3 class="box-title">Daftar</h3>
        <?php if ($registerError): ?>
            <p style="color:#d32f2f;"><?php echo htmlspecialchars($registerError); ?></p>
        <?php endif; ?>

        <form method="post">
            <div class="field">
                <label for="name">Nama Lengkap</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">Daftar</button>
        </form>

        <p style="margin-top:20px;">Sudah punya akun? <a href="login.php">Masuk di sini</a>.</p>
    </div>
</div>

<?php include "includes/footer.php"; ?>
