<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function get_user() {
    return [
        'id'     => $_SESSION['user_id'] ?? null,
        'nom'    => $_SESSION['user_nom'] ?? null,
        'email'  => $_SESSION['user_email'] ?? null,
        'region' => $_SESSION['user_region'] ?? null,
    ];
}

function require_login() {
    if (!is_logged_in()) {
        header('Location: /rejoindre.php');
        exit;
    }
}
?>