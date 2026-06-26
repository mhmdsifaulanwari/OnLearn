<?php
require_once '../config/database.php';
require_once '../function/helper.php';

/** @var mysqli $conn */

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $message = "Username/email dan password harus diisi!";
    } else {

        /*
        |--------------------------------------------------------------------------
        | CEK ADMIN DULU
        |--------------------------------------------------------------------------
        */
        $adminQuery = "SELECT * FROM admins WHERE email='$email' LIMIT 1";
        $adminResult = mysqli_query($conn, $adminQuery);


        if (!$adminResult) {
            die("Admin query error: " . mysqli_error($conn));
        }

        if ($adminResult && mysqli_num_rows($adminResult) == 1) {
            $admin = mysqli_fetch_assoc($adminResult);

            if ($password === $admin['password']) {
                $_SESSION['adminId'] = $admin['adminId'];
                $_SESSION['adminUsername'] = $admin['email'];

                redirect('../admin/dashboard.php');
            } else {
                $message = "Password admin salah!";
            }
        } else {

            /*
            |--------------------------------------------------------------------------
            | CEK USER
            |--------------------------------------------------------------------------
            */
            $query = "
                SELECT * FROM users 
                WHERE email='$email' OR username='$email'
            ";

            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) == 1) {
                $user = mysqli_fetch_assoc($result);


                if (
                    $user['status'] === 'Nonaktif' &&
                    !empty($user['inactive_until']) &&
                    strtotime($user['inactive_until']) > time()
                ) {
                    $message = "Akun dinonaktifkan sampai " .
                        date('d M Y', strtotime($user['inactive_until']));
                } elseif (isAccountLocked($user['locked_until'])) {
                    $message = "Akun dikunci sementara. Coba lagi 1 menit.";
                } else {
                    if ($password === $user['password']) {

                        mysqli_query($conn, "
                            UPDATE users 
                            SET login_attempt = 0, locked_until = NULL 
                            WHERE userId = {$user['userId']}
                        ");

                        $_SESSION['userId'] = $user['userId'];
                        $_SESSION['username'] = $user['username'];

                        redirect('../index.php');
                    } else {
                        $attempt = $user['login_attempt'] + 1;

                        if ($attempt >= 3) {
                            $lockedUntil = date('Y-m-d H:i:s', strtotime('+1 minute'));

                            mysqli_query($conn, "
                                UPDATE users 
                                SET login_attempt = $attempt,
                                    locked_until = '$lockedUntil'
                                WHERE userId = {$user['userId']}
                            ");

                            $message = "Akun dikunci selama 1 menit karena 3x gagal login.";
                        } else {
                            mysqli_query($conn, "
                                UPDATE users 
                                SET login_attempt = $attempt
                                WHERE userId = {$user['userId']}
                            ");

                            $message = "Password salah! Percobaan ke-$attempt.";
                        }
                    }
                }
            } else {
                $message = "Akun tidak ditemukan!";
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
    <title>Login - OnLearn</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>

<body>

    <div class="auth-container">
        <div class="logo">OnLearn</div>
        <p class="subtitle">Login sebagai user atau admin</p>

        <?php if ($message): ?>
            <div class="message"><?= $message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <input
                    type="text"
                    name="email"
                    placeholder="Email / Username"
                    required>
            </div>

            <div class="form-group password-wrapper">
                <input
                    type="password"
                    id="loginPassword"
                    name="password"
                    placeholder="Password"
                    required>
                <span
                    class="toggle-password"
                    onclick="togglePassword('loginPassword', this)">👁</span>
            </div>

            <button type="submit">Login</button>
        </form>

        <div class="auth-link">
            <a href="forgot_password.php">Lupa kata sandi?</a>
        </div>

        <div class="auth-link">
            Belum punya akun? <a href="register.php">Register</a>
        </div>
    </div>

    <script src="..\assets\css\js\auth.js"></script>
</body>

</html>