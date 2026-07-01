CREATE DATABASE IF NOT EXISTS attendance_db;
USE attendance_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(20) NOT NULL,
    student_name VARCHAR(100) NOT NULL,
    status VARCHAR(20) NOT NULL,
    log_date DATE NOT NULL
);

-- Seed admin (Password is 'admin123' mapped to PHP PASSWORD_DEFAULT hash)
INSERT INTO users (username, password) VALUES 
('admin', '$2y$10$wKxNRYg6Ww1A4rC3q9mIeO3vX9p3pYv2Z6zE8g7x8c9v0b1n2m3qi');
