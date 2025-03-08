<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$movies = [];

if ($search !== '') {
    $stmt = $pdo->prepare("
        SELECT m.*, c.name as category_name 
        FROM movies m 
        JOIN categories c ON m.category_id = c.id 
        WHERE m.title LIKE ? OR m.description LIKE ?
        ORDER BY m.title
    ");
    $searchTerm = "%{$search}%";
    $stmt->execute([$searchTerm, $searchTerm]);
    $movies = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Netflix Clone - Search</title>
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
        <div class="row justify-content-center mb-4">
            <div class="col-md-6">
                <form action="search.php" method="GET" class="d-flex">
                    <div class="input-group">
                        <input type="search" name="q" 
                               class="form-control bg-dark text-white" 
                               placeholder="Search movies..." 
                               value="<?php echo htmlspecialchars($search); ?>">
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-search me-2"></i>Search
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <?php if ($search !== ''): ?>
            <?php if (empty($movies)): ?>
                <div class="text-center mt-5">
                    <i class="fas fa-search fa-3x text-secondary mb-3"></i>
                    <h2 class="h4 text-secondary">No results found for "<?php echo htmlspecialchars($search); ?>"</h2>
                </div>
            <?php else: ?>
                <h2 class="mb-4">
                    <i class="fas fa-search me-2"></i>
                    Search Results for "<?php echo htmlspecialchars($search); ?>"
                </h2>
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
                                        <?php echo htmlspecialchars($movie['release_year']); ?> • 
                                        <?php echo htmlspecialchars($movie['category_name']); ?>
                                    </p>
                                    <div class="d-grid gap-2">
                                        <a href="movie.php?id=<?php echo $movie['id']; ?>" 
                                           class="btn btn-primary">View Details</a>
                                        <a href="watch.php?id=<?php echo $movie['id']; ?>" 
                                           class="btn btn-outline-light">Watch Now</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="text-center mt-5">
                <i class="fas fa-film fa-3x text-secondary mb-3"></i>
                <h2 class="h4 text-secondary">Start searching for movies</h2>
            </div>
        <?php endif; ?>
    </div>
</body>
</html> 