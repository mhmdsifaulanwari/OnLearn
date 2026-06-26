<?php
require_once '../config/database.php';
require_once '../function/helper.php';

/** @var mysqli $conn */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../index.php');
}

$materiId = isset($_POST['materiId']) ? (int) $_POST['materiId'] : 0;
$jawabanUser = $_POST['jawaban'] ?? [];

if (!$materiId) {
    redirect('../index.php');
}

$materiQuery = "SELECT * FROM materi WHERE materiId = $materiId LIMIT 1";
$materiResult = mysqli_query($conn, $materiQuery);

if (mysqli_num_rows($materiResult) == 0) {
    redirect('../index.php');
}

$materi = mysqli_fetch_assoc($materiResult);

$quizQuery = "SELECT * FROM quiz WHERE materiId = $materiId";
$quizResult = mysqli_query($conn, $quizQuery);

$totalSoal = mysqli_num_rows($quizResult);
$benar = 0;
$salah = 0;

while ($soal = mysqli_fetch_assoc($quizResult)) {
    $quizId = $soal['quizId'];
    $jawabanBenar = strtoupper($soal['jawabanBenar']);
    $jawabanDipilih = $jawabanUser[$quizId] ?? '';

    if ($jawabanDipilih === $jawabanBenar) {
        $benar++;
    } else {
        $salah++;
    }
}

$skor = $totalSoal > 0 ? round(($benar / $totalSoal) * 100) : 0;

if ($skor >= 80) {
    $pesan = "Luar biasa! Pemahamanmu sangat baik 🎉";
} elseif ($skor >= 60) {
    $pesan = "Bagus! Tetap semangat belajar 💪";
} else {
    $pesan = "Jangan menyerah, coba pelajari lagi materinya 📚";
}

$isLoggedIn = isUserLoggedIn();
$username = $isLoggedIn ? $_SESSION['username'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Quiz - OnLearn</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<nav class="navbar">
    <div class="logo">OnLearn</div>

    <ul class="nav-links">
        <li><a href="../index.php">Home</a></li>
        <li><a href="../materi/view.php?materiId=<?= $materiId ?>">Materi</a></li>
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

<section class="hasil-section">
    <div class="hasil-card">

        <div class="hasil-icon">🏆</div>

        <span class="badge">
            <?= htmlspecialchars($materi['judul']) ?>
        </span>

        <h1>Skor Kamu: <?= $skor ?></h1>

        <p><?= $pesan ?></p>

        <div class="quiz-stats">
            <div class="stat-box">
                <h3><?= $benar ?></h3>
                <span>Jawaban Benar</span>
            </div>

            <div class="stat-box">
                <h3><?= $salah ?></h3>
                <span>Jawaban Salah</span>
            </div>

            <div class="stat-box">
                <h3><?= $totalSoal ?></h3>
                <span>Total Soal</span>
            </div>
        </div>

        <div class="hero-buttons">
            <a href="kerjakan.php?materiId=<?= $materiId ?>" class="btn-primary">
                Ulangi Quiz
            </a>

            <a href="../materi/view.php?materiId=<?= $materiId ?>" class="btn-outline">
                Kembali ke Materi
            </a>
        </div>

    </div>
</section>

<footer>
    <p>© 2025 OnLearn. All Rights Reserved.</p>
</footer>

</body>
</html>