<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: login.php");
    exit();
}

$stmt = $pdo->prepare("
    INSERT INTO watchlist (user_id, movie_id) 
    VALUES (?, ?) 
    ON DUPLICATE KEY UPDATE created_at = CURRENT_TIMESTAMP
");
$stmt->execute([$_SESSION['user_id'], $_GET['id']]);

header("Location: " . $_SERVER['HTTP_REFERER']);
exit(); 