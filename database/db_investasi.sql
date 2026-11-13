CREATE DATABASE IF NOT EXISTS db_investasi;
USE db_investasi;

-- 1. Tabel users
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);

INSERT INTO users (id, username, password) VALUES 
(1, 'admin', MD5('admin123'));

-- 2. Tabel investasi_barang
DROP TABLE IF EXISTS investasi_barang;
CREATE TABLE investasi_barang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    nama_barang VARCHAR(100) NOT NULL,
    kategori VARCHAR(50) NOT NULL,
    harga_beli INT NOT NULL,
    harga_sekarang INT NOT NULL
);
