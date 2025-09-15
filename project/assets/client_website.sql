CREATE DATABASE client_website CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE client_website;

-- 1. Admin Users
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin user (password: admin123)
INSERT INTO admins (username, password) 
VALUES ('admin', '$2y$10$u8jDe37whsQJHp0jZhy7M.qLRgxG8gMp9GyADrGV4A49YNGmJcQve');

-- 2. Navigation Menu
CREATE TABLE menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL,
    parent_id INT DEFAULT NULL,
    sort_order INT DEFAULT 0,
    status TINYINT(1) DEFAULT 1,
    FOREIGN KEY (parent_id) REFERENCES menu_items(id) ON DELETE CASCADE
);

-- 3. Pages (Objective, Mission, Vision, About, etc.)
CREATE TABLE pages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    content TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 4. Banners
CREATE TABLE banners (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150),
    image VARCHAR(255),
    link VARCHAR(255),
    position ENUM('top','bottom','sidebar') DEFAULT 'top',
    status TINYINT(1) DEFAULT 1
);

-- 5. News / Editorials / Press
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type ENUM('news','editorial','press') NOT NULL,
    title VARCHAR(200) NOT NULL,
    content TEXT,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 6. Complaints
CREATE TABLE complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(20),
    message TEXT NOT NULL,
    status ENUM('pending','resolved') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 7. Contributions (Sahyog Kare)
CREATE TABLE contributions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(20),
    amount DECIMAL(10,2),
    payment_status ENUM('pending','completed','failed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 8. Gallery
CREATE TABLE gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150),
    image VARCHAR(255),
    category VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
