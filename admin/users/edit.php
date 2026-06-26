<?php
require_once '../../config/database.php';
require_once '../../function/helper.php';

/** @var mysqli $conn */

requireAdminLogin();

if (!isset($_GET['id'])) {
    redirect('index.php');
}

$userId = (int) $_GET['id'];

$getUser = mysqli_query($conn, "
    SELECT * FROM users
    WHERE userId = $userId
    LIMIT 1
");

if (mysqli_num_rows($getUser) == 0) {
    redirect('index.php');
}

$user = mysqli_fetch_assoc($getUser);
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = sanitize($_POST['username']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $statusOption = $_POST['status'];

    if (empty($username) || empty($email)) {
        $message = "Username dan email wajib diisi!";
    } elseif (!isValidEmail($email)) {
        $message = "Format email tidak valid!";
    } else {

        $check = mysqli_query($conn, "
            SELECT * FROM users
            WHERE (username='$username' OR email='$email')
            AND userId != $userId
        ");

        if (mysqli_num_rows($check) > 0) {
            $message = "Username atau email sudah digunakan!";
        } else {

            $status = "Aktif";
            $inactiveUntil = "NULL";

            if ($statusOption == "1tahun") {
                $status = "Nonaktif";
                $inactiveUntil = "'" . date('Y-m-d H:i:s', strtotime('+1 year')) . "'";
            } elseif ($statusOption == "2tahun") {
                $status = "Nonaktif";
                $inactiveUntil = "'" . date('Y-m-d H:i:s', strtotime('+2 years')) . "'";
            } elseif ($statusOption == "permanen") {
                $status = "Nonaktif";
                $inactiveUntil = "'2099-12-31 23:59:59'";
            }

            $query = "
                UPDATE users
                SET
                    username='$username',
                    email='$email',
                    status='$status',
                    inactive_until=$inactiveUntil
            ";

            if (!empty($password)) {
                $query .= ", password='$password'";
            }

            $query .= " WHERE userId=$userId";

            if (mysqli_query($conn, $query)) {
                redirect('index.php');
            } else {
                $message = "Gagal update user.";
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
    <title>Edit User - OnLearn</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>

<?php include '../../templates/admin_sidebar.php'; ?>

<main class="admin-content">

    <div class="content-header">
        <h1>Edit User</h1>
        <p>Kelola akun pengguna.</p>
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
                    value="<?= htmlspecialchars($user['username']) ?>"
                    required
                >
            </div>

            <div class="form-group-admin">
                <label>Email</label>
                <input
                    type="email"
                    name="email"
                    value="<?= htmlspecialchars($user['email']) ?>"
                    required
                >
            </div>

            <div class="form-group-admin">
                <label>Password Baru (opsional)</label>
                <input
                    type="text"
                    name="password"
                    placeholder="Kosongkan jika tidak diubah"
                >
            </div>

            <div class="form-group-admin">
                <label>Status Akun</label>
                <select name="status">
                    <option value="aktif">Aktif</option>
                    <option value="1tahun">Nonaktif 1 Tahun</option>
                    <option value="2tahun">Nonaktif 2 Tahun</option>
                    <option value="permanen">Nonaktif Permanen</option>
                </select>
            </div>

            <div class="form-actions">
                <a href="index.php" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Update</button>
            </div>

        </form>

    </div>

</main>

</body>
</html>