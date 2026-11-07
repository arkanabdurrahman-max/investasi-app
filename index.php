<?php
session_start();
// Proteksi: Jika belum login, tendang balik ke login.php
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
require 'config/koneksi.php';

// Ambil data asli dari MySQL
$result = mysqli_query($conn, "SELECT * FROM investasi_barang ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Investasi</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h2>Dashboard Aplikasi Investasi Barang (IB) (Halo, <?= htmlspecialchars($_SESSION['user']); ?>!)</h2>
            <a href="logout.php" class="btn-danger">Logout</a>
        </header>

        <div style="margin-bottom: 20px;">
            <a href="tambah.php" class="btn" style="width: auto;">+ Tambah Aset Baru</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Harga Beli</th>
                    <th>Harga Sekarang</th>
                    <th>Estimasi Profit/Loss</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) == 0) : ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: #9ca3af;">Belum ada data investasi. Klik tambah aset baru!</td>
                </tr>
                <?php endif; ?>
                
                <?php while ($row = mysqli_fetch_assoc($result)) : 
                    $selisih = $row['harga_sekarang'] - $row['harga_beli'];
                    $status_cuan = ($selisih >= 0) ? 'cuan' : 'btn-danger'; 
                ?>
                <tr>
                    <td><?= htmlspecialchars($row['nama_barang']); ?></td>
                    <td><?= htmlspecialchars($row['kategori']); ?></td>
                    <td>Rp <?= number_format($row['harga_beli'], 0, ',', '.'); ?></td>
                    <td>Rp <?= number_format($row['harga_sekarang'], 0, ',', '.'); ?></td>
                    <td class="<?= $status_cuan; ?>">
                        <?= ($selisih >= 0) ? '+ Rp ' : '- Rp '; ?><?= number_format(abs($selisih), 0, ',', '.'); ?>
                    </td>
                    <td>
                        <a href="edit.php?id=<?= $row['id']; ?>" class="btn" style="width: auto; padding: 5px 10px; font-size: 13px;">Edit</a> | 
                        <a href="hapus.php?id=<?= $row['id']; ?>" class="btn-danger action-hapus">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <script src="assets/js/app.js"></script>
</body>
</html>