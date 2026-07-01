CREATE DATABASE IF NOT EXISTS attendance_db;
USE attendance_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(50) NOT NULL,
    student_name VARCHAR(100) NOT NULL,
    status VARCHAR(15) NOT NULL,
    log_date DATE NOT NULL
);

INSERT INTO users (username, password) VALUES 
('Verceles', 'Verceles'),
('Dacanay', 'Dacanay'),
('Enriquez', 'Enriquez'),
('Cueto', 'Cueto'),
('Usana', 'Usana');
