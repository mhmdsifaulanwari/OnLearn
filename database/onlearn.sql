CREATE DATABASE IF NOT EXISTS onlearn;
USE onlearn;


CREATE TABLE users (
    userId INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    login_attempt INT DEFAULT 0,
    locked_until DATETIME NULL
);


CREATE TABLE admins (
    adminId INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);


CREATE TABLE materi (
    materiId INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    isiMateri TEXT NOT NULL,
    jenjang ENUM('SD','SMP','SMA/SMK') NOT NULL
);


CREATE TABLE quiz (
    quizId INT AUTO_INCREMENT PRIMARY KEY,
    materiId INT NOT NULL,
    pertanyaan TEXT NOT NULL,
    pilihanA VARCHAR(255) NOT NULL,
    pilihanB VARCHAR(255) NOT NULL,
    pilihanC VARCHAR(255) NOT NULL,
    pilihanD VARCHAR(255) NOT NULL,
    jawabanBenar ENUM('A','B','C','D') NOT NULL,
    FOREIGN KEY (materiId) REFERENCES materi(materiId)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);


CREATE TABLE diskusi (
    diskusiId INT AUTO_INCREMENT PRIMARY KEY,
    userId INT NOT NULL,
    komentar VARCHAR(350) NOT NULL,
    tanggal DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userId) REFERENCES users(userId)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);


CREATE TABLE laporan_bug (
    bugId INT AUTO_INCREMENT PRIMARY KEY,
    userId INT NOT NULL,
    deskripsi TEXT NOT NULL,
    status ENUM('Pending','Diproses','Selesai') DEFAULT 'Pending',
    tanggal DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userId) REFERENCES users(userId)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);


INSERT INTO admins (email, password)
VALUES (
    'admin@onlearn.com',
    '$2y$10$dummyhashpassword'
);