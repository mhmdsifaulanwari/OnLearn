<?php
require_once '../config/database.php';
require_once '../function/helper.php';

/** @var mysqli $conn */

$jenjang = isset($_GET['jenjang']) ? sanitize($_GET['jenjang']) : '';
$kelas = isset($_GET['kelas']) ? (int) $_GET['kelas'] : 0;

if (!$jenjang || !$kelas) {
    redirect('../index.php');
}

$query = "
    SELECT * FROM materi
    WHERE jenjang='$jenjang'
    AND kelas=$kelas
    ORDER BY judul ASC
";

$result = mysqli_query($conn, $query);

$isLoggedIn = isUserLoggedIn();
$username = $isLoggedIn ? $_SESSION['username'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Materi <?= htmlspecialchars($jenjang) ?> Kelas <?= $kelas ?></title>
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

<section class="materi-list">
    <div class="materi-header">
        <h1><?= htmlspecialchars($jenjang) ?> - Kelas <?= $kelas ?></h1>
        <p>Pilih mata pelajaran untuk mulai belajar</p>
    </div>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="materi-grid">
            <?php while ($materi = mysqli_fetch_assoc($result)): ?>
                <a 
                    href="view.php?materiId=<?= $materi['materiId'] ?>"
                    class="materi-item"
                >
                    <h3><?= htmlspecialchars($materi['judul']) ?></h3>
                    <p>Klik untuk membaca materi</p>
                </a>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <h2>Materi belum tersedia</h2>
            <p>Admin belum menambahkan materi untuk kelas ini.</p>
        </div>
    <?php endif; ?>
</section>

<footer>
    <p>© 2025 OnLearn. All Rights Reserved.</p>
</footer>

</body>
</html>