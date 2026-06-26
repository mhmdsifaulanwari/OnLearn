<?php
require_once '../../config/database.php';
require_once '../../function/helper.php';

/** @var mysqli $conn */

requireAdminLogin();

$message = "";

$materiList = mysqli_query($conn, "
    SELECT * FROM materi
    ORDER BY judul ASC
");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $materiId = (int) $_POST['materiId'];

    if (empty($materiId)) {
        $message = "Pilih materi terlebih dahulu!";
    } else {
        if (isset($_POST['pertanyaan']) && count($_POST['pertanyaan']) > 0) {

            foreach ($_POST['pertanyaan'] as $index => $pertanyaan) {
                $pertanyaan = sanitize($pertanyaan);
                $pilihanA = sanitize($_POST['pilihanA'][$index]);
                $pilihanB = sanitize($_POST['pilihanB'][$index]);
                $pilihanC = sanitize($_POST['pilihanC'][$index]);
                $pilihanD = sanitize($_POST['pilihanD'][$index]);
                $jawabanBenar = sanitize($_POST['jawabanBenar'][$index]);

                if (
                    !empty($pertanyaan) &&
                    !empty($pilihanA) &&
                    !empty($pilihanB) &&
                    !empty($pilihanC) &&
                    !empty($pilihanD)
                ) {
                    mysqli_query($conn, "
                        INSERT INTO quiz (
                            materiId,
                            pertanyaan,
                            pilihanA,
                            pilihanB,
                            pilihanC,
                            pilihanD,
                            jawabanBenar
                        )
                        VALUES (
                            '$materiId',
                            '$pertanyaan',
                            '$pilihanA',
                            '$pilihanB',
                            '$pilihanC',
                            '$pilihanD',
                            '$jawabanBenar'
                        )
                    ");
                }
            }

            redirect('index.php');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Quiz - OnLearn</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>

<body>

    <?php include '../../templates/admin_sidebar.php'; ?>

    <main class="admin-content">

        <div class="content-header">
            <h1>Buat Quiz</h1>
            <p>Tambahkan banyak soal sekaligus.</p>
        </div>

        <div class="form-card">

            <?php if ($message): ?>
                <div class="error-message"><?= $message ?></div>
            <?php endif; ?>

            <form method="POST" class="admin-form">

                <div class="form-group-admin">
                    <label>Pilih Materi</label>
                    <select name="materiId" required>
                        <option value="">Pilih Materi</option>

                        <?php while ($materi = mysqli_fetch_assoc($materiList)): ?>
                            <option value="<?= $materi['materiId'] ?>">
                                <?= htmlspecialchars($materi['judul']) ?>
                                (<?= $materi['jenjang'] ?> - Kelas <?= $materi['kelas'] ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div id="quizContainer">

                    <div class="quiz-box">
                        <div class="quiz-header">

                            <h3>Soal 1</h3>

                            <button
                                type="button"
                                class="remove-question"
                                onclick="removeQuestion(this)">

                                ✕

                            </button>

                        </div>

                        <div class="form-group-admin">
                            <label>Pertanyaan</label>
                            <textarea name="pertanyaan[]" rows="3" required></textarea>
                        </div>

                        <div class="form-group-admin">
                            <label>Pilihan A</label>
                            <input type="text" name="pilihanA[]" required>
                        </div>

                        <div class="form-group-admin">
                            <label>Pilihan B</label>
                            <input type="text" name="pilihanB[]" required>
                        </div>

                        <div class="form-group-admin">
                            <label>Pilihan C</label>
                            <input type="text" name="pilihanC[]" required>
                        </div>

                        <div class="form-group-admin">
                            <label>Pilihan D</label>
                            <input type="text" name="pilihanD[]" required>
                        </div>

                        <div class="form-group-admin">
                            <label>Jawaban Benar</label>
                            <select name="jawabanBenar[]" required>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                            </select>
                        </div>
                    </div>
                </div>
                <button
                    type="button"
                    class="btn-secondary add-question-btn"
                    onclick="addQuestion()">

                    + Tambah Soal

                </button>

                <div class="form-actions">

                    <a href="index.php" class="btn-secondary">
                        Batal
                    </a>

                    <button type="submit" class="btn-primary">
                        Simpan Quiz
                    </button>

                </div>

            </form>

        </div>

    </main>

    <script>
        let questionCount = 1;

        function addQuestion() {

            questionCount++;

            const container =
                document.getElementById('quizContainer');

            const html = `

        <div class="quiz-box">

            <div class="quiz-header">

                <h3>Soal ${questionCount}</h3>

                <button
                    type="button"
                    class="remove-question"
                    onclick="removeQuestion(this)">

                    ✕

                </button>

            </div>

            <div class="form-group-admin">
                <label>Pertanyaan</label>
                <textarea name="pertanyaan[]" rows="3" required></textarea>
            </div>

            <div class="form-group-admin">
                <label>Pilihan A</label>
                <input type="text" name="pilihanA[]" required>
            </div>

            <div class="form-group-admin">
                <label>Pilihan B</label>
                <input type="text" name="pilihanB[]" required>
            </div>

            <div class="form-group-admin">
                <label>Pilihan C</label>
                <input type="text" name="pilihanC[]" required>
            </div>

            <div class="form-group-admin">
                <label>Pilihan D</label>
                <input type="text" name="pilihanD[]" required>
            </div>

            <div class="form-group-admin">
                <label>Jawaban Benar</label>

                <select name="jawabanBenar[]" required>

                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>

                </select>

            </div>

        </div>
        `;

            container.insertAdjacentHTML('beforeend', html);
        }

        function removeQuestion(button) {

            const quizBox = button.closest('.quiz-box');

            quizBox.remove();

            updateQuestionNumbers();
        }

        function updateQuestionNumbers() {

            const quizBoxes =
                document.querySelectorAll('.quiz-box');

            quizBoxes.forEach((box, index) => {

                box.querySelector('h3').innerText =
                    `Soal ${index + 1}`;

            });

            questionCount = quizBoxes.length;
        }
    </script>

</body>

</html>