<?php
require_once '../config/database.php';
require_once '../function/helper.php';

/** @var mysqli $conn */

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = sanitize($_POST['username']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];

    if (empty($username) || empty($email) || empty($password)) {
        $message = "Semua field harus diisi!";
    } elseif (!isValidEmail($email)) {
        $message = "Format email tidak valid!";
    } elseif (!isValidPassword($password)) {
        $message = "Password minimal 8 karakter, harus mengandung huruf dan angka!";
    } else {
        $check = mysqli_query($conn, "
            SELECT * FROM users 
            WHERE email='$email' OR username='$username'
        ");

        if (mysqli_num_rows($check) > 0) {
             $message = "Username atau email sudah digunakan!";
            } else {
                $query = "INSERT INTO users (username, email, password)
                VALUES ('$username', '$email', '$password')";

            if (mysqli_query($conn, $query)) {
                echo showAlert("Registrasi berhasil!");
                redirect("login.php");
            } else {
                $message = "Registrasi gagal!";
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
    <title>Register - OnLearn</title>
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body>

<div class="auth-container">
    <div class="logo">OnLearn</div>
    <p class="subtitle">Buat akun untuk mulai belajar</p>

    <?php if ($message): ?>
        <div class="message"><?= $message; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <input type="text" name="username" placeholder="Username" required>
        </div>

        <div class="form-group">
            <input type="email" name="email" placeholder="Email" required>
        </div>

        <div class="form-group password-wrapper">
            <input 
                type="password"
                id="registerPassword"
                name="password"
                placeholder="Password"
                required
            >
            <span 
                class="toggle-password"
                onclick="togglePassword('registerPassword', this)"
            >👁</span>
        </div>

        <button type="submit">Daftar</button>
    </form>

    <div class="auth-link">
        Sudah punya akun? <a href="login.php">Login</a>
    </div>
</div>

<script src="..\assets\css\js\auth.js"></script>
</body>
</html>