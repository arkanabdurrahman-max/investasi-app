<?php
session_start();
if (!isset($_SESSION['login'])) { header("Location: login.php"); exit; }
require 'config/koneksi.php';

$id = intval($_GET['id']);
$user_id = intval($_SESSION['user_id']);
$result = mysqli_query($conn, "SELECT * FROM investasi_barang WHERE id = $id AND user_id = $user_id");
$row = mysqli_fetch_assoc($result);

if (!$row) { header("Location: index.php"); exit; }

if (isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $harga_pokok = intval($_POST['harga_pokok']);
    $harga_beli = intval($_POST['harga_beli']);
    $harga_sekarang = intval($_POST['harga_sekarang']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
    $nama_foto = $row['foto']; // Default pakai foto lama

    // Jika user mengunggah foto baru
    if (!empty($_FILES['foto']['name'])) {
        // Hapus foto lama jika ada di folder
        if (!empty($row['foto']) && file_exists("assets/img/" . $row['foto'])) {
            unlink("assets/img/" . $row['foto']);
        }
        $ekstensi = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $nama_foto = time() . "_" . uniqid() . "." . $ekstensi;
        move_uploaded_file($_FILES['foto']['tmp_name'], "assets/img/" . $nama_foto);
    }

    $query = "UPDATE investasi_barang SET 
              nama_barang='$nama', 
              kategori='$kategori', 
              foto='$nama_foto', 
              harga_pokok=$harga_pokok, 
              harga_beli=$harga_beli, 
              harga_sekarang=$harga_sekarang, 
              keterangan='$keterangan' 
              WHERE id=$id AND user_id=$user_id";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Data berhasil diubah!'); window.location.href='index.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Aset Investasi</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="form-container">
        <h2>Edit Aset Investasi</h2>
        <form id="editForm" action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nama Barang</label>
                <input type="text" name="nama_barang" value="<?= htmlspecialchars($row['nama_barang']); ?>" required>
            </div>
            <div class="form-group">
                <label>Kategori</label>
                <input type="text" name="kategori" value="<?= htmlspecialchars($row['kategori']); ?>" required>
            </div>
            <div class="form-group">
                <label>Foto Barang (Biarkan kosong jika tidak diganti)</label>
                <?php if (!empty($row['foto'])): ?>
                    <div style="margin-bottom: 8px;">
                        <img src="assets/img/<?= htmlspecialchars($row['foto']); ?>" width="80" style="border-radius: 6px;">
                    </div>
                <?php endif; ?>
                <input type="file" name="foto" accept="image/*">
            </div>
            <div class="form-group">
                <label>Harga Pokok (Rp)</label>
                <input type="number" name="harga_pokok" value="<?= $row['harga_pokok']; ?>" required>
            </div>
            <div class="form-group">
                <label>Harga Beli (Rp)</label>
                <input type="number" id="hargaBeli" name="harga_beli" value="<?= $row['harga_beli']; ?>" required>
            </div>
            <div class="form-group">
                <label>Harga Pasar Saat Ini (Rp)</label>
                <input type="number" id="hargaSekarang" name="harga_sekarang" value="<?= $row['harga_sekarang']; ?>" required>
            </div>
            <div class="form-group">
                <label>Keterangan Detail</label>
                <textarea name="keterangan" rows="3" style="width: 100%; padding: 8px; border-radius: 5px;" required><?= htmlspecialchars($row['keterangan']); ?></textarea>
            </div>
            <button type="submit" name="update" class="btn">Simpan Perubahan</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
    <script src="assets/js/app.js"></script>
</body>
</html>
