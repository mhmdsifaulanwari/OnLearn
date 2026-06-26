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

$quizQuery = "SELECT * FROM quiz WHERE materiId = $materiId ORDER BY quizId ASC";
$quizResult = mysqli_query($conn, $quizQuery);

if (mysqli_num_rows($quizResult) == 0) {
    redirect('index.php?materiId=' . $materiId);
}

$isLoggedIn = isUserLoggedIn();
$username = $isLoggedIn ? $_SESSION['username'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kerjakan Quiz - OnLearn</title>
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

<section class="quiz-section">
    <div class="quiz-form-card">
        <h1>Quiz <?= htmlspecialchars($materi['judul']) ?></h1>
        <p>Jawab semua soal berikut dengan benar.</p>

        <form action="hasil.php" method="POST">
            <input type="hidden" name="materiId" value="<?= $materiId ?>">

            <?php 
            $nomor = 1;
            while ($soal = mysqli_fetch_assoc($quizResult)): 
            ?>
                <div class="question-card">
                    <h3><?= $nomor ?>. <?= htmlspecialchars($soal['pertanyaan']) ?></h3>

                    <label class="option">
                        <input type="radio" name="jawaban[<?= $soal['quizId'] ?>]" value="A" required>
                        A. <?= htmlspecialchars($soal['pilihanA']) ?>
                    </label>

                    <label class="option">
                        <input type="radio" name="jawaban[<?= $soal['quizId'] ?>]" value="B">
                        B. <?= htmlspecialchars($soal['pilihanB']) ?>
                    </label>

                    <label class="option">
                        <input type="radio" name="jawaban[<?= $soal['quizId'] ?>]" value="C">
                        C. <?= htmlspecialchars($soal['pilihanC']) ?>
                    </label>

                    <label class="option">
                        <input type="radio" name="jawaban[<?= $soal['quizId'] ?>]" value="D">
                        D. <?= htmlspecialchars($soal['pilihanD']) ?>
                    </label>
                </div>
            <?php 
                $nomor++;
            endwhile; 
            ?>

            <button type="submit" class="btn-primary">
                Submit Quiz
            </button>
        </form>
    </div>
</section>

<footer>
    <p>© 2025 OnLearn. All Rights Reserved.</p>
</footer>

</body>
</html>