<?php
require_once '../../config/database.php';
require_once '../../function/helper.php';

/** @var mysqli $conn */

requireAdminLogin();

$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';

$query = "SELECT * FROM materi";

if (!empty($search)) {
    $query .= "
        WHERE judul LIKE '%$search%'
        OR isiMateri LIKE '%$search%'
        OR jenjang LIKE '%$search%'
        OR kelas LIKE '%$search%'
    ";
}

$query .= " ORDER BY materiId DESC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Materi - OnLearn</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>

<body>

    <?php include '../../templates/admin_sidebar.php'; ?>

    <main class="admin-content">

        <div class="content-header">
            <h1>Kelola Materi</h1>
            <p>Kelola seluruh materi pembelajaran dan file pendukung.</p>
        </div>

        <div class="admin-toolbar">
            <a href="tambah.php" class="btn-primary">+ Tambah Materi</a>

            <div class="search-form">

                <div class="search-box">

                    <img
                        src="/finalProject/assets/icon/basic-ui.png"
                        alt="search">

                    <input
                        type="text"
                        id="searchInput"
                        placeholder="Cari materi...">

                </div>

            </div>
        </div>

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Preview Isi</th>
                        <th>Jenjang</th>
                        <th>Kelas</th>
                        <th>File</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php $no = 1; ?>
                        <?php while ($materi = mysqli_fetch_assoc($result)): ?>
                            <tr class="table-row">
                                <td><?= $no++ ?></td>

                                <td>
                                    <?= htmlspecialchars($materi['judul']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        substr(strip_tags($materi['isiMateri']), 0, 80)
                                    ) ?>...
                                </td>

                                <td>
                                    <?= htmlspecialchars($materi['jenjang']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($materi['kelas']) ?>
                                </td>

                                <td>
                                    <?php if (!empty($materi['fileMateri'])): ?>
                                        <a
                                            href="../../assets/uploads/materi/<?= urlencode($materi['fileMateri']) ?>"
                                            target="_blank"
                                            class="action-edit">
                                            Lihat File
                                        </a>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <div class="action-buttons">

                                        <a
                                            href="edit.php?id=<?= $materi['materiId'] ?>"
                                            class="action-icon edit-icon">

                                            <img src="/finalProject/assets/icon/edit.png" alt="Edit">

                                        </a>

                                        <a
                                            href="hapus.php?id=<?= $materi['materiId'] ?>"
                                            class="action-icon delete-icon"
                                            onclick="return confirm('Hapus materi ini?')">

                                            <img src="/finalProject/assets/icon/trash.png" alt="Hapus">

                                        </a>

                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">Tidak ada data materi.</td>
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

                const judul =
                    row.children[1].textContent.toLowerCase();

                const isi =
                    row.children[2].textContent.toLowerCase();

                const jenjang =
                    row.children[3].textContent.toLowerCase();

                const kelas =
                    row.children[4].textContent.toLowerCase();

                if (
                    judul.includes(keyword) ||
                    isi.includes(keyword) ||
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