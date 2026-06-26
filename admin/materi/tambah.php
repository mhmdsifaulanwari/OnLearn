<?php
require_once '../../config/database.php';
require_once '../../function/helper.php';

/** @var mysqli $conn */

requireAdminLogin();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = sanitize($_POST['judul']);
    $isiMateri = trim($_POST['isiMateri']);
    $jenjang = sanitize($_POST['jenjang']);
    $kelas = sanitize($_POST['kelas']);

    $fileName = "";

    /*
    |--------------------------------------------------------------------------
    | UPLOAD FILE
    |--------------------------------------------------------------------------
    */
    if (!empty($_FILES['fileMateri']['name'])) {
        $allowed = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'jpg', 'jpeg', 'png'];

        $originalName = $_FILES['fileMateri']['name'];
        $tmpName = $_FILES['fileMateri']['tmp_name'];
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            $message = "Format file tidak didukung.";
        } else {
            $fileName = time() . "_" . basename($originalName);
            move_uploaded_file(
                $tmpName,
                "../../assets/uploads/materi/" . $fileName
            );
        }
    }

    if (
        empty($judul) ||
        empty(trim($isiMateri)) ||
        empty($jenjang) ||
        empty($kelas)
    ) {
        $message = "Semua field wajib diisi!";
    }

    if (empty($message)) {
        $query = "
            INSERT INTO materi (
                judul,
                isiMateri,
                jenjang,
                kelas,
                fileMateri
            )
            VALUES (
                '$judul',
                '$isiMateri',
                '$jenjang',
                '$kelas',
                '$fileName'
            )
        ";

        if (mysqli_query($conn, $query)) {
            redirect('index.php');
        } else {
            $message = "Gagal menambahkan materi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Materi - OnLearn</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>

<body>

    <?php include '../../templates/admin_sidebar.php'; ?>

    <main class="admin-content">

        <div class="content-header">
            <h1>Tambah Materi</h1>
            <p>Tambahkan materi pembelajaran lengkap beserta file pendukung.</p>
        </div>

        <div class="form-card">

            <?php if ($message): ?>
                <div class="error-message"><?= $message ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="admin-form">

                <div class="form-group-admin">
                    <label>Judul Materi</label>
                    <input
                        type="text"
                        name="judul"
                        required>
                </div>

                <div class="form-group-admin">
                    <label>Jenjang</label>
                    <select name="jenjang" required>
                        <option value="">Pilih Jenjang</option>
                        <option value="SD">SD</option>
                        <option value="SMP">SMP</option>
                        <option value="SMA/SMK">SMA/SMK</option>
                    </select>
                </div>

                <div class="form-group-admin">
                    <label>Kelas</label>
                    <select name="kelas" required>
                        <option value="">Pilih Kelas</option>

                        <option value="1">SD Kelas 1</option>
                        <option value="2">SD Kelas 2</option>
                        <option value="3">SD Kelas 3</option>
                        <option value="4">SD Kelas 4</option>
                        <option value="5">SD Kelas 5</option>
                        <option value="6">SD Kelas 6</option>

                        <option value="7">SMP Kelas 7</option>
                        <option value="8">SMP Kelas 8</option>
                        <option value="9">SMP Kelas 9</option>

                        <option value="10">SMA/SMK Kelas 10</option>
                        <option value="11">SMA/SMK Kelas 11</option>
                        <option value="12">SMA/SMK Kelas 12</option>
                    </select>
                </div>

                <div class="form-group-admin">
                    <label>Isi Materi</label>
                    <textarea
                        name="isiMateri"
                        id="isiMateri"
                        rows="12"
                        required></textarea>
                </div>

                <div class="form-group-admin">
                    <label>Upload File Pembelajaran (opsional)</label>
                    <input
                        type="file"
                        name="fileMateri"
                        accept=".pdf,.doc,.docx,.ppt,.pptx,.jpg,.jpeg,.png">
                </div>

                <div class="form-actions">
                    <a href="index.php" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">Simpan Materi</button>
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