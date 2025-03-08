<?php
require_once '../middleware/admin_auth.php';
checkAdminAccess();
require_once '../config/database.php';

$pageTitle = 'Manage Movies - Netflix Clone';
require_once '../includes/header.php';

// Handle movie deletion
if (isset($_POST['delete_movie'])) {
    $stmt = $pdo->prepare("DELETE FROM movies WHERE id = ?");
    $stmt->execute([$_POST['movie_id']]);
}

// Add search functionality
$search = $_GET['search'] ?? '';
$searchCondition = $search ? "WHERE m.title LIKE :search OR c.name LIKE :search" : "";

// Fetch all movies with their categories
$stmt = $pdo->prepare("
    SELECT m.*, c.name as category_name 
    FROM movies m 
    LEFT JOIN categories c ON m.category_id = c.id 
    $searchCondition
    ORDER BY m.title
");

if ($search) {
    $stmt->bindValue(':search', "%$search%");
}
$stmt->execute();
$movies = $stmt->fetchAll();

// Add bulk delete handling
if (isset($_POST['bulk_action']) && isset($_POST['movie_ids'])) {
    if ($_POST['bulk_action'] === 'delete') {
        $ids = implode(',', array_map('intval', $_POST['movie_ids']));
        $pdo->query("DELETE FROM movies WHERE id IN ($ids)");
    }
}
?>

<?php include 'includes/admin_nav.php'; ?>

<div class="container mt-5 pt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manage Movies</h1>
        <a href="add_movie.php" class="btn btn-netflix">Add New Movie</a>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <form class="d-flex">
                <input type="search" name="search" class="form-control bg-dark text-white me-2" 
                       placeholder="Search movies..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-netflix">Search</button>
            </form>
        </div>
    </div>

    <div class="card bg-dark">
        <div class="card-body">
            <div class="table-responsive">
                <form method="POST">
                    <div class="mb-3">
                        <select name="bulk_action" class="form-select bg-dark text-white w-auto d-inline-block">
                            <option value="">Bulk Actions</option>
                            <option value="delete">Delete Selected</option>
                        </select>
                        <button type="submit" class="btn btn-netflix ms-2" onclick="return confirm('Are you sure?')">Apply</button>
                    </div>
                    
                    <table class="table table-dark table-hover">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all"></th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Release Year</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($movies as $movie): ?>
                            <tr>
                                <td><input type="checkbox" name="movie_ids[]" value="<?php echo $movie['id']; ?>"></td>
                                <td><?php echo htmlspecialchars($movie['title']); ?></td>
                                <td><?php echo htmlspecialchars($movie['category_name']); ?></td>
                                <td><?php echo htmlspecialchars($movie['release_year']); ?></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="edit_movie.php?id=<?php echo $movie['id']; ?>" 
                                           class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                            <input type="hidden" name="movie_id" value="<?php echo $movie['id']; ?>">
                                            <button type="submit" name="delete_movie" 
                                                    class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('select-all').addEventListener('change', function() {
    document.querySelectorAll('input[name="movie_ids[]"]')
        .forEach(cb => cb.checked = this.checked);
});
</script>
</body>
</html> 