<?php
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    try {
        $stmt->execute([$username, $email, $password]);
        header("Location: login.php");
        exit();
    } catch(PDOException $e) {
        $error = "Registration failed. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Netflix Clone - Register</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-netflix-black text-white min-h-screen">
    <div class="max-w-md mx-auto mt-20 p-8 bg-black/75 rounded">
        <h2 class="text-3xl font-bold mb-8">Register</h2>
        <?php if (isset($error)) echo "<p class='text-netflix-red mb-4'>$error</p>"; ?>
        <form method="POST" class="space-y-4">
            <div>
                <input type="text" name="username" placeholder="Username" required 
                    class="input-field">
            </div>
            <div>
                <input type="email" name="email" placeholder="Email" required 
                    class="input-field">
            </div>
            <div>
                <input type="password" name="password" placeholder="Password" required 
                    class="input-field">
            </div>
            <button type="submit" class="btn-primary w-full">Register</button>
        </form>
        <p class="mt-6 text-center">
            Already have an account? 
            <a href="login.php" class="text-netflix-red hover:underline">Login here</a>
        </p>
    </div>
</body>
</html> 