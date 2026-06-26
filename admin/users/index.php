<?php
require_once '../../config/database.php';
require_once '../../function/helper.php';

/** @var mysqli $conn */

requireAdminLogin();

$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';

$query = "
    SELECT * FROM users
";

if (!empty($search)) {
    $query .= "
        WHERE username LIKE '%$search%'
        OR email LIKE '%$search%'
    ";
}

$query .= " ORDER BY userId DESC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola User - OnLearn</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>

<body>

    <?php include '../../templates/admin_sidebar.php'; ?>

    <main class="admin-content">

        <div class="content-header">
            <h1>Kelola User</h1>
            <p>Kelola seluruh akun pengguna OnLearn.</p>
        </div>

        <div class="admin-toolbar">
            <a href="tambah.php" class="btn-primary">+ Tambah User</a>

            <div class="search-form">

                <div class="search-box">

                    <img
                        src="/finalProject/assets/icon/basic-ui.png"
                        alt="search">

                    <input
                        type="text"
                        id="searchInput"
                        placeholder="Cari username atau email...">

                </div>

            </div>
        </div>

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php $no = 1; ?>
                        <?php while ($user = mysqli_fetch_assoc($result)): ?>
                            <?php
                            $status = "Aktif";

                            if (!empty($user['locked_until']) && strtotime($user['locked_until']) > time()) {
                                $status = "Terkunci";
                            }
                            ?>
                            <tr class="table-row">
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($user['username']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td>
                                    <span class="<?= $status == 'Aktif' ? 'status-active' : 'status-lock' ?>">
                                        <?= $status ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">

                                        <a
                                            href="edit.php?id=<?= $user['userId'] ?>"
                                            class="action-icon edit-icon">

                                            <img src="/finalProject/assets/icon/edit.png" alt="Edit">

                                        </a>

                                        <a
                                            href="hapus.php?id=<?= $user['userId'] ?>"
                                            class="action-icon delete-icon"
                                            onclick="return confirm('Hapus user ini?')">

                                            <img src="/finalProject/assets/icon/trash.png" alt="Hapus">

                                        </a>

                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">Tidak ada data user.</td>
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

                const username =
                    row.children[1].textContent.toLowerCase();

                const email =
                    row.children[2].textContent.toLowerCase();

                if (
                    username.includes(keyword) ||
                    email.includes(keyword)
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