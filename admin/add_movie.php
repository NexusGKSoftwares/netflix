<?php
require_once '../middleware/admin_auth.php';
checkAdminAccess();
require_once '../config/database.php';

$pageTitle = 'Add Movie - Netflix Clone';
require_once '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category_id = $_POST['category_id'];
    $release_year = $_POST['release_year'];
    $video_url = $_POST['video_url'];

    // Handle thumbnail upload
    $thumbnail = '';
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == 0) {
        $upload_dir = '../uploads/thumbnails/';
        $filename = uniqid() . '_' . $_FILES['thumbnail']['name'];
        move_uploaded_file($_FILES['thumbnail']['tmp_name'], $upload_dir . $filename);
        $thumbnail = 'uploads/thumbnails/' . $filename;
    }

    $stmt = $pdo->prepare("
        INSERT INTO movies (title, description, thumbnail, video_url, category_id, release_year) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    
    try {
        $stmt->execute([$title, $description, $thumbnail, $video_url, $category_id, $release_year]);
        header("Location: movies.php");
        exit();
    } catch(PDOException $e) {
        $error = "Failed to add movie. Please try again.";
    }
}

// Fetch categories for the dropdown
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
?>

<?php include 'includes/admin_nav.php'; ?>

<div class="container mt-5 pt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1 class="mb-4">Add New Movie</h1>

            <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

            <div class="card bg-dark">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" required class="form-control bg-dark text-white">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" required class="form-control bg-dark text-white" rows="4"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category_id" required class="form-select bg-dark text-white">
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category['id']; ?>">
                                        <?php echo htmlspecialchars($category['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Release Year</label>
                            <input type="number" name="release_year" required class="form-control bg-dark text-white">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Thumbnail</label>
                            <input type="file" name="thumbnail" required class="form-control bg-dark text-white">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Video URL</label>
                            <input type="url" name="video_url" required class="form-control bg-dark text-white">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Preview</label>
                            <div class="card bg-dark">
                                <img id="thumbnail-preview" class="card-img-top" style="max-height: 300px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 id="title-preview" class="card-title"></h5>
                                    <p id="description-preview" class="card-text"></p>
                                    <p id="year-preview" class="card-text"><small class="text-muted"></small></p>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-netflix w-100">Add Movie</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Live preview
document.querySelector('input[name="title"]').addEventListener('input', function() {
    document.getElementById('title-preview').textContent = this.value;
});

document.querySelector('textarea[name="description"]').addEventListener('input', function() {
    document.getElementById('description-preview').textContent = this.value;
});

document.querySelector('input[name="release_year"]').addEventListener('input', function() {
    document.getElementById('year-preview').textContent = this.value;
});

document.querySelector('input[name="thumbnail"]').addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('thumbnail-preview').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});
</script>
</body>
</html> 