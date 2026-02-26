<?php
require_once __DIR__ . '/../config.php';

$conn = new mysqli(hostname: "host", username: "user", password: '', database: "agrisenegal");

if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>