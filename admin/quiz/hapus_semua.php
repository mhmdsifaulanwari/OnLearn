<?php
require_once '../../config/database.php';
require_once '../../function/helper.php';

/** @var mysqli $conn */

requireAdminLogin();

if (!isset($_GET['materiId'])) {
    redirect('index.php');
}

$materiId = (int) $_GET['materiId'];

/*
|--------------------------------------------------------------------------
| CEK ADA QUIZ UNTUK MATERI INI?
|--------------------------------------------------------------------------
*/
$check = mysqli_query($conn, "
    SELECT * FROM quiz
    WHERE materiId = $materiId
    LIMIT 1
");

if (mysqli_num_rows($check) == 0) {
    redirect('index.php');
}

/*
|--------------------------------------------------------------------------
| HAPUS SEMUA QUIZ BERDASARKAN MATERI
|--------------------------------------------------------------------------
*/
mysqli_query($conn, "
    DELETE FROM quiz
    WHERE materiId = $materiId
");

redirect('index.php');
