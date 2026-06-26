<?php
require_once '../../config/database.php';
require_once '../../function/helper.php';

/** @var mysqli $conn */

requireAdminLogin();

if (!isset($_GET['id'])) {
    redirect('index.php');
}

$userId = (int) $_GET['id'];

/*
|--------------------------------------------------------------------------
| CEK USER ADA?
|--------------------------------------------------------------------------
*/
$check = mysqli_query($conn, "
    SELECT * FROM users
    WHERE userId = $userId
    LIMIT 1
");

if (mysqli_num_rows($check) == 0) {
    redirect('index.php');
}

/*
|--------------------------------------------------------------------------
| HAPUS USER
|--------------------------------------------------------------------------
*/
mysqli_query($conn, "
    DELETE FROM users
    WHERE userId = $userId
");

redirect('index.php');
?>