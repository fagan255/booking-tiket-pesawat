<?php
/**
 * login.php - Form Masuk pengguna
 */
require_once __DIR__ . '/includes/init.php';

if (skyfareIsLoggedIn()) {
    header('Location: booking.php');
    exit;
}

$loginError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        $loginError = 'Email dan password harus diisi.';
    } else {
        $_SESSION['user'] = [
            'name' => strstr($email, '@', true) ?: $email,
            'email' => $email,
        ];
        header('Location: booking.php');
        exit;
    }
}

$pageTitle = "Masuk - SkyFare";
include "includes/header.php";
?>

<div class="container" style="max-width:600px;">
    <div class="box">
        <h3 class="box-title">Masuk</h3>
        <?php if ($loginError): ?>
            <p style="color:#d32f2f;"><?php echo htmlspecialchars($loginError); ?></p>
        <?php endif; ?>

        <form method="post">
            <div class="field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">Masuk</button>
        </form>

        <p style="margin-top:20px;">Belum punya akun? <a href="register.php">Daftar di sini</a>.</p>
    </div>
</div>

<?php include "includes/footer.php"; ?>
