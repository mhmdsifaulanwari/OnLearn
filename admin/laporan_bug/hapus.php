<?php
require_once '../../config/database.php';
require_once '../../function/helper.php';

/** @var mysqli $conn */

requireAdminLogin();

if (!isset($_GET['id'])) {
    redirect('index.php');
}

$bugId = (int) $_GET['id'];

/*
|--------------------------------------------------------------------------
| CEK LAPORAN ADA?
|--------------------------------------------------------------------------
*/
$check = mysqli_query($conn, "
    SELECT * FROM laporan_bug
    WHERE bugId = $bugId
    LIMIT 1
");

if (mysqli_num_rows($check) == 0) {
    redirect('index.php');
}

/*
|--------------------------------------------------------------------------
| HAPUS LAPORAN
|--------------------------------------------------------------------------
*/
mysqli_query($conn, "
    DELETE FROM laporan_bug
    WHERE bugId = $bugId
");

redirect('index.php');
?>