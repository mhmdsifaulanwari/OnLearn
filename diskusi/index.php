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
| TAMBAH KOMENTAR
|--------------------------------------------------------------------------
*/
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['komentar'])) {
    $komentar = sanitize($_POST['komentar']);

    if (!isValidDiskusi($komentar)) {
        $message = "Komentar tidak valid. Maksimal 350 karakter dan tidak boleh mengandung kata terlarang.";
    } else {
        $query = "
            INSERT INTO diskusi (userId, komentar, tanggal)
            VALUES ($userId, '$komentar', NOW())
        ";

        if (mysqli_query($conn, $query)) {
            redirect('index.php');
        } else {
            $message = "Gagal mengirim komentar.";
        }
    }
}

/*
|--------------------------------------------------------------------------
| HAPUS KOMENTAR
|--------------------------------------------------------------------------
*/
if (isset($_GET['hapus'])) {
    $diskusiId = (int) $_GET['hapus'];

    $cekQuery = "
        SELECT * FROM diskusi
        WHERE diskusiId = $diskusiId
        AND userId = $userId
        LIMIT 1
    ";

    $cekResult = mysqli_query($conn, $cekQuery);

    if (mysqli_num_rows($cekResult) > 0) {
        $data = mysqli_fetch_assoc($cekResult);

        $waktuKomentar = strtotime($data['tanggal']);
        $selisih = time() - $waktuKomentar;

        if ($selisih <= 7200) {
            mysqli_query($conn, "DELETE FROM diskusi WHERE diskusiId = $diskusiId");
            redirect('index.php');
        } else {
            $message = "Mohon maaf, komentar tidak dapat dihapus karena melebihi batas 2 jam.";
        }
    }
}

/*
|--------------------------------------------------------------------------
| AMBIL KOMENTAR
|--------------------------------------------------------------------------
*/
$queryDiskusi = "
    SELECT diskusi.*, users.username
    FROM diskusi
    JOIN users ON diskusi.userId = users.userId
    ORDER BY tanggal DESC
";

$resultDiskusi = mysqli_query($conn, $queryDiskusi);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diskusi - OnLearn</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<section class="diskusi-page">

    <div class="diskusi-card">

        <a href="../index.php" class="close-btn">✕</a>

        <div class="diskusi-header">
            <h1>Diskusi OnLearn</h1>
            <p>Diskusikan pertanyaan atau pengalaman belajarmu bersama pengguna lain.</p>
        </div>

        <?php if ($message): ?>
            <div class="message">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <div class="diskusi-list">

            <?php if (mysqli_num_rows($resultDiskusi) > 0): ?>
                <?php while ($diskusi = mysqli_fetch_assoc($resultDiskusi)): ?>
                    <div class="chat-bubble">

                        <div class="chat-top">
                            <strong><?= htmlspecialchars($diskusi['username']) ?></strong>
                            <span><?= date('d M Y H:i', strtotime($diskusi['tanggal'])) ?></span>
                        </div>

                        <p><?= nl2br(htmlspecialchars($diskusi['komentar'])) ?></p>

                        <?php if ($diskusi['userId'] == $userId): ?>
                            <?php
                                $selisih = time() - strtotime($diskusi['tanggal']);
                            ?>
                            <?php if ($selisih <= 7200): ?>
                                <a 
                                    href="?hapus=<?= $diskusi['diskusiId'] ?>"
                                    class="delete-link"
                                    onclick="return confirm('Hapus komentar ini?')"
                                >
                                    Hapus
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>

                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-state">
                    <h2>Belum ada diskusi</h2>
                    <p>Jadilah yang pertama memulai diskusi.</p>
                </div>
            <?php endif; ?>

        </div>

        <form method="POST" class="chat-form">
            <textarea 
                name="komentar"
                placeholder="Tulis komentar atau pertanyaan..."
                maxlength="350"
                required
            ></textarea>

            <button type="submit" class="send-btn">✈️</button>
        </form>

    </div>

</section>

</body>
</html>