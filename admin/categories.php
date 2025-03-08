<?php
require_once '../middleware/admin_auth.php';
checkAdminAccess();
require_once '../config/database.php';

$pageTitle = 'Manage Categories - Netflix Clone';
require_once '../includes/header.php';

// Handle category addition
if (isset($_POST['add_category'])) {
    $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
    $stmt->execute([$_POST['name']]);
}

// Handle category deletion
if (isset($_POST['delete_category'])) {
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$_POST['category_id']]);
}

// Fetch all categories
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
?>

<?php include 'includes/admin_nav.php'; ?>

<div class="container mt-5 pt-4">
    <h1 class="mb-4">Manage Categories</h1>

    <div class="row">
        <!-- Add Category Form -->
        <div class="col-md-6 mb-4">
            <div class="card bg-dark">
                <div class="card-body">
                    <h5 class="card-title">Add New Category</h5>
                    <form method="POST">
                        <div class="mb-3">
                            <input type="text" name="name" placeholder="Category Name" required 
                                   class="form-control bg-dark text-white">
                        </div>
                        <button type="submit" name="add_category" class="btn btn-netflix w-100">
                            Add Category
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Categories List -->
        <div class="col-md-6 mb-4">
            <div class="card bg-dark">
                <div class="card-body">
                    <h5 class="card-title">Existing Categories</h5>
                    <div class="list-group bg-dark">
                        <?php foreach ($categories as $category): ?>
                            <div class="list-group-item bg-dark text-white d-flex justify-content-between align-items-center">
                                <span><?php echo htmlspecialchars($category['name']); ?></span>
                                <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                    <input type="hidden" name="category_id" value="<?php echo $category['id']; ?>">
                                    <button type="submit" name="delete_category" 
                                            class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 