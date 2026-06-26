<?php
require_once '../config/database.php';
require_once '../function/helper.php';

/** @var mysqli $conn */

$materiId = isset($_GET['materiId']) ? (int) $_GET['materiId'] : 0;

if (!$materiId) {
    redirect('../index.php');
}

$materiQuery = "SELECT * FROM materi WHERE materiId = $materiId LIMIT 1";
$materiResult = mysqli_query($conn, $materiQuery);

if (mysqli_num_rows($materiResult) == 0) {
    redirect('../index.php');
}

$materi = mysqli_fetch_assoc($materiResult);

$quizQuery = "SELECT COUNT(*) as total FROM quiz WHERE materiId = $materiId";
$quizResult = mysqli_query($conn, $quizQuery);
$totalQuiz = mysqli_fetch_assoc($quizResult)['total'];

$isLoggedIn = isUserLoggedIn();
$username = $isLoggedIn ? $_SESSION['username'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz <?= htmlspecialchars($materi['judul']) ?> - OnLearn</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<nav class="navbar">
    <div class="logo">OnLearn</div>

    <ul class="nav-links">
        <li><a href="../index.php">Home</a></li>
        <li>
            <a href="../materi/view.php?materiId=<?= $materiId ?>">
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

<section class="quiz-intro">
    <div class="quiz-card">

        <div class="quiz-icon">📝</div>

        <span class="badge">
            <?= htmlspecialchars($materi['jenjang']) ?> - Kelas <?= $materi['kelas'] ?>
        </span>

        <h1>Quiz <?= htmlspecialchars($materi['judul']) ?></h1>

        <p>
            Uji pemahamanmu setelah mempelajari materi ini.
            Jawab semua soal dengan teliti untuk mendapatkan skor terbaik.
        </p>

        <div class="quiz-stats">
            <div class="stat-box">
                <h3><?= $totalQuiz ?></h3>
                <span>Jumlah Soal</span>
            </div>

            <div class="stat-box">
                <h3>ABC D</h3>
                <span>Pilihan Jawaban</span>
            </div>

            <div class="stat-box">
                <h3>100</h3>
                <span>Skor Maksimal</span>
            </div>
        </div>

        <?php if ($totalQuiz > 0): ?>
            <a 
                href="kerjakan.php?materiId=<?= $materiId ?>"
                class="btn-primary"
            >
                Mulai Quiz
            </a>
        <?php else: ?>
            <p class="empty-quiz">Quiz belum tersedia untuk materi ini.</p>
        <?php endif; ?>

    </div>
</section>

<footer>
    <p>© 2025 OnLearn. All Rights Reserved.</p>
</footer>

</body>
</html>