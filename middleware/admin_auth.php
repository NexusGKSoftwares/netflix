<?php
session_start();

function checkAdminAccess() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    require_once __DIR__ . '/../config/database.php';
    $stmt = $pdo->prepare("SELECT is_admin FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    if (!$user || !$user['is_admin']) {
        header("Location: index.php");
        exit();
    }
} 