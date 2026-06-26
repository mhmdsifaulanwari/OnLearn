<?php
require_once '../../config/database.php';
require_once '../../function/helper.php';

/** @var mysqli $conn */

requireAdminLogin();

if (!isset($_GET['id'])) {
    redirect('index.php');
}

$bugId = (int) $_GET['id'];

$getBug = mysqli_query($conn, "
    SELECT
        laporan_bug.*,
        users.username
    FROM laporan_bug
    JOIN users ON laporan_bug.userId = users.userId
    WHERE laporan_bug.bugId = $bugId
    LIMIT 1
");

if (mysqli_num_rows($getBug) == 0) {
    redirect('index.php');
}

$bug = mysqli_fetch_assoc($getBug);
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status = sanitize($_POST['status']);

    $allowedStatus = ['Pending', 'Diproses', 'Selesai'];

    if (!in_array($status, $allowedStatus)) {
        $message = "Status tidak valid!";
    } else {
        mysqli_query($conn, "
            UPDATE laporan_bug
            SET status = '$status'
            WHERE bugId = $bugId
        ");

        redirect('index.php');
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Laporan Bug - OnLearn</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>

<?php include '../../templates/admin_sidebar.php'; ?>

<main class="admin-content">

    <div class="content-header">
        <h1>Kelola Laporan Bug</h1>
        <p>Perbarui status laporan bug pengguna.</p>
    </div>

    <div class="form-card">

        <?php if ($message): ?>
            <div class="error-message"><?= $message ?></div>
        <?php endif; ?>

        <form method="POST" class="admin-form">

            <div class="form-group-admin">
                <label>Pelapor</label>
                <input
                    type="text"
                    value="<?= htmlspecialchars($bug['username']) ?>"
                    readonly
                >
            </div>

            <div class="form-group-admin">
                <label>Deskripsi Bug</label>
                <textarea rows="8" readonly><?= htmlspecialchars($bug['deskripsi']) ?></textarea>
            </div>

            <div class="form-group-admin">
                <label>Status</label>
                <select name="status" required>
                    <option value="Pending" <?= $bug['status'] == 'Pending' ? 'selected' : '' ?>>
                        Pending
                    </option>

                    <option value="Diproses" <?= $bug['status'] == 'Diproses' ? 'selected' : '' ?>>
                        Diproses
                    </option>

                    <option value="Selesai" <?= $bug['status'] == 'Selesai' ? 'selected' : '' ?>>
                        Selesai
                    </option>
                </select>
            </div>

            <div class="form-group-admin">
                <label>Tanggal Laporan</label>
                <input
                    type="text"
                    value="<?= date('d M Y H:i', strtotime($bug['tanggal'])) ?>"
                    readonly
                >
            </div>

            <div class="form-actions">
                <a href="index.php" class="btn-secondary">Kembali</a>
                <button type="submit" class="btn-primary">
                    Update Status
                </button>
            </div>

        </form>

    </div>

</main>

</body>
</html>