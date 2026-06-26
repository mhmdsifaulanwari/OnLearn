<?php
require_once '../../config/database.php';
require_once '../../function/helper.php';

/** @var mysqli $conn */

requireAdminLogin();

if (!isset($_GET['materiId'])) {
    redirect('index.php');
}

$materiId = (int) $_GET['materiId'];

$getMateri = mysqli_query($conn, "
    SELECT * FROM materi
    WHERE materiId = $materiId
    LIMIT 1
");

if (mysqli_num_rows($getMateri) == 0) {
    redirect('index.php');
}

$materi = mysqli_fetch_assoc($getMateri);

$getQuiz = mysqli_query($conn, "
    SELECT * FROM quiz
    WHERE materiId = $materiId
    ORDER BY quizId ASC
");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($_POST['quizId'] as $index => $quizId) {
        $quizId = (int) $quizId;

        $pertanyaan = sanitize($_POST['pertanyaan'][$index]);
        $pilihanA = sanitize($_POST['pilihanA'][$index]);
        $pilihanB = sanitize($_POST['pilihanB'][$index]);
        $pilihanC = sanitize($_POST['pilihanC'][$index]);
        $pilihanD = sanitize($_POST['pilihanD'][$index]);
        $jawabanBenar = sanitize($_POST['jawabanBenar'][$index]);

        mysqli_query($conn, "
            UPDATE quiz
            SET
                pertanyaan='$pertanyaan',
                pilihanA='$pilihanA',
                pilihanB='$pilihanB',
                pilihanC='$pilihanC',
                pilihanD='$pilihanD',
                jawabanBenar='$jawabanBenar'
            WHERE quizId = $quizId
        ");
    }

    redirect('index.php');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Quiz - OnLearn</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>

<?php include '../../templates/admin_sidebar.php'; ?>

<main class="admin-content">

    <div class="content-header">
        <h1>Kelola Soal Quiz</h1>
        <p>
            Materi:
            <strong><?= htmlspecialchars($materi['judul']) ?></strong>
            (<?= $materi['jenjang'] ?> - Kelas <?= $materi['kelas'] ?>)
        </p>
    </div>

    <div class="form-card">

        <form method="POST" class="admin-form">

            <?php
            $no = 1;
            while ($quiz = mysqli_fetch_assoc($getQuiz)):
            ?>

                <div class="quiz-box">
                    <h3>Soal <?= $no++ ?></h3>

                    <input
                        type="hidden"
                        name="quizId[]"
                        value="<?= $quiz['quizId'] ?>"
                    >

                    <div class="form-group-admin">
                        <label>Pertanyaan</label>
                        <textarea
                            name="pertanyaan[]"
                            rows="3"
                            required
                        ><?= htmlspecialchars($quiz['pertanyaan']) ?></textarea>
                    </div>

                    <div class="form-group-admin">
                        <label>Pilihan A</label>
                        <input
                            type="text"
                            name="pilihanA[]"
                            value="<?= htmlspecialchars($quiz['pilihanA']) ?>"
                            required
                        >
                    </div>

                    <div class="form-group-admin">
                        <label>Pilihan B</label>
                        <input
                            type="text"
                            name="pilihanB[]"
                            value="<?= htmlspecialchars($quiz['pilihanB']) ?>"
                            required
                        >
                    </div>

                    <div class="form-group-admin">
                        <label>Pilihan C</label>
                        <input
                            type="text"
                            name="pilihanC[]"
                            value="<?= htmlspecialchars($quiz['pilihanC']) ?>"
                            required
                        >
                    </div>

                    <div class="form-group-admin">
                        <label>Pilihan D</label>
                        <input
                            type="text"
                            name="pilihanD[]"
                            value="<?= htmlspecialchars($quiz['pilihanD']) ?>"
                            required
                        >
                    </div>

                    <div class="form-group-admin">
                        <label>Jawaban Benar</label>
                        <select name="jawabanBenar[]">
                            <option value="A" <?= $quiz['jawabanBenar'] == 'A' ? 'selected' : '' ?>>A</option>
                            <option value="B" <?= $quiz['jawabanBenar'] == 'B' ? 'selected' : '' ?>>B</option>
                            <option value="C" <?= $quiz['jawabanBenar'] == 'C' ? 'selected' : '' ?>>C</option>
                            <option value="D" <?= $quiz['jawabanBenar'] == 'D' ? 'selected' : '' ?>>D</option>
                        </select>
                    </div>

                    <div style="margin-top: 15px;">
                        <a
                            href="hapus.php?id=<?= $quiz['quizId'] ?>&materiId=<?= $materiId ?>"
                            class="action-delete"
                            onclick="return confirm('Hapus soal ini?')"
                        >
                            Hapus Soal Ini
                        </a>
                    </div>
                </div>

            <?php endwhile; ?>

            <div class="form-actions">
                <a href="index.php" class="btn-secondary">Kembali</a>
                <button type="submit" class="btn-primary">Update Semua Soal</button>
            </div>

        </form>

    </div>

</main>

</body>
</html>