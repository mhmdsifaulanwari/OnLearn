<?php
require_once '../../config/database.php';
require_once '../../function/helper.php';

/** @var mysqli $conn */

requireAdminLogin();

if (!isset($_GET['id'])) {
    redirect('index.php');
}

$diskusiId = (int) $_GET['id'];

/*
|--------------------------------------------------------------------------
| CEK KOMENTAR ADA?
|--------------------------------------------------------------------------
*/
$check = mysqli_query($conn, "
    SELECT * FROM diskusi
    WHERE diskusiId = $diskusiId
    LIMIT 1
");

if (mysqli_num_rows($check) == 0) {
    redirect('index.php');
}

/*
|--------------------------------------------------------------------------
| HAPUS KOMENTAR
|--------------------------------------------------------------------------
*/
mysqli_query($conn, "
    DELETE FROM diskusi
    WHERE diskusiId = $diskusiId
");

redirect('index.php');
?>