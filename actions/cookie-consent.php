<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

$choice = $_POST['choice'] ?? '';

if ($choice !== 'accepted' && $choice !== 'declined') {
    header('Location: ../index.php');
    exit;
}

setcookie(
    'cleare_cookie_consent',
    $choice,
    time() + (365 * 24 * 60 * 60),
    '/'
);

$redirect = $_SERVER['HTTP_REFERER'] ?? '../index.php';
header('Location: ' . $redirect);
exit;