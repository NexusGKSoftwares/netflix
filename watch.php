<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$stmt = $pdo->prepare("
    SELECT m.*, c.name as category_name 
    FROM movies m 
    JOIN categories c ON m.category_id = c.id 
    WHERE m.id = ?
");
$stmt->execute([$_GET['id']]);
$movie = $stmt->fetch();

if (!$movie) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Netflix Clone - <?php echo htmlspecialchars($movie['title']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-dark text-white">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand text-danger fw-bold fs-4" href="index.php">Netflix Clone</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php">Home</a>
                <a class="nav-link" href="watchlist.php">My List</a>
                <a class="nav-link" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="ratio ratio-16x9 mb-4">
                    <video controls class="rounded">
                        <source src="<?php echo htmlspecialchars($movie['video_url']); ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
                <h1 class="h2 mb-3"><?php echo htmlspecialchars($movie['title']); ?></h1>
                <div class="mb-3">
                    <span class="badge bg-secondary me-2"><?php echo htmlspecialchars($movie['release_year']); ?></span>
                    <span class="badge bg-primary"><?php echo htmlspecialchars($movie['category_name']); ?></span>
                </div>
                <p class="text-secondary"><?php echo htmlspecialchars($movie['description']); ?></p>
                <a href="add_to_watchlist.php?id=<?php echo $movie['id']; ?>" 
                   class="btn btn-outline-light">Add to My List</a>
            </div>
        </div>
    </div>
</body>
</html> 