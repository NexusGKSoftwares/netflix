<?php
session_start();
require_once 'config/database.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: index.php");
        exit();
    } else {
        $error = 'Invalid email or password';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Netflix Clone - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-dark text-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card bg-dark mt-5 p-4">
                    <div class="text-center mb-4">
                        <h1 class="text-danger fw-bold">Netflix Clone</h1>
                        <h2 class="h4">Sign In</h2>
                    </div>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <input type="email" name="email" class="form-control bg-dark text-white" 
                                   placeholder="Email address" required>
                        </div>
                        <div class="mb-3">
                            <input type="password" name="password" class="form-control bg-dark text-white" 
                                   placeholder="Password" required>
                        </div>
                        <button type="submit" class="btn btn-danger w-100 mb-3">Sign In</button>
                        <p class="text-center">
                            New to Netflix Clone? 
                            <a href="register.php" class="text-white">Sign up now</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 