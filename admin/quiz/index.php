<?php
require_once '../../config/database.php';
require_once '../../function/helper.php';

/** @var mysqli $conn */

requireAdminLogin();

$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';

$query = "
    SELECT
        quiz.materiId,
        materi.judul,
        materi.jenjang,
        materi.kelas,
        COUNT(quiz.quizId) AS totalSoal
    FROM quiz
    JOIN materi ON quiz.materiId = materi.materiId
";

if (!empty($search)) {
    $query .= "
        WHERE materi.judul LIKE '%$search%'
        OR materi.jenjang LIKE '%$search%'
        OR materi.kelas LIKE '%$search%'
    ";
}

$query .= "
    GROUP BY quiz.materiId
    ORDER BY quiz.materiId DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Quiz - OnLearn</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>

<body>

    <?php include '../../templates/admin_sidebar.php'; ?>

    <main class="admin-content">

        <div class="content-header">
            <h1>Kelola Quiz</h1>
            <p>Kelola seluruh quiz berdasarkan materi.</p>
        </div>

        <div class="admin-toolbar">

            <a href="tambah.php" class="btn-primary">
                + Buat Quiz
            </a>

            <div class="search-form">

                <div class="search-box">

                    <img
                        src="/finalProject/assets/icon/basic-ui.png"
                        alt="search">

                    <input
                        type="text"
                        id="searchInput"
                        placeholder="Cari materi / kelas...">

                </div>

            </div>

        </div>

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Materi</th>
                        <th>Jenjang</th>
                        <th>Kelas</th>
                        <th>Total Soal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php $no = 1; ?>

                        <?php while ($quiz = mysqli_fetch_assoc($result)): ?>
                            <tr class="table-row">
                                <td><?= $no++ ?></td>

                                <td>
                                    <?= htmlspecialchars($quiz['judul']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($quiz['jenjang']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($quiz['kelas']) ?>
                                </td>

                                <td>
                                    <?= $quiz['totalSoal'] ?> soal
                                </td>

                                <td>
                                    <div class="action-buttons">

                                        <a
                                            href="edit.php?materiId=<?= $quiz['materiId'] ?>"
                                            class="action-icon edit-icon">

                                            <img src="/finalProject/assets/icon/edit.png" alt="Kelola">

                                        </a>

                                        <a
                                            href="hapus_semua.php?materiId=<?= $quiz['materiId'] ?>"
                                            class="action-icon delete-icon"
                                            onclick="return confirm('Hapus semua soal quiz ini?')">

                                            <img src="/finalProject/assets/icon/trash.png" alt="Hapus">

                                        </a>

                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>

                    <?php else: ?>
                        <tr>
                            <td colspan="6">Belum ada quiz.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </main>
    <script>
        const searchInput = document.getElementById('searchInput');

        searchInput.addEventListener('keyup', function() {

            const keyword = this.value.toLowerCase();

            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {

                const materi =
                    row.children[1].textContent.toLowerCase();

                const jenjang =
                    row.children[2].textContent.toLowerCase();

                const kelas =
                    row.children[3].textContent.toLowerCase();

                if (
                    materi.includes(keyword) ||
                    jenjang.includes(keyword) ||
                    kelas.includes(keyword)
                ) {

                    row.style.display = '';

                } else {

                    row.style.display = 'none';
                }

            });

        });
    </script>
    <script>
        const tableRows =
            document.querySelectorAll('.table-row');

        tableRows.forEach((row, index) => {

            const cells = row.querySelectorAll('td');

            cells.forEach(cell => {

                cell.style.animationDelay =
                    `${index * 0.15}s`;

            });

        });
    </script>
</body>

</html>