<?php
require_once '../../config/database.php';
require_once '../../function/helper.php';

/** @var mysqli $conn */

requireAdminLogin();

if (!isset($_GET['id']) || !isset($_GET['materiId'])) {
    redirect('index.php');
}

$quizId = (int) $_GET['id'];
$materiId = (int) $_GET['materiId'];

/*
|--------------------------------------------------------------------------
| CEK SOAL ADA?
|--------------------------------------------------------------------------
*/
$check = mysqli_query($conn, "
    SELECT * FROM quiz
    WHERE quizId = $quizId
    LIMIT 1
");

if (mysqli_num_rows($check) == 0) {
    redirect('index.php');
}

/*
|--------------------------------------------------------------------------
| HAPUS SOAL
|--------------------------------------------------------------------------
*/
mysqli_query($conn, "
    DELETE FROM quiz
    WHERE quizId = $quizId
");

redirect("edit.php?materiId=$materiId");
?>