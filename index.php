<?php 
session_start();

// Proteksi Sesi Ketat
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit;
}

// Mekanisme Keamanan Cache Kontrol setelah Logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once 'config/koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Aplikasi Investasi Barang</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <!-- Header Panel Utama -->
    <header>
        <h2>Aplikasi Investasi Barang (IB)</h2>
        <div style="display: flex; gap: 20px; align-items: center;">
            <span>Selamat Datang, <strong><?php echo htmlspecialchars($_SESSION['user']); ?></strong></span>
            <!-- Tombol Sakelar Tema Global -->
            <button id="theme-toggle" class="theme-switch-btn">🌙 Dark Mode</button>
            <a href="logout.php" style="color: #ef4444; font-weight: bold; text-decoration: none;">Keluar</a>
        </div>
    </header>

    <!-- Papan Kendali Data CRUD -->
    <main class="main-dashboard">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h3>Daftar Aset Investasi Terdaftar</h3>
            <a href="tambah.php" class="btn" style="width: auto; padding: 10px 18px;">➕ Tambah Barang</a>
        </div>

        <table border="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Aset</th>
                    <th>Kategori</th>
                    <th>Nilai Investasi (Cuan)</th>
                    <th style="text-align: center;">Aksi Operasional</th>
                </tr>
            </thead>
                        <tbody>
                <?php
                $user_id = intval($_SESSION['user_id']);
                // Hanya tampilkan data milik akun yang sedang login
                $query = "SELECT * FROM investasi_barang WHERE user_id = $user_id ORDER BY id ASC";
                $result = mysqli_query($conn, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                    $no = 1; // Nomor urut tabel per akun mulai dari 1
                    while ($row = mysqli_fetch_assoc($result)) {
                        $selisih = $row['harga_sekarang'] - $row['harga_beli'];
                        $status_cuan = ($selisih >= 0) ? "cuan" : "";
                        
                        echo "<tr>";
                        echo "<td>" . $no++ . "</td>";
                        echo "<td>" . htmlspecialchars($row['nama_barang']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['kategori']) . "</td>";
                        echo "<td>
                                Rp " . number_format($row['harga_sekarang'], 0, ',', '.') . " 
                                (<span class='$status_cuan'>Rp " . number_format($selisih, 0, ',', '.') . "</span>)
                              </td>";
                        echo "<td style='text-align: center;'>
                                <a href='edit.php?id=" . $row['id'] . "' style='color: #3b82f6; text-decoration: none; font-weight: 600; margin-right: 10px;'>Edit</a>
                                <a href='hapus.php?id=" . $row['id'] . "' class='btn-danger action-hapus'>Hapus</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' style='text-align: center; color: var(--text-muted);'>Belum ada data barang untuk akun ini.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </main>

    <script src="assets/js/app.js"></script>
</body>
</html>
