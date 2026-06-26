<?php
require_once '../config/database.php';
require_once '../function/helper.php';

/** @var mysqli $conn */

$materiId = isset($_GET['materiId']) ? (int) $_GET['materiId'] : 0;

if (!$materiId) {
    redirect('../index.php');
}

$query = "SELECT * FROM materi WHERE materiId = $materiId LIMIT 1";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    redirect('../index.php');
}

$materi = mysqli_fetch_assoc($result);

$isLoggedIn = isUserLoggedIn();
$username = $isLoggedIn ? $_SESSION['username'] : '';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($materi['judul']) ?> - OnLearn</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

    <nav class="navbar">
        <div class="logo">OnLearn</div>

        <ul class="nav-links">
            <li><a href="../index.php">Home</a></li>
            <li>
                <a href="detail.php?jenjang=<?= urlencode($materi['jenjang']) ?>&kelas=<?= $materi['kelas'] ?>">
                    Materi
                </a>
            </li>

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

    <section class="materi-view">
        <div class="materi-content-card">

            <a
                href="detail.php?jenjang=<?= urlencode($materi['jenjang']) ?>&kelas=<?= $materi['kelas'] ?>"
                class="close-btn">
                ✕
            </a>

            <span class="badge">
                <?= htmlspecialchars($materi['jenjang']) ?> - Kelas <?= $materi['kelas'] ?>
            </span>

            <h1><?= htmlspecialchars($materi['judul']) ?></h1>

            <div class="materi-text">
                <?= $materi['isiMateri'] ?>
            </div>

            <?php if (!empty($materi['fileMateri'])): ?>

                <?php
                $ext = strtolower(pathinfo($materi['fileMateri'], PATHINFO_EXTENSION));
                ?>

                <?php if (in_array($ext, ['jpg', 'jpeg', 'png'])): ?>

                    <img
                        src="../assets/uploads/<?= $materi['fileMateri'] ?>"
                        style="max-width:100%; margin-top:20px; border-radius:10px;">

                <?php else: ?>

                    <div style="margin-top:20px;">
                        <a
                            href="../assets/uploads/<?= $materi['fileMateri'] ?>"
                            target="_blank"
                            class="btn-primary">
                            Download File Materi
                        </a>
                    </div>

                <?php endif; ?>

            <?php endif; ?>

            <div class="hero-buttons">
                <a
                    href="../quiz/index.php?materiId=<?= $materi['materiId'] ?>"
                    class="btn-primary">
                    Kerjakan Quiz
                </a>
            </div>

        </div>
    </section>

    <footer>
        <p>© 2025 OnLearn. All Rights Reserved.</p>
    </footer>

</body>

</html>