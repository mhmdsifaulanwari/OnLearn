<?php
require_once '../../config/database.php';
require_once '../../function/helper.php';

/** @var mysqli $conn */

requireAdminLogin();

if (!isset($_GET['id'])) {
    redirect('index.php');
}

$materiId = (int) $_GET['id'];

$getMateri = mysqli_query($conn, "
    SELECT * FROM materi
    WHERE materiId = $materiId
    LIMIT 1
");

if (mysqli_num_rows($getMateri) == 0) {
    redirect('index.php');
}

$materi = mysqli_fetch_assoc($getMateri);

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $judul = sanitize($_POST['judul']);
    $isiMateri = trim($_POST['isiMateri']);
    $jenjang = sanitize($_POST['jenjang']);
    $kelas = sanitize($_POST['kelas']);

    $fileName = $materi['fileMateri'];

    /*
    |--------------------------------------------------------------------------
    | VALIDASI
    |--------------------------------------------------------------------------
    */
    if (
        empty($judul) ||
        empty(strip_tags($isiMateri)) ||
        empty($jenjang) ||
        empty($kelas)
    ) {
        $message = "Semua field wajib diisi!";
    }

    /*
    |--------------------------------------------------------------------------
    | UPLOAD FILE BARU
    |--------------------------------------------------------------------------
    */
    if (
        empty($message) &&
        !empty($_FILES['fileMateri']['name'])
    ) {

        $allowed = [
            'pdf',
            'doc',
            'docx',
            'ppt',
            'pptx',
            'jpg',
            'jpeg',
            'png'
        ];

        $originalName = $_FILES['fileMateri']['name'];
        $tmpName = $_FILES['fileMateri']['tmp_name'];

        $ext = strtolower(
            pathinfo($originalName, PATHINFO_EXTENSION)
        );

        if (!in_array($ext, $allowed)) {

            $message = "Format file tidak didukung.";
        } else {

            /*
            |--------------------------------------------------------------------------
            | HAPUS FILE LAMA
            |--------------------------------------------------------------------------
            */
            if (!empty($materi['fileMateri'])) {

                $oldFile =
                    "../../assets/uploads/materi/" .
                    $materi['fileMateri'];

                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | UPLOAD FILE BARU
            |--------------------------------------------------------------------------
            */
            $fileName =
                time() . "_" . basename($originalName);

            move_uploaded_file(
                $tmpName,
                "../../assets/uploads/materi/" . $fileName
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE DATABASE
    |--------------------------------------------------------------------------
    */
    if (empty($message)) {

        $query = "
            UPDATE materi
            SET
                judul='$judul',
                isiMateri='$isiMateri',
                jenjang='$jenjang',
                kelas='$kelas',
                fileMateri='$fileName'
            WHERE materiId=$materiId
        ";

        if (mysqli_query($conn, $query)) {

            redirect('index.php');
        } else {

            $message =
                'Gagal update materi: ' .
                mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Materi - OnLearn</title>

    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>

<body>

    <?php include '../../templates/admin_sidebar.php'; ?>

    <main class="admin-content">

        <div class="content-header">
            <h1>Edit Materi</h1>
            <p>Perbarui materi pembelajaran.</p>
        </div>

        <div class="form-card">

            <?php if ($message): ?>
                <div class="error-message">
                    <?= $message ?>
                </div>
            <?php endif; ?>

            <form
                method="POST"
                enctype="multipart/form-data"
                class="admin-form">

                <div class="form-group-admin">
                    <label>Judul Materi</label>

                    <input
                        type="text"
                        name="judul"
                        value="<?= htmlspecialchars($materi['judul']) ?>"
                        required>
                </div>

                <div class="form-group-admin">
                    <label>Jenjang</label>

                    <select name="jenjang" required>

                        <option
                            value="SD"
                            <?= $materi['jenjang'] == 'SD' ? 'selected' : '' ?>>
                            SD
                        </option>

                        <option
                            value="SMP"
                            <?= $materi['jenjang'] == 'SMP' ? 'selected' : '' ?>>
                            SMP
                        </option>

                        <option
                            value="SMA/SMK"
                            <?= $materi['jenjang'] == 'SMA/SMK' ? 'selected' : '' ?>>
                            SMA / SMK
                        </option>

                    </select>
                </div>

                <div class="form-group-admin">
                    <label>Kelas</label>

                    <select name="kelas" required>

                        <?php for ($i = 1; $i <= 12; $i++): ?>

                            <option
                                value="<?= $i ?>"
                                <?= $materi['kelas'] == $i ? 'selected' : '' ?>>
                                Kelas <?= $i ?>
                            </option>

                        <?php endfor; ?>

                    </select>
                </div>

                <div class="form-group-admin">
                    <label>Isi Materi</label>

                    <textarea
                        name="isiMateri"
                        id="isiMateri"
                        rows="12"
                        required><?= htmlspecialchars($materi['isiMateri']) ?></textarea>
                </div>

                <div class="form-group-admin">

                    <label>File Saat Ini</label>

                    <?php if (!empty($materi['fileMateri'])): ?>

                        <a
                            href="../../assets/uploads/materi/<?= urlencode($materi['fileMateri']) ?>"
                            target="_blank"
                            class="action-edit">
                            Lihat File Saat Ini
                        </a>

                    <?php else: ?>

                        <p>Tidak ada file.</p>

                    <?php endif; ?>

                </div>

                <div class="form-group-admin">

                    <label>Ganti File (opsional)</label>

                    <input
                        type="file"
                        name="fileMateri"
                        accept=".pdf,.doc,.docx,.ppt,.pptx,.jpg,.jpeg,.png">
                </div>

                <div class="form-actions">

                    <a href="index.php" class="btn-secondary">
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="btn-primary">
                        Update Materi
                    </button>

                </div>

            </form>

        </div>

    </main>

    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>

    <script>
        CKEDITOR.replace('isiMateri');

        document.querySelector("form").addEventListener("submit", function() {

            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }

        });
    </script>

</body>

</html>