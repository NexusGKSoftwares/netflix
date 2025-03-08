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
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-netflix-black text-white min-h-screen">
    <nav class="bg-black py-4 px-6 fixed w-full z-50">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="text-netflix-red text-2xl font-bold">Netflix Clone</div>
            <div class="space-x-6">
                <a href="index.php" class="text-white hover:text-gray-300">Home</a>
                <a href="watchlist.php" class="text-white hover:text-gray-300">My List</a>
                <a href="logout.php" class="text-white hover:text-gray-300">Logout</a>
            </div>
        </div>
    </nav>

    <div class="pt-20 px-6">
        <div class="max-w-7xl mx-auto">
            <?php foreach ($moviesByCategory as $category => $categoryMovies): ?>
                <div class="mb-8">
                    <h2 class="text-2xl font-bold mb-4"><?php echo htmlspecialchars($category); ?></h2>
                    <div class="flex overflow-x-auto space-x-4 pb-4">
                        <?php foreach ($categoryMovies as $movie): ?>
                            <div class="flex-none w-64 transform hover:scale-105 transition-transform duration-300">
                                <div class="rounded-lg overflow-hidden shadow-lg bg-gray-900">
                                    <img src="<?php echo htmlspecialchars($movie['thumbnail']); ?>" 
                                         alt="<?php echo htmlspecialchars($movie['title']); ?>"
                                         class="w-full h-96 object-cover">
                                    <div class="p-4">
                                        <h3 class="text-lg font-semibold mb-2">
                                            <?php echo htmlspecialchars($movie['title']); ?>
                                        </h3>
                                        <p class="text-gray-400 mb-4">
                                            <?php echo htmlspecialchars($movie['release_year']); ?>
                                        </p>
                                        <div class="space-y-2">
                                            <a href="watch.php?id=<?php echo $movie['id']; ?>" 
                                               class="btn-primary block text-center">Watch Now</a>
                                            <a href="add_to_watchlist.php?id=<?php echo $movie['id']; ?>" 
                                               class="border border-white text-white px-4 py-2 rounded block text-center hover:bg-white hover:text-black transition-colors">
                                                Add to My List
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
    </div>
</body>
</html> 