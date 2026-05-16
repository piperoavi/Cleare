<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /cleare/');
    exit;
}

$choice = $_POST['choice'] ?? '';

if ($choice !== 'accepted' && $choice !== 'declined') {
    header('Location: /cleare/');
    exit;
}

setcookie(
    'cleare_cookie_consent',
    $choice,
    time() + (365 * 24 * 60 * 60),
    '/cleare/'
);

$redirect = $_SERVER['HTTP_REFERER'] ?? '/cleare/';
header('Location: ' . $redirect);
exit;