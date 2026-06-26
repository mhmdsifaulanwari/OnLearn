<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = "localhost";
$port = "3306";
$username = "root";
$password = "";
$database = "onlearn";

$conn = mysqli_connect($host, $username, $password, $database, $port);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
