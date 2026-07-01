DROP TABLE IF EXISTS `attendance`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL
);

CREATE TABLE `attendance` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `student_id` VARCHAR(20) NOT NULL,
    `student_name` VARCHAR(100) NOT NULL,
    `status` VARCHAR(20) NOT NULL,
    `log_date` DATE NOT NULL
);

-- Plaintext Passwords (Username + 123)
INSERT INTO `users` (`username`, `password`) VALUES 
('admin', 'admin123'),
('leader', 'leader123'),
('dev2', 'dev2123'),
('dev3', 'dev3123'),
('dev4', 'dev4123');
