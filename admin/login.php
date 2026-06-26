<?php
require_once '../config/database.php';
require_once '../function/helper.php';

/** @var mysqli $conn */

if (isAdminLoggedIn()) {
    redirect('dashboard.php');
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $message = "Username dan password harus diisi!";
    } else {
        $query = "
            SELECT * FROM admins
            WHERE username = '$username'
            LIMIT 1
        ";

        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {
            $admin = mysqli_fetch_assoc($result);

            if (password_verify($password, $admin['password'])) {
                $_SESSION['adminId'] = $admin['adminId'];
                $_SESSION['adminUsername'] = $admin['username'];

                redirect('dashboard.php');
            } else {
                $message = "Password admin salah!";
            }
        } else {
            $message = "Username admin tidak ditemukan!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - OnLearn</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>

<body>

    <div class="auth-container">
        <div class="logo">OnLearn Admin</div>
        <p class="subtitle">Masuk ke dashboard admin</p>

        <?php if ($message): ?>
            <div class="message"><?= $message ?></div>
        <?php endif; ?>

        <form method="POST">

            <div class="form-group">
                <input
                    type="text"
                    name="username"
                    placeholder="Username Admin"
                    required>
            </div>

            <div class="form-group password-group">
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Password"
                    required>
                <span onclick="togglePassword('password', this)">👁</span>
            </div>

            <button type="submit">Login Admin</button>

        </form>

        <div class="auth-link">
            <a href="../index.php">← Kembali ke OnLearn</a>
        </div>
    </div>

    <script src="/finalProject/assets/js/auth.js"></script>

</body>

</html>