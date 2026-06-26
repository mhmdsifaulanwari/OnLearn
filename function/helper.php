<?php

function redirect($url)
{
    header("Location: $url");
    exit;
}

function sanitize($data)
{
    return htmlspecialchars(trim($data));
}

function showAlert($message)
{
    return "<script>alert('$message');</script>";
}

/*------ VALIDATION-------*/

function isValidEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function isValidPassword($password)
{
    return preg_match('/^(?=.*[A-Za-z])(?=.*\d).{8,}$/', $password);
}

function isValidDiskusi($komentar)
{
    if (strlen(trim($komentar)) == 0 || strlen($komentar) > 350) {
        return false;
    }

    $blockedWords = [
        'bodoh',
        'tolol',
        'anjing',
        'goblok',
        'sara',
        'ambigu',
    ];

    foreach ($blockedWords as $word) {
        if (stripos($komentar, $word) !== false) {
            return false;
        }
    }

    return true;
}



function isValidBugReport($deskripsi)
{
    if (strlen($deskripsi) == 0 || strlen($deskripsi) > 350) {
        return false;
    }

    return true;
}

/*------AUTH------*/


function isUserLoggedIn()
{
    return isset($_SESSION['userId']);
}

function isAdminLoggedIn()
{
    return isset($_SESSION['adminId']);
}

function requireUserLogin()
{
    if (!isUserLoggedIn()) {
        redirect('/finalProject/auth/login.php');
    }
}

function requireAdminLogin()
{
    if (!isAdminLoggedIn()) {
        redirect('/finalProject/auth/login.php');
    }
}

function isAccountLocked($lockedUntil)
{
    if (!$lockedUntil) {
        return false;
    }

    return strtotime($lockedUntil) > time();
}
?>