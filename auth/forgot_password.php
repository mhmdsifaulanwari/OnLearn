<?php
require_once '../config/database.php';
require_once '../function/helper.php';

/** @var mysqli $conn */

$message = "";
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = sanitize($_POST['email']);
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if (empty($email) || empty($newPassword) || empty($confirmPassword)) {
        $message = "Semua field harus diisi!";
    } elseif (!isValidPassword($newPassword)) {
        $message = "Password minimal 8 karakter, harus mengandung huruf dan angka!";
    } elseif ($newPassword !== $confirmPassword) {
        $message = "Konfirmasi password tidak cocok!";
    } else {

        /*
        |--------------------------------------------------------------------------
        | CEK ADMIN
        |--------------------------------------------------------------------------
        */
        $adminCheck = mysqli_query($conn, "
            SELECT * FROM admins
            WHERE email='$email'
            LIMIT 1
        ");

        if (mysqli_num_rows($adminCheck) == 1) {
            mysqli_query($conn, "
                UPDATE admins
                SET password='$newPassword'
                WHERE email='$email'
            ");

            $success = true;
            $message = "Password admin berhasil diperbarui.";

        } else {

            /*
            |--------------------------------------------------------------------------
            | CEK USER
            |--------------------------------------------------------------------------
            */
            $userCheck = mysqli_query($conn, "
                SELECT * FROM users
                WHERE email='$email'
                LIMIT 1
            ");

            if (mysqli_num_rows($userCheck) == 1) {
                mysqli_query($conn, "
                    UPDATE users
                    SET password='$newPassword'
                    WHERE email='$email'
                ");

                $success = true;
                $message = "Password berhasil diperbarui.";
            } else {
                $message = "Mohon maaf, email tidak ditemukan.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - OnLearn</title>
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body>

<div class="auth-container">
    <div class="logo">OnLearn</div>
    <p class="subtitle">Reset kata sandi akun</p>

    <?php if ($message): ?>
        <div class="message <?= $success ? 'success-message' : '' ?>">
            <?= $message; ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <div class="form-group">
            <input
                type="email"
                name="email"
                placeholder="Masukkan email"
                required
            >
        </div>

        <div class="form-group password-wrapper">
            <input
                type="password"
                id="newPassword"
                name="new_password"
                placeholder="Password baru"
                required
            >
            <span onclick="togglePassword('newPassword', this)">👁</span>
        </div>

        <div class="form-group password-wrapper">
            <input
                type="password"
                id="confirmPassword"
                name="confirm_password"
                placeholder="Konfirmasi password"
                required
            >
            <span onclick="togglePassword('confirmPassword', this)">👁</span>
        </div>

        <button type="submit">Reset Password</button>
    </form>

    <div class="auth-link">
        <a href="login.php">← Kembali ke login</a>
    </div>
</div>

<script src="..\assets\css\js\auth.js"></script>

</body>
</html>