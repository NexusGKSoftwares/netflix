<?php
require_once '../middleware/admin_auth.php';
checkAdminAccess();
require_once '../config/database.php';

$pageTitle = 'Admin Dashboard - Netflix Clone';
require_once '../includes/header.php';

// Fetch statistics
$movieCount = $pdo->query("SELECT COUNT(*) FROM movies")->fetchColumn();
$categoryCount = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$userCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

// Add more statistics
$recentMovies = $pdo->query("
    SELECT m.*, c.name as category_name 
    FROM movies m 
    LEFT JOIN categories c ON m.category_id = c.id 
    ORDER BY m.created_at DESC 
    LIMIT 5
")->fetchAll();

$categoryStats = $pdo->query("
    SELECT c.name, COUNT(m.id) as movie_count 
    FROM categories c 
    LEFT JOIN movies m ON c.id = m.category_id 
    GROUP BY c.id, c.name 
    ORDER BY movie_count DESC
")->fetchAll();
?>

<?php include 'includes/admin_nav.php'; ?>

<div class="container mt-5 pt-4">
    <h1 class="mb-4">Admin Dashboard</h1>
    
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card bg-dark text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Movies</h5>
                    <p class="card-text netflix-red display-4"><?php echo $movieCount; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card bg-dark text-white">
                <div class="card-body">
                    <h5 class="card-title">Categories</h5>
                    <p class="card-text netflix-red display-4"><?php echo $categoryCount; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card bg-dark text-white">
                <div class="card-body">
                    <h5 class="card-title">Users</h5>
                    <p class="card-text netflix-red display-4"><?php echo $userCount; ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6 mb-4">
            <div class="card bg-dark text-white h-100">
                <div class="card-body">
                    <h5 class="card-title">Manage Movies</h5>
                    <p class="card-text text-muted">Add, edit, or delete movies</p>
                    <a href="movies.php" class="btn btn-netflix">Go to Movies</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card bg-dark text-white h-100">
                <div class="card-body">
                    <h5 class="card-title">Manage Categories</h5>
                    <p class="card-text text-muted">Manage movie categories</p>
                    <a href="categories.php" class="btn btn-netflix">Go to Categories</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6 mb-4">
            <div class="card bg-dark">
                <div class="card-body">
                    <h5 class="card-title">Recently Added Movies</h5>
                    <div class="list-group bg-dark">
                        <?php foreach ($recentMovies as $movie): ?>
                            <div class="list-group-item bg-dark text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><?php echo htmlspecialchars($movie['title']); ?></span>
                                    <span class="badge bg-netflix-red"><?php echo htmlspecialchars($movie['category_name']); ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card bg-dark">
                <div class="card-body">
                    <h5 class="card-title">Movies by Category</h5>
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('categoryChart');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode(array_column($categoryStats, 'name')); ?>,
        datasets: [{
            label: 'Number of Movies',
            data: <?php echo json_encode(array_column($categoryStats, 'movie_count')); ?>,
            backgroundColor: '#e50914'
        }]
    },
    options: {
        plugins: {
            legend: {
                labels: {
                    color: 'white'
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    color: 'white'
                }
            },
            x: {
                ticks: {
                    color: 'white'
                }
            }
        }
    }
});
</script>
</body>
</html> 