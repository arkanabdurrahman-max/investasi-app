CREATE DATABASE IF NOT EXISTS `db_investasi`;
USE `db_investasi`;


CREATE TABLE IF NOT EXISTS `users` (
  `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$w/k4FvqQW15Z0B4YlP4zM.1c8W7zD7Z3M1f2A3B4C5D6E7F8G9H0I')
ON DUPLICATE KEY UPDATE `id`=`id`;

CREATE TABLE IF NOT EXISTS `investasi_barang` (
  `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
  `nama_barang` VARCHAR(100) NOT NULL,
  `kategori` VARCHAR(50) NOT NULL,
  `harga_beli` INT(11) NOT NULL,
  `harga_sekarang` INT(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `investasi_barang` (`id`, `nama_barang`, `kategori`, `harga_beli`, `harga_sekarang`) VALUES
(1, 'Emas Antam 10 Gram', 'Logam Mulia', 13500000, 14200000),
(2, 'Sepatu Nike Air Jordan 1', 'Sneakers', 3500000, 3100000)
ON DUPLICATE KEY UPDATE `id`=`id`;
