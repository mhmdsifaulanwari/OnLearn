<?php
require_once '../config/database.php';
require_once '../function/helper.php';

$jenjang = isset($_GET['jenjang']) ? $_GET['jenjang'] : '';

if (!$jenjang) {
    redirect('../index.php');
}

$kelasList = [];

if ($jenjang == 'SD') {
    $kelasList = [1, 2, 3, 4, 5, 6];
} elseif ($jenjang == 'SMP') {
    $kelasList = [7, 8, 9];
} elseif ($jenjang == 'SMA/SMK') {
    $kelasList = [10, 11, 12];
} else {
    redirect('../index.php');
}

$isLoggedIn = isUserLoggedIn();
$username = $isLoggedIn ? $_SESSION['username'] : '';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Materi <?= htmlspecialchars($jenjang) ?> - OnLearn</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

    <nav class="navbar">
        <div class="logo">OnLearn</div>

        <ul class="nav-links">
            <li><a href="../index.php">Home</a></li>
            <li><a href="index.php?jenjang=<?= urlencode($jenjang) ?>">Materi</a></li>

            <?php if ($isLoggedIn): ?>
                <li><a href="../diskusi/index.php">Diskusi</a></li>
                <li><a href="../lapor_bug/index.php">Lapor Bug</a></li>
            <?php else: ?>
                <li><a href="../auth/login.php">Diskusi</a></li>
                <li><a href="../auth/login.php">Lapor Bug</a></li>
            <?php endif; ?>
        </ul>

        <div class="auth-buttons">
            <?php if ($isLoggedIn): ?>
                <span class="username">Halo, <?= htmlspecialchars($username) ?></span>
                <a href="../auth/logout.php" class="btn-outline">Logout</a>
            <?php else: ?>
                <a href="../auth/login.php" class="btn-outline">Login</a>
                <a href="../auth/register.php" class="btn-primary">Register</a>
            <?php endif; ?>
        </div>
    </nav>

    <section class="jenjang">
        <h2>Pilih Kelas <?= htmlspecialchars($jenjang) ?></h2>

        <div class="jenjang-grid">
            <?php foreach ($kelasList as $kelas): ?>
                <a
                    href="detail.php?jenjang=<?= urlencode($jenjang) ?>&kelas=<?= $kelas ?>"
                    class="jenjang-card">
                    <h3>Kelas <?= $kelas ?></h3>
                    <p>Lihat materi pembelajaran</p>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <footer>
        <p>© 2025 OnLearn. All Rights Reserved.</p>
    </footer>

</body>

</html>