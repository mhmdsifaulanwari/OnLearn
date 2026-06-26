<?php
require_once '../config/database.php';
require_once '../function/helper.php';

/** @var mysqli $conn */

requireUserLogin();

$message = "";
$userId = $_SESSION['userId'];
$username = $_SESSION['username'];

/*
|--------------------------------------------------------------------------
| KIRIM LAPORAN
|--------------------------------------------------------------------------
*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deskripsi = sanitize($_POST['deskripsi']);

    if (!isValidBugReport($deskripsi)) {
        $message = "Laporan tidak valid. Maksimal 350 karakter.";
    } else {
        $query = "
            INSERT INTO laporan_bug (userId, deskripsi, status, tanggal)
            VALUES ($userId, '$deskripsi', 'Pending', NOW())
        ";

        if (mysqli_query($conn, $query)) {
            redirect('index.php');
        } else {
            $message = "Gagal mengirim laporan bug.";
        }
    }
}

/*
|--------------------------------------------------------------------------
| AMBIL HISTORI
|--------------------------------------------------------------------------
*/
$queryBug = "
    SELECT * FROM laporan_bug
    WHERE userId = $userId
    ORDER BY tanggal DESC
";

$resultBug = mysqli_query($conn, $queryBug);

function badgeClass($status)
{
    if ($status === 'Pending') return 'badge-pending';
    if ($status === 'Diproses') return 'badge-process';
    if ($status === 'Selesai') return 'badge-done';
    return '';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lapor Bug - OnLearn</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<section class="diskusi-page">

    <div class="diskusi-card">

        <a href="../index.php" class="close-btn">✕</a>

        <div class="diskusi-header">
            <h1>Lapor Bug</h1>
            <p>Laporkan bug atau kendala pada sistem OnLearn.</p>
        </div>

        <?php if ($message): ?>
            <div class="message">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <div class="diskusi-list">

            <?php if (mysqli_num_rows($resultBug) > 0): ?>
                <?php while ($bug = mysqli_fetch_assoc($resultBug)): ?>
                    <div class="chat-bubble">

                        <div class="chat-top">
                            <strong>Laporan Bug</strong>
                            <span><?= date('d M Y H:i', strtotime($bug['tanggal'])) ?></span>
                        </div>

                        <p><?= nl2br(htmlspecialchars($bug['deskripsi'])) ?></p>

                        <span class="status-badge <?= badgeClass($bug['status']) ?>">
                            <?= htmlspecialchars($bug['status']) ?>
                        </span>

                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-state">
                    <h2>Belum ada laporan</h2>
                    <p>Belum ada bug yang kamu laporkan.</p>
                </div>
            <?php endif; ?>

        </div>

        <form method="POST" class="chat-form">
            <textarea
                name="deskripsi"
                placeholder="Tulis laporan bug atau kendala..."
                maxlength="350"
                required
            ></textarea>

            <button type="submit" class="send-btn">✈️</button>
        </form>

    </div>

</section>

</body>
</html>