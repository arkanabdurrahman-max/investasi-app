SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+07:00";

-- Tabel Users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `users` (`id`, `username`, `password`) VALUES
(1, 'admin', '202cb962ac59075b964b07152d234b70');

-- Tabel Investasi Barang (Revisi Dosen: foto, harga_pokok, keterangan)
CREATE TABLE IF NOT EXISTS `investasi_barang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `harga_pokok` int(11) NOT NULL,
  `harga_beli` int(11) NOT NULL,
  `harga_sekarang` int(11) NOT NULL,
  `keterangan` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `fk_user_investasi` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Penawaran (Fitur Tawar-Menawar)
CREATE TABLE IF NOT EXISTS `penawaran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `barang_id` int(11) NOT NULL,
  `penawar_id` int(11) NOT NULL,
  `harga_tawar` int(11) NOT NULL,
  `status` enum('Pending','Diterima','Ditolak') NOT NULL DEFAULT 'Pending',
  PRIMARY KEY (`id`),
  KEY `barang_id` (`barang_id`),
  KEY `penawar_id` (`penawar_id`),
  CONSTRAINT `fk_tawar_barang` FOREIGN KEY (`barang_id`) REFERENCES `investasi_barang` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_tawar_user` FOREIGN KEY (`penawar_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;
