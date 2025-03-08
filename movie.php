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

// Fetch movie details
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

// Check if movie is in user's watchlist
$stmt = $pdo->prepare("
    SELECT 1 FROM watchlist 
    WHERE user_id = ? AND movie_id = ?
");
$stmt->execute([$_SESSION['user_id'], $movie['id']]);
$inWatchlist = $stmt->fetch() !== false;

// Fetch similar movies
$stmt = $pdo->prepare("
    SELECT m.*, c.name as category_name 
    FROM movies m 
    JOIN categories c ON m.category_id = c.id 
    WHERE m.category_id = ? AND m.id != ?
    LIMIT 4
");
$stmt->execute([$movie['category_id'], $movie['id']]);
$similarMovies = $stmt->fetchAll();
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
                <a class="nav-link" href="categories.php">Categories</a>
                <a class="nav-link" href="watchlist.php">My List</a>
                <a class="nav-link" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-4">
                <img src="<?php echo htmlspecialchars($movie['thumbnail']); ?>" 
                     alt="<?php echo htmlspecialchars($movie['title']); ?>"
                     class="img-fluid rounded">
            </div>
            <div class="col-md-8">
                <h1 class="mb-3"><?php echo htmlspecialchars($movie['title']); ?></h1>
                <div class="mb-3">
                    <span class="badge bg-secondary me-2"><?php echo htmlspecialchars($movie['release_year']); ?></span>
                    <span class="badge bg-primary"><?php echo htmlspecialchars($movie['category_name']); ?></span>
                </div>
                <p class="text-secondary mb-4"><?php echo htmlspecialchars($movie['description']); ?></p>
                <div class="d-flex gap-2 mb-4">
                    <a href="watch.php?id=<?php echo $movie['id']; ?>" 
                       class="btn btn-danger">Watch Now</a>
                    <?php if ($inWatchlist): ?>
                        <a href="remove_from_watchlist.php?id=<?php echo $movie['id']; ?>" 
                           class="btn btn-outline-danger">Remove from My List</a>
                    <?php else: ?>
                        <a href="add_to_watchlist.php?id=<?php echo $movie['id']; ?>" 
                           class="btn btn-outline-light">Add to My List</a>
                    <?php endif; ?>
                </div>

                <?php if (!empty($similarMovies)): ?>
                    <h2 class="h4 mb-3">Similar Movies</h2>
                    <div class="row g-4">
                        <?php foreach ($similarMovies as $similar): ?>
                            <div class="col-6 col-md-3">
                                <div class="card bg-dark h-100 hover-scale">
                                    <img src="<?php echo htmlspecialchars($similar['thumbnail']); ?>" 
                                         alt="<?php echo htmlspecialchars($similar['title']); ?>"
                                         class="card-img-top" style="height: 200px; object-fit: cover;">
                                    <div class="card-body">
                                        <h3 class="card-title fs-6 fw-semibold">
                                            <?php echo htmlspecialchars($similar['title']); ?>
                                        </h3>
                                        <a href="movie.php?id=<?php echo $similar['id']; ?>" 
                                           class="btn btn-outline-light btn-sm w-100">View Details</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html> 