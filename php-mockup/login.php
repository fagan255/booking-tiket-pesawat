<?php
require_once __DIR__ . '/includes/init.php';

$loginError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $statement = $pdo->prepare('SELECT id, name, email, password_hash FROM users WHERE email = :email');
    $statement->execute(['email' => $email]);
    $user = $statement->fetch();
    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user'] = ['id' => $user['id'], 'name' => $user['name'], 'email' => $user['email']];
        header('Location: index.php');
        exit;
    }
    $loginError = 'Email atau password salah.';
}

$pageTitle = "Masuk - SkyFare";
include "includes/header.php";
?>

<div class="container auth-shell">
    <div class="card auth-card">
        <h2 class="auth-title">Masuk ke akun Anda</h2>
        <p class="auth-subtitle">Akses booking, tiket, dan promo terbaru dengan satu akun.</p>

        <?php if ($loginError): ?><p class="helper-text"><?php echo htmlspecialchars($loginError); ?></p><?php endif; ?>
        <form method="post">
            <div class="field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="nama@email.com" required>
            </div>
            <div class="field">
                <label for="password">Kata Sandi</label>
                <input type="password" id="password" name="password" placeholder="Masukkan kata sandi" required>
            </div>
            <button type="submit" class="btn">MASUK</button>
        </form>

        <p class="helper-text">
            Belum punya akun? <a href="register.php">Daftar sekarang</a>
        </p>
    </div>
</div>

<?php include "includes/footer.php"; ?>
