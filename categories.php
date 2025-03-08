<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$category_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch all categories
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

// Fetch movies for selected category
if ($category_id > 0) {
    $stmt = $pdo->prepare("
        SELECT m.*, c.name as category_name 
        FROM movies m 
        JOIN categories c ON m.category_id = c.id 
        WHERE m.category_id = ?
        ORDER BY m.title
    ");
    $stmt->execute([$category_id]);
    $movies = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Netflix Clone - Categories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-dark text-white">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand text-danger fw-bold fs-4" href="index.php">Netflix Clone</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php">Home</a>
                <a class="nav-link active" href="categories.php">Categories</a>
                <a class="nav-link" href="watchlist.php">My List</a>
                <a class="nav-link" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-3">
                <div class="card bg-dark">
                    <div class="card-header">
                        <h2 class="h5 mb-0">Categories</h2>
                    </div>
                    <div class="list-group list-group-flush">
                        <?php foreach ($categories as $category): ?>
                            <a href="?id=<?php echo $category['id']; ?>" 
                               class="list-group-item list-group-item-action bg-dark text-white
                                      <?php echo $category_id == $category['id'] ? 'active' : ''; ?>">
                                <?php echo htmlspecialchars($category['name']); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-9">
                <?php if ($category_id > 0): ?>
                    <?php if (empty($movies)): ?>
                        <div class="text-center mt-5">
                            <h2 class="h4 text-secondary">No movies in this category</h2>
                        </div>
                    <?php else: ?>
                        <h2 class="mb-4"><?php echo htmlspecialchars($movies[0]['category_name']); ?></h2>
                        <div class="row g-4">
                            <?php foreach ($movies as $movie): ?>
                                <div class="col-12 col-md-4">
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
                        <h2 class="h4 text-secondary">Select a category to view movies</h2>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html> 