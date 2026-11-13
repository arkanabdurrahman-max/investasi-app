<?php
session_start();
if (!isset($_SESSION['login'])) { header("Location: login.php"); exit; }
require 'config/koneksi.php';

if (isset($_POST['submit'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $harga_beli = intval($_POST['harga_beli']);
    $harga_sekarang = intval($_POST['harga_sekarang']);

    mysqli_query($conn, "ALTER TABLE investasi_barang AUTO_INCREMENT = 1");
    $query = "INSERT INTO investasi_barang (nama_barang, kategori, harga_beli, harga_sekarang) VALUES ('$nama', '$kategori', $harga_beli, $harga_sekarang)";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Aset berhasil disimpan!'); window.location.href='index.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Aset Investasi</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="form-container">
        <h2>Tambah Investasi Baru</h2>
        <form id="tambahForm" action="" method="POST">
            <div class="form-group">
                <label>Nama Barang</label>
                <input type="text" name="nama_barang" required autocomplete="off">
            </div>
            <div class="form-group">
                <label>Kategori</label>
                <input type="text" name="kategori" required autocomplete="off">
            </div>
            <div class="form-group">
                <label>Harga Beli (Rp)</label>
                <input type="number" id="hargaBeli" name="harga_beli" required>
            </div>
            <div class="form-group">
                <label>Harga Pasar Saat Ini (Rp)</label>
                <input type="number" id="hargaSekarang" name="harga_sekarang" required>
            </div>
            <button type="submit" name="submit" class="btn">Simpan Aset</button>
            <a href="index.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
    <script src="assets/js/app.js"></script>
</body>
</html>