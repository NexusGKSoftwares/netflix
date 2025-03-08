CREATE DATABASE netflix_clone;
USE netflix_clone;

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_admin BOOLEAN DEFAULT FALSE
);

CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL
);

CREATE TABLE movies (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    thumbnail VARCHAR(255),
    video_url VARCHAR(255),
    category_id INT,
    release_year YEAR,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE watchlist (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    movie_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (movie_id) REFERENCES movies(id)
);

-- Add sample categories
INSERT INTO categories (name) VALUES 
('Action'),
('Comedy'),
('Drama'),
('Horror'),
('Sci-Fi'),
('Documentary'),
('Romance'),
('Thriller');

-- Add sample movies
INSERT INTO movies (title, description, thumbnail, video_url, category_id, release_year) VALUES
('The Matrix', 'A computer programmer discovers a mysterious world of digital reality', 'uploads/thumbnails/matrix.jpg', 'https://example.com/matrix.mp4', 5, 1999),
('Inception', 'A thief enters dreams to steal secrets from the subconscious', 'uploads/thumbnails/inception.jpg', 'https://example.com/inception.mp4', 5, 2010),
('The Shawshank Redemption', 'Two imprisoned men bond over several years', 'uploads/thumbnails/shawshank.jpg', 'https://example.com/shawshank.mp4', 3, 1994),
('Pulp Fiction', 'Various interconnected stories of criminals in Los Angeles', 'uploads/thumbnails/pulp_fiction.jpg', 'https://example.com/pulp_fiction.mp4', 1, 1994),
('The Dark Knight', 'Batman faces his greatest challenge against the Joker', 'uploads/thumbnails/dark_knight.jpg', 'https://example.com/dark_knight.mp4', 1, 2008),
('Forrest Gump', 'The life journey of a man who influences historical events', 'uploads/thumbnails/forrest_gump.jpg', 'https://example.com/forrest_gump.mp4', 3, 1994),
('The Silence of the Lambs', 'An FBI trainee seeks help from an imprisoned cannibal', 'uploads/thumbnails/silence_lambs.jpg', 'https://example.com/silence_lambs.mp4', 8, 1991),
('Jurassic Park', 'Dinosaurs are brought back to life in a theme park', 'uploads/thumbnails/jurassic_park.jpg', 'https://example.com/jurassic_park.mp4', 5, 1993);

-- Create an admin user (password: admin123)
INSERT INTO users (username, email, password, is_admin) VALUES
('admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', TRUE); 