<?php
session_start();
if (!isset($_SESSION['login'])) { header("Location: login.php"); exit; }
require 'config/koneksi.php';

if (isset($_POST['submit'])) {
    $user_id = intval($_SESSION['user_id']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $harga_pokok = intval($_POST['harga_pokok']);
    $harga_beli = intval($_POST['harga_beli']);
    $harga_sekarang = intval($_POST['harga_sekarang']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);

    // Proses Upload Foto
    $nama_foto = "";
    if (!empty($_FILES['foto']['name'])) {
        $ekstensi = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $nama_foto = time() . "_" . uniqid() . "." . $ekstensi;
        $tujuan = "assets/img/" . $nama_foto;
        move_uploaded_file($_FILES['foto']['tmp_name'], $tujuan);
    }

    mysqli_query($conn, "ALTER TABLE investasi_barang AUTO_INCREMENT = 1");
    $query = "INSERT INTO investasi_barang (user_id, nama_barang, kategori, foto, harga_pokok, harga_beli, harga_sekarang, keterangan) 
              VALUES ($user_id, '$nama', '$kategori', '$nama_foto', $harga_pokok, $harga_beli, $harga_sekarang, '$keterangan')";
    
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
        <form id="tambahForm" action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nama Barang</label>
                <input type="text" name="nama_barang" required autocomplete="off">
            </div>
            <div class="form-group">
                <label>Kategori</label>
                <input type="text" name="kategori" required autocomplete="off">
            </div>
            <div class="form-group">
                <label>Foto Barang</label>
                <input type="file" name="foto" accept="image/*" required>
            </div>
            <div class="form-group">
                <label>Harga Pokok (Rp)</label>
                <input type="number" name="harga_pokok" required>
            </div>
            <div class="form-group">
                <label>Harga Beli (Rp)</label>
                <input type="number" id="hargaBeli" name="harga_beli" required>
            </div>
            <div class="form-group">
                <label>Harga Pasar Saat Ini (Rp)</label>
                <input type="number" id="hargaSekarang" name="harga_sekarang" required>
            </div>
            <div class="form-group">
                <label>Keterangan Detail</label>
                <textarea name="keterangan" rows="3" style="width: 100%; padding: 8px; border-radius: 5px;" required></textarea>
            </div>
            <button type="submit" name="submit" class="btn">Simpan Aset</button>
            <a href="index.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
    <script src="assets/js/app.js"></script>
</body>
</html>
