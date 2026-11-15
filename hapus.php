<?php
session_start();
if (!isset($_SESSION['login'])) { header("Location: login.php"); exit; }
require 'config/koneksi.php';

$id = intval($_GET['id']);
$user_id = intval($_SESSION['user_id']);

// 1. Cek dan hapus file foto di folder assets/img/ terlebih dahulu
$cek = mysqli_query($conn, "SELECT foto FROM investasi_barang WHERE id = $id AND user_id = $user_id");
$data = mysqli_fetch_assoc($cek);

if ($data && !empty($data['foto']) && file_exists("assets/img/" . $data['foto'])) {
    unlink("assets/img/" . $data['foto']);
}

// 2. Hapus data dari database dan rapikan urutan ID
if (mysqli_query($conn, "DELETE FROM investasi_barang WHERE id = $id AND user_id = $user_id")) {
    mysqli_query($conn, "SET @num := 0");
    mysqli_query($conn, "UPDATE investasi_barang SET id = @num := (@num + 1)");
    mysqli_query($conn, "ALTER TABLE investasi_barang AUTO_INCREMENT = 1");

    header("Location: index.php");
    exit;
}
?>
