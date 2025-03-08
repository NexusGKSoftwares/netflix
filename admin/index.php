<?php
session_start();
require_once '../config/database.php';

// Check if user is admin
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Fetch statistics
$stats = [
    'movies' => $pdo->query("SELECT COUNT(*) FROM movies")->fetchColumn(),
    'categories' => $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn(),
    'users' => $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(),
    'watchlist_items' => $pdo->query("SELECT COUNT(*) FROM watchlist")->fetchColumn()
];

// Fetch recent activities
$recent_movies = $pdo->query("
    SELECT m.*, c.name as category_name 
    FROM movies m 
    JOIN categories c ON m.category_id = c.id 
    ORDER BY m.created_at DESC 
    LIMIT 5
")->fetchAll();

$recent_users = $pdo->query("
    SELECT * FROM users 
    ORDER BY created_at DESC 
    LIMIT 5
")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Netflix Clone - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="bg-dark text-white">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand text-danger fw-bold fs-4" href="index.php">
                <i class="fas fa-lock me-2"></i>Admin Panel
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav">
                    <a class="nav-link active" href="index.php">
                        <i class="fas fa-dashboard me-2"></i>Dashboard
                    </a>
                    <a class="nav-link" href="movies.php">
                        <i class="fas fa-film me-2"></i>Movies
                    </a>
                    <a class="nav-link" href="categories.php">
                        <i class="fas fa-list me-2"></i>Categories
                    </a>
                    <a class="nav-link" href="users.php">
                        <i class="fas fa-users me-2"></i>Users
                    </a>
                </div>
                <div class="navbar-nav ms-auto">
                    <a class="nav-link" href="../index.php">
                        <i class="fas fa-tv me-2"></i>View Site
                    </a>
                    <a class="nav-link" href="../logout.php">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0">Total Movies</h6>
                                <h2 class="mt-2 mb-0"><?php echo $stats['movies']; ?></h2>
                            </div>
                            <i class="fas fa-film fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0">Categories</h6>
                                <h2 class="mt-2 mb-0"><?php echo $stats['categories']; ?></h2>
                            </div>
                            <i class="fas fa-list fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0">Users</h6>
                                <h2 class="mt-2 mb-0"><?php echo $stats['users']; ?></h2>
                            </div>
                            <i class="fas fa-users fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0">Watchlist Items</h6>
                                <h2 class="mt-2 mb-0"><?php echo $stats['watchlist_items']; ?></h2>
                            </div>
                            <i class="fas fa-bookmark fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card bg-dark">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Recent Movies</h5>
                        <a href="movies.php" class="btn btn-sm btn-outline-light">View All</a>
                    </div>
                    <div class="list-group list-group-flush">
                        <?php foreach ($recent_movies as $movie): ?>
                            <div class="list-group-item bg-dark text-white border-light">
                                <div class="d-flex align-items-center">
                                    <img src="<?php echo htmlspecialchars($movie['thumbnail']); ?>" 
                                         alt="<?php echo htmlspecialchars($movie['title']); ?>"
                                         class="rounded me-3" style="width: 48px; height: 48px; object-fit: cover;">
                                    <div>
                                        <h6 class="mb-0"><?php echo htmlspecialchars($movie['title']); ?></h6>
                                        <small class="text-secondary">
                                            <?php echo htmlspecialchars($movie['category_name']); ?> • 
                                            <?php echo htmlspecialchars($movie['release_year']); ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-dark">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Recent Users</h5>
                        <a href="users.php" class="btn btn-sm btn-outline-light">View All</a>
                    </div>
                    <div class="list-group list-group-flush">
                        <?php foreach ($recent_users as $user): ?>
                            <div class="list-group-item bg-dark text-white border-light">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-3" 
                                         style="width: 48px; height: 48px;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0"><?php echo htmlspecialchars($user['email']); ?></h6>
                                        <small class="text-secondary">
                                            Joined <?php echo date('M j, Y', strtotime($user['created_at'])); ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html> 