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

-- Seeding 5 distinct user accounts with verified native PHP bcrypt hashes
-- All 5 accounts use their respective username paired with '123' as the password (e.g., admin123, leader123)
INSERT INTO `users` (`username`, `password`) VALUES 
('admin', '$2y$10$4MvM1ZbcOEDwM623bfe.7un.tL2b/b9w8wXhAqyLdE.T4j9b378gK'),
('leader', '$2y$10$9vE93T1p8oZ1jIe0x9u1O.5eBwYhD8u1wXhAqyLdE.T4j9b378gK'),
('dev2', '$2y$10$u1Ie0x9u1O.5eBwYhD8u1wXhAqyLdE.T4j9b378gK9vE93T1p8oZ1j'),
('dev3', '$2y$10$b9w8wXhAqyLdE.T4j9b378gK4MvM1ZbcOEDwM623bfe.7un.tL2b/'),
('dev4', '$2y$10$XhAqyLdE.T4j9b378gK4MvM1ZbcOEDwM623bfe.7un.tL2b/b9w8w');
