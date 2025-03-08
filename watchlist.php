<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$stmt = $pdo->prepare("
    SELECT m.*, c.name as category_name 
    FROM movies m 
    JOIN categories c ON m.category_id = c.id 
    JOIN watchlist w ON m.id = w.movie_id 
    WHERE w.user_id = ?
    ORDER BY w.created_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$movies = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Netflix Clone - My List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-dark text-white">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand text-danger fw-bold fs-4" href="index.php">Netflix Clone</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php">Home</a>
                <a class="nav-link active" href="watchlist.php">My List</a>
                <a class="nav-link" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="mb-4">My List</h1>
        
        <?php if (empty($movies)): ?>
            <div class="text-center mt-5">
                <h2 class="h4 text-secondary">Your watchlist is empty</h2>
                <a href="index.php" class="btn btn-danger mt-3">Browse Movies</a>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($movies as $movie): ?>
                    <div class="col-12 col-md-3">
                        <div class="card bg-dark h-100 hover-scale">
                            <img src="<?php echo htmlspecialchars($movie['thumbnail']); ?>" 
                                 alt="<?php echo htmlspecialchars($movie['title']); ?>"
                                 class="card-img-top" style="height: 400px; object-fit: cover;">
                            <div class="card-body">
                                <h3 class="card-title fs-5 fw-semibold">
                                    <?php echo htmlspecialchars($movie['title']); ?>
                                </h3>
                                <p class="text-secondary">
                                    <?php echo htmlspecialchars($movie['release_year']); ?>
                                </p>
                                <div class="d-grid gap-2">
                                    <a href="watch.php?id=<?php echo $movie['id']; ?>" 
                                       class="btn btn-primary">Watch Now</a>
                                    <a href="remove_from_watchlist.php?id=<?php echo $movie['id']; ?>" 
                                       class="btn btn-outline-danger">Remove</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html> 