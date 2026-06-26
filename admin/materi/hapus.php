<?php
require_once '../../config/database.php';
require_once '../../function/helper.php';

/** @var mysqli $conn */

requireAdminLogin();

/*
|--------------------------------------------------------------------------
| AMBIL ID MATERI
|--------------------------------------------------------------------------
*/
$materiId = isset($_GET['id'])
    ? (int) $_GET['id']
    : 0;

if ($materiId <= 0) {
    die("ID materi tidak valid.");
}

/*
|--------------------------------------------------------------------------
| CEK DATA MATERI
|--------------------------------------------------------------------------
*/
$query = mysqli_query(
    $conn,
    "SELECT * FROM materi WHERE materiId = '$materiId'"
);

if (mysqli_num_rows($query) == 0) {
    die("Materi tidak ditemukan.");
}

$materi = mysqli_fetch_assoc($query);

/*
|--------------------------------------------------------------------------
| HAPUS FILE JIKA ADA
|--------------------------------------------------------------------------
*/
if (!empty($materi['fileMateri'])) {

    $filePath =
        "../../assets/uploads/materi/" .
        $materi['fileMateri'];

    if (file_exists($filePath)) {
        unlink($filePath);
    }
}

/*
|--------------------------------------------------------------------------
| HAPUS DATA DARI DATABASE
|--------------------------------------------------------------------------
*/
$delete = mysqli_query(
    $conn,
    "DELETE FROM materi WHERE materiId = '$materiId'"
);

if ($delete) {

    header("Location: index.php");
    exit;
} else {

    echo "Gagal menghapus materi: " . mysqli_error($conn);
}
