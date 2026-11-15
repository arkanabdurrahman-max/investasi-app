<?php 
session_start();

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit;
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once 'config/koneksi.php';

$user_id = intval($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Aplikasi Investasi Barang</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        html { scroll-behavior: smooth; }
        header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; }
        .nav-links { display: flex; gap: 20px; align-items: center; }
        .nav-links a { text-decoration: none; color: inherit; font-weight: 600; font-size: 14px; }
        .nav-links a:hover { color: #3b82f6; }
        .grid-pasar { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 20px; }
        .card-pasar { border: 1px solid rgba(148, 163, 184, 0.3); border-radius: 8px; padding: 14px; display: flex; flex-direction: column; }
        .card-pasar img { width: 100%; height: 150px; object-fit: cover; border-radius: 6px; margin-bottom: 10px; }
        .section-box { margin-bottom: 50px; padding-bottom: 30px; border-bottom: 1px solid rgba(148, 163, 184, 0.2); }
    </style>
</head>
<body>

    <!-- Navbar Utama -->
    <header>
        <h2 style="margin: 0; font-size: 20px;">Aplikasi Investasi Barang (IB)</h2>
        
        <nav class="nav-links">
            <a href="#aset-saya">Aset Saya</a>
            <a href="#pasar-tawar">Pasar Tawar</a>
            <a href="#tawaran-masuk">Tawaran Masuk</a>
        </nav>

        <div style="display: flex; gap: 15px; align-items: center;">
            <span>Halo, <strong><?= htmlspecialchars($_SESSION['user']); ?></strong></span>
            <button id="theme-toggle" class="theme-switch-btn">🌙 Dark Mode</button>
            <a href="logout.php" style="color: #ef4444; font-weight: bold; text-decoration: none;">Keluar</a>
        </div>
    </header>

    <main class="main-dashboard">

        <!-- 1. DAFTAR ASET SAYA -->
        <section id="aset-saya" class="section-box">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="margin: 0;">Daftar Aset Investasi Terdaftar</h3>
                <a href="tambah.php" class="btn" style="width: auto; padding: 10px 18px;">➕ Tambah Barang</a>
            </div>

            <div style="overflow-x: auto;">
                <table border="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Foto</th>
                            <th>Nama Aset</th>
                            <th>Kategori</th>
                            <th>Harga Pokok</th>
                            <th>Nilai Investasi (Cuan)</th>
                            <th>Keterangan</th>
                            <th style="text-align: center;">Aksi Operasional</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM investasi_barang WHERE user_id = $user_id ORDER BY id ASC";
                        $result = mysqli_query($conn, $query);

                        if ($result && mysqli_num_rows($result) > 0) {
                            $no = 1;
                            while ($row = mysqli_fetch_assoc($result)) {
                                $selisih = $row['harga_sekarang'] - $row['harga_beli'];
                                $status_cuan = ($selisih >= 0) ? "cuan" : "";
                                $foto_path = (!empty($row['foto'])) ? "assets/img/" . htmlspecialchars($row['foto']) : "";
                        ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td>
                                    <?php if ($foto_path !== ""): ?>
                                        <img src="<?= $foto_path; ?>" alt="Foto Aset" style="width: 55px; height: 55px; object-fit: cover; border-radius: 6px;">
                                    <?php else: ?>
                                        <span style="font-size: 12px; color: gray;">No Image</span>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?= htmlspecialchars($row['nama_barang']); ?></strong></td>
                                <td><?= htmlspecialchars($row['kategori']); ?></td>
                                <td>Rp <?= number_format($row['harga_pokok'], 0, ',', '.'); ?></td>
                                <td>
                                    Rp <?= number_format($row['harga_sekarang'], 0, ',', '.'); ?> 
                                    (<span class="<?= $status_cuan; ?>">Rp <?= number_format($selisih, 0, ',', '.'); ?></span>)
                                </td>
                                <td><?= nl2br(htmlspecialchars($row['keterangan'])); ?></td>
                                <td style="text-align: center; white-space: nowrap;">
                                    <a href="edit.php?id=<?= $row['id']; ?>" style="color: #3b82f6; text-decoration: none; font-weight: 600; margin-right: 10px;">Edit</a>
                                    <a href="hapus.php?id=<?= $row['id']; ?>" class="btn-danger action-hapus">Hapus</a>
                                </td>
                            </tr>
                        <?php 
                            }
                        } else {
                            echo "<tr><td colspan='8' style='text-align: center; color: var(--text-muted); padding: 20px;'>Belum ada data barang untuk akun ini.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
        
        <!--PASAR TAWAR-MENAWAR (ASET USER LAIN) -->
        <section id="pasar-tawar" class="section-box">
            <h3 style="margin: 0 0 20px 0;">Pasar Aset (Tawar Barang User Lain)</h3>
            <div class="grid-pasar">
                <?php
                $q_pasar = mysqli_query($conn, "SELECT b.*, u.username AS pemilik FROM investasi_barang b JOIN users u ON b.user_id = u.id WHERE b.user_id != $user_id ORDER BY b.id DESC");
                if ($q_pasar && mysqli_num_rows($q_pasar) > 0) {
                    while ($item = mysqli_fetch_assoc($q_pasar)) {
                        $foto_p = (!empty($item['foto']) && file_exists("assets/img/" . $item['foto'])) 
                                  ? "assets/img/" . htmlspecialchars($item['foto']) 
                                  : "https://placeholder.com";
                ?>
                    <div class="card-pasar">
                        <img src="<?= $foto_p; ?>" alt="Foto Barang">
                        <small style="color: #3b82f6; font-weight: bold;"><?= htmlspecialchars($item['kategori']); ?> • Pemilik: <?= htmlspecialchars($item['pemilik']); ?></small>
                        <h4 style="margin: 6px 0;"><?= htmlspecialchars($item['nama_barang']); ?></h4>
                        <p style="font-size: 13px; margin: 0 0 12px; flex: 1; opacity: 0.85;"><?= nl2br(htmlspecialchars($item['keterangan'])); ?></p>
                        <strong style="display: block; margin-bottom: 12px; font-size: 15px;">Harga: Rp <?= number_format($item['harga_sekarang'], 0, ',', '.'); ?></strong>
                        
                        <form action="tawar.php" method="POST" style="display: flex; gap: 8px; margin: 0;">
                            <input type="hidden" name="barang_id" value="<?= $item['id']; ?>">
                            <input type="number" name="harga_tawar" placeholder="Nominal tawar" required style="width: 100%; padding: 8px; border-radius: 6px;">
                            <button type="submit" name="kirim_tawar" class="btn" style="width: auto; padding: 8px 14px; margin: 0;">Tawar</button>
                        </form>
                    </div>
                <?php 
                    }
                } else {
                    echo "<p style='color: var(--text-muted); margin: 0;'>Belum ada barang dari akun lain untuk ditawar.</p>";
                }
                ?>
            </div>
        </section>

        <!-- 3. TAWARAN MASUK -->
        <section id="tawaran-masuk">
            <h3 style="margin: 0 0 20px 0;">Tawaran Masuk untuk Aset Saya</h3>
            <div style="overflow-x: auto;">
                <table border="0">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Penawar</th>
                            <th>Harga Pasarmu</th>
                            <th>Harga Ditawar</th>
                            <th>Status</th>
                            <th style="text-align: center;">Keputusan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $q_tawar = mysqli_query($conn, "SELECT p.*, b.nama_barang, b.harga_sekarang, u.username AS penawar FROM penawaran p JOIN investasi_barang b ON p.barang_id = b.id JOIN users u ON p.penawar_id = u.id WHERE b.user_id = $user_id ORDER BY p.id DESC");
                        if ($q_tawar && mysqli_num_rows($q_tawar) > 0) {
                            while ($tw = mysqli_fetch_assoc($q_tawar)) {
                        ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($tw['nama_barang']); ?></strong></td>
                                <td><?= htmlspecialchars($tw['penawar']); ?></td>
                                <td>Rp <?= number_format($tw['harga_sekarang'], 0, ',', '.'); ?></td>
                                <td style="color: #3b82f6; font-weight: bold;">Rp <?= number_format($tw['harga_tawar'], 0, ',', '.'); ?></td>
                                <td><?= htmlspecialchars($tw['status']); ?></td>
                                <td style="text-align: center;">
                                    <?php if ($tw['status'] === 'Pending'): ?>
                                        <a href="tawar.php?aksi=terima&id=<?= $tw['id']; ?>" style="color: #10b981; font-weight: 600; text-decoration: none; margin-right: 12px;">Terima</a>
                                        <a href="tawar.php?aksi=tolak&id=<?= $tw['id']; ?>" style="color: #ef4444; font-weight: 600; text-decoration: none;">Tolak</a>
                                    <?php else: ?>
                                        <span style="color: var(--text-muted); font-size: 13px;">Selesai</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php 
                            }
                        } else {
                            echo "<tr><td colspan='6' style='text-align: center; color: var(--text-muted); padding: 20px;'>Tidak ada tawaran masuk.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>

    </main>

    <script src="assets/js/app.js"></script>
</body>
</html>
