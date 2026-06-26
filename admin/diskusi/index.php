<?php
require_once '../../config/database.php';
require_once '../../function/helper.php';

/** @var mysqli $conn */

requireAdminLogin();

$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';

$query = "
    SELECT
        diskusi.*,
        users.username
    FROM diskusi
    JOIN users ON diskusi.userId = users.userId
";

if (!empty($search)) {
    $query .= "
        WHERE users.username LIKE '%$search%'
        OR diskusi.komentar LIKE '%$search%'
    ";
}

$query .= " ORDER BY diskusi.tanggal DESC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Diskusi - OnLearn</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>

<body>

    <?php include '../../templates/admin_sidebar.php'; ?>

    <main class="admin-content">

        <div class="content-header">
            <h1>Kelola Diskusi</h1>
            <p>Moderasi komentar pengguna pada forum diskusi.</p>
        </div>

        <div class="admin-toolbar">
            <div class="search-form">

                <div class="search-box">

                    <img
                        src="/finalProject/assets/icon/basic-ui.png"
                        alt="search">

                    <input
                        type="text"
                        id="searchInput"
                        placeholder="Cari user atau komentar...">

                </div>

            </div>
        </div>

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>User</th>
                        <th>Komentar</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php $no = 1; ?>

                        <?php while ($diskusi = mysqli_fetch_assoc($result)): ?>
                            <tr class="table-row">
                                <td><?= $no++ ?></td>

                                <td>
                                    <?= htmlspecialchars($diskusi['username']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        substr($diskusi['komentar'], 0, 120)
                                    ) ?>
                                </td>

                                <td>
                                    <?= date(
                                        'd M Y H:i',
                                        strtotime($diskusi['tanggal'])
                                    ) ?>
                                </td>

                                <td>
                                    <div class="action-buttons">

                                        <a
                                            href="hapus.php?id=<?= $diskusi['diskusiId'] ?>"
                                            class="action-icon delete-icon"
                                            onclick="return confirm('Hapus komentar ini?')">

                                            <img src="/finalProject/assets/icon/trash.png" alt="Hapus">

                                        </a>

                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>

                    <?php else: ?>
                        <tr>
                            <td colspan="5">Belum ada komentar diskusi.</td>
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

                const user =
                    row.children[1].textContent.toLowerCase();

                const komentar =
                    row.children[2].textContent.toLowerCase();

                if (
                    user.includes(keyword) ||
                    komentar.includes(keyword)
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