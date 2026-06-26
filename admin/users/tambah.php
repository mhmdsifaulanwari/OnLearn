<?php
require_once '../../config/database.php';
require_once '../../function/helper.php';

/** @var mysqli $conn */

requireAdminLogin();

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
            WHERE username='$username' OR email='$email'
        ");

        if (mysqli_num_rows($check) > 0) {
            $message = "Username atau email sudah digunakan!";
        } else {
            $query = "
                INSERT INTO users (
                    username,
                    email,
                    password,
                    login_attempt,
                    locked_until
                )
                VALUES (
                    '$username',
                    '$email',
                    '$password',
                    0,
                    NULL
                )
            ";

            if (mysqli_query($conn, $query)) {
                redirect('index.php');
            } else {
                $message = "Gagal menambahkan user!";
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
    <title>Tambah User - OnLearn</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>

<body>

    <?php include '../../templates/admin_sidebar.php'; ?>

    <main class="admin-content">

        <div class="content-header">
            <h1>Tambah User</h1>
            <p>Tambahkan akun pengguna baru.</p>
        </div>

        <div class="form-card">

            <?php if ($message): ?>
                <div class="error-message"><?= $message ?></div>
            <?php endif; ?>

            <form method="POST" class="admin-form">

                <div class="form-group-admin">
                    <label>Username</label>
                    <input
                        type="text"
                        name="username"
                        required>
                </div>

                <div class="form-group-admin">
                    <label>Email</label>
                    <input
                        type="email"
                        name="email"
                        required>
                </div>

                <div class="form-group-admin">
                    <label>Password</label>
                    <input
                        type="text"
                        name="password"
                        required>
                </div>

                <div class="form-actions">
                    <a href="index.php" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">Simpan</button>
                </div>

            </form>

        </div>

    </main>

</body>

</html>