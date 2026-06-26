<?php
require_once '../../config/database.php';
require_once '../../function/helper.php';

/** @var mysqli $conn */

requireAdminLogin();

$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';

$query = "
    SELECT
        laporan_bug.*,
        users.username
    FROM laporan_bug
    JOIN users ON laporan_bug.userId = users.userId
";

if (!empty($search)) {
    $query .= "
        WHERE users.username LIKE '%$search%'
        OR laporan_bug.deskripsi LIKE '%$search%'
        OR laporan_bug.status LIKE '%$search%'
    ";
}

$query .= " ORDER BY laporan_bug.tanggal DESC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Laporan Bug - OnLearn</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>

<body>

    <?php include '../../templates/admin_sidebar.php'; ?>

    <main class="admin-content">

        <div class="content-header">
            <h1>Kelola Laporan Bug</h1>
            <p>Kelola laporan bug dari pengguna.</p>
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
                        placeholder="Cari user / bug / status...">

                </div>

            </div>
        </div>

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>User</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php $no = 1; ?>

                        <?php while ($bug = mysqli_fetch_assoc($result)): ?>
                            <tr class="table-row">
                                <td><?= $no++ ?></td>

                                <td>
                                    <?= htmlspecialchars($bug['username']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        substr($bug['deskripsi'], 0, 120)
                                    ) ?>
                                </td>

                                <td>
                                    <?php
                                    $statusClass = 'status-active';

                                    if ($bug['status'] == 'Pending') {
                                        $statusClass = 'status-lock';
                                    } elseif ($bug['status'] == 'Diproses') {
                                        $statusClass = 'action-edit';
                                    }
                                    ?>

                                    <span class="<?= $statusClass ?>">
                                        <?= htmlspecialchars($bug['status']) ?>
                                    </span>
                                </td>

                                <td>
                                    <?= date(
                                        'd M Y H:i',
                                        strtotime($bug['tanggal'])
                                    ) ?>
                                </td>

                                <td>
                                    <div class="action-buttons">

                                        <a
                                            href="edit.php?id=<?= $bug['bugId'] ?>"
                                            class="action-icon edit-icon">

                                            <img src="/finalProject/assets/icon/edit.png" alt="Edit">

                                        </a>

                                        <a
                                            href="hapus.php?id=<?= $bug['bugId'] ?>"
                                            class="action-icon delete-icon"
                                            onclick="return confirm('Hapus laporan bug ini?')">

                                            <img src="/finalProject/assets/icon/trash.png" alt="Hapus">

                                        </a>

                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>

                    <?php else: ?>
                        <tr>
                            <td colspan="6">Belum ada laporan bug.</td>
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

                const deskripsi =
                    row.children[2].textContent.toLowerCase();

                const status =
                    row.children[3].textContent.toLowerCase();

                if (
                    user.includes(keyword) ||
                    deskripsi.includes(keyword) ||
                    status.includes(keyword)
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