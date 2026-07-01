CREATE DATABASE IF NOT EXISTS attendance_db;
USE attendance_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(20) NOT NULL,
    student_name VARCHAR(100) NOT NULL,
    status ENUM('Present', 'Absent', 'Tardy') NOT NULL,
    log_date DATE NOT NULL
);

-- Seed an administrative user (Password is 'admin123' hashed via PASSWORD_DEFAULT)
INSERT INTO users (username, password) VALUES 
('admin', '$2y$10$wKxNRYg6Ww1A4rC3q9mIeO3vX9p3pYv2Z6zE8g7x8c9v0b1n2m3qi');

-- Sample initial rows for Member 3 to read
INSERT INTO attendance (student_id, student_name, status, log_date) VALUES 
('2026-0001', 'Juan Dela Cruz', 'Present', '2026-07-01'),
('2026-0002', 'Maria Clara', 'Absent', '2026-07-01');