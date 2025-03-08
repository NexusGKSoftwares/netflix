<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid email or password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Netflix Clone - Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-netflix-black text-white min-h-screen">
    <div class="max-w-md mx-auto mt-20 p-8 bg-black/75 rounded">
        <h2 class="text-3xl font-bold mb-8">Login</h2>
        <?php if (isset($error)) echo "<p class='text-netflix-red mb-4'>$error</p>"; ?>
        <form method="POST" class="space-y-4">
            <div>
                <input type="email" name="email" placeholder="Email" required 
                    class="input-field">
            </div>
            <div>
                <input type="password" name="password" placeholder="Password" required 
                    class="input-field">
            </div>
            <button type="submit" class="btn-primary w-full">Login</button>
        </form>
        <p class="mt-6 text-center">
            Don't have an account? 
            <a href="register.php" class="text-netflix-red hover:underline">Register here</a>
        </p>
    </div>
</body>
</html> 