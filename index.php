<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch movies grouped by category
$stmt = $pdo->query("
    SELECT m.*, c.name as category_name 
    FROM movies m 
    JOIN categories c ON m.category_id = c.id 
    ORDER BY c.name, m.title
");
$movies = $stmt->fetchAll();

// Group movies by category
$moviesByCategory = [];
foreach ($movies as $movie) {
    $moviesByCategory[$movie['category_name']][] = $movie;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Netflix Clone - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-dark text-white min-vh-100">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand text-danger fw-bold fs-4" href="index.php">
                <i class="fas fa-play-circle me-2"></i>Netflix Clone
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav ms-auto">
                    <a class="nav-link active" href="index.php">
                        <i class="fas fa-home me-2"></i>Home
                    </a>
                    <a class="nav-link" href="categories.php">
                        <i class="fas fa-list me-2"></i>Categories
                    </a>
                    <a class="nav-link" href="search.php">
                        <i class="fas fa-search me-2"></i>Search
                    </a>
                    <a class="nav-link" href="watchlist.php">
                        <i class="fas fa-bookmark me-2"></i>My List
                    </a>
                    <a class="nav-link" href="logout.php">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container pt-5 mt-4">
        <?php foreach ($moviesByCategory as $category => $categoryMovies): ?>
            <div class="mb-4">
                <div class="d-flex align-items-center mb-3">
                    <h2 class="fs-2 fw-bold mb-0"><?php echo htmlspecialchars($category); ?></h2>
                    <a href="categories.php?id=<?php echo $categoryMovies[0]['category_id']; ?>" 
                       class="ms-3 text-decoration-none text-white-50">
                        <i class="fas fa-chevron-right"></i> View All
                    </a>
                </div>
                <div class="row flex-nowrap overflow-auto g-4 pb-4">
                    <?php foreach ($categoryMovies as $movie): ?>
                        <div class="col-12 col-md-3">
                            <div class="card bg-dark h-100 hover-scale">
                                <div class="position-relative">
                                    <img src="<?php echo htmlspecialchars($movie['thumbnail']); ?>" 
                                         alt="<?php echo htmlspecialchars($movie['title']); ?>"
                                         class="card-img-top" style="height: 400px; object-fit: cover;">
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge bg-danger">
                                            <i class="fas fa-star me-1"></i><?php echo $movie['release_year']; ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h3 class="card-title fs-5 fw-semibold">
                                        <?php echo htmlspecialchars($movie['title']); ?>
                                    </h3>
                                    <div class="d-grid gap-2">
                                        <a href="watch.php?id=<?php echo $movie['id']; ?>" 
                                           class="btn btn-danger">
                                           <i class="fas fa-play me-2"></i>Watch Now
                                        </a>
                                        <a href="add_to_watchlist.php?id=<?php echo $movie['id']; ?>" 
                                           class="btn btn-outline-light">
                                           <i class="fas fa-plus me-2"></i>Add to List
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Add Bootstrap JavaScript and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html> 