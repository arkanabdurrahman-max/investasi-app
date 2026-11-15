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
        .nav-links { display: flex; gap: 20px; align-items: center; }
        .nav-links a { text-decoration: none; color: inherit; font-weight: 600; font-size: 14px; }
        .nav-links a:hover { color: #3b82f6; }
        .grid-pasar { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 20px; }
        .card-pasar { border: 1px solid #cbd5e1; border-radius: 8px; padding: 14px; display: flex; flex-direction: column; }
        .card-pasar img { width: 100%; height: 150px; object-fit: cover; border-radius: 6px; margin-bottom: 10px; }
    </style>
</head>
<body>

    <!-- Navbar Utama -->
    <header>
        <div style="display: flex; align-items: center; gap: 30px;">
            <h2 style="margin: 0;">Aplikasi Investasi Barang (IB)</h2>
            <nav class="nav-links">
                <a href="#aset-saya">Aset Saya</a>
                <a href="#pasar-tawar">Pasar Tawar</a>
                <a href="#tawaran-masuk">Tawaran Masuk</a>
            </nav>
        </div>

        <div style="display: flex; gap: 20px; align-items: center;">
            <span>Selamat Datang, <strong><?php echo htmlspecialchars($_SESSION['user']); ?></strong></span>
            <button id="theme-toggle" class="theme-switch-btn">🌙 Dark Mode</button>
            <a href="logout.php" style="color: #ef4444; font-weight: bold; text-decoration: none;">Keluar</a>
        </div>
    </header>

    <main class="main-dashboard">

        <!-- 1. DAFTAR ASET SAYA -->
        <section id="aset-saya" style="margin-bottom: 50px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                <h3>Daftar Aset Investasi Terdaftar</h3>
                <a href="tambah.php" class="btn" style="width: auto; padding: 10px 18px;">➕ Tambah Barang</a>
            </div>

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
                            
                            echo "<tr>";
                            echo "<td>" . $no++ . "</td>";
                            echo "<td>";
                            if ($foto_path !== "") {
                                echo "<img src='$foto_path' alt='Foto Aset' style='width: 60px; height: 60px; object-fit: cover; border-radius: 6px;'>";
                            } else {
                                echo "<span style='font-size: 12px; color: gray;'>No Image</span>";
                            }
                            echo "</td>";
                            echo "<td>" . htmlspecialchars($row['nama_barang']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['kategori']) . "</td>";
                            echo "<td>Rp " . number_format($row['harga_pokok'], 0, ',', '.') . "</td>";
                            echo "<td>
                                    Rp " . number_format($row['harga_sekarang'], 0, ',', '.') . " 
                                    (<span class='$status_cuan'>Rp " . number_format($selisih, 0, ',', '.') . "</span>)
                                  </td>";
                            echo "<td>" . nl2br(htmlspecialchars($row['keterangan'])) . "</td>";
                            echo "<td style='text-align: center;'>
                                    <a href='edit.php?id=" . $row['id'] . "' style='color: #3b82f6; text-decoration: none; font-weight: 600; margin-right: 10px;'>Edit</a>
                                    <a href='hapus.php?id=" . $row['id'] . "' class='btn-danger action-hapus'>Hapus</a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8' style='text-align: center; color: var(--text-muted);'>Belum ada data barang untuk akun ini.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>

        <!-- 2. PASAR TAWAR-MENAWAR (ASET USER LAIN) -->
        <section id="pasar-tawar" style="margin-bottom: 50px;">
            <h3 style="margin-bottom: 20px;">Pasar Aset (Tawar Barang User Lain)</h3>
            <div class="grid-pasar">
                <?php
                $q_pasar = mysqli_query($conn, "SELECT b.*, u.username AS pemilik FROM investasi_barang b JOIN users u ON b.user_id = u.id WHERE b.user_id != $user_id ORDER BY b.id DESC");
                if ($q_pasar && mysqli_num_rows($q_pasar) > 0) {
                    while ($item = mysqli_fetch_assoc($q_pasar)) {
                        $foto_p = (!empty($item['foto']) && file_exists("assets/img/" . $item['foto'])) ? "assets/img/" . htmlspecialchars($item['foto']) : "https://placeholder.com";
                        echo "<div class='card-pasar'>";
                        echo "<img src='$foto_p' alt='Foto Barang'>";
                        echo "<small style='color: #3b82f6; font-weight: bold;'>" . htmlspecialchars($item['kategori']) . " • Pemilik: " . htmlspecialchars($item['pemilik']) . "</small>";
                        echo "<h4 style='margin: 6px 0;'>" . htmlspecialchars($item['nama_barang']) . "</h4>";
                        echo "<p style='font-size: 13px; margin: 0 0 10px; flex: 1;'>" . nl2br(htmlspecialchars($item['keterangan'])) . "</p>";
                        echo "<strong style='display: block; margin-bottom: 10px;'>Harga: Rp " . number_format($item['harga_sekarang'], 0, ',', '.') . "</strong>";
                        echo "<form action='tawar.php' method='POST' style='display: flex; gap: 6px;'>
                                <input type='hidden' name='barang_id' value='" . $item['id'] . "'>
                                <input type='number' name='harga_tawar' placeholder='Nominal tawar' required style='width: 100%; padding: 6px;'>
                                <button type='submit' name='kirim_tawar' class='btn' style='width: auto; padding: 6px 12px;'>Tawar</button>
                              </form>";
                        echo "</div>";
                    }
                } else {
                    echo "<p style='color: var(--text-muted);'>Belum ada barang dari akun lain untuk ditawar.</p>";
                }
                ?>
            </div>
        </section>

        <!-- 3. TAWARAN MASUK -->
        <section id="tawaran-masuk">
            <h3 style="margin-bottom: 20px;">Tawaran Masuk untuk Aset Saya</h3>
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
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($tw['nama_barang']) . "</td>";
                            echo "<td>" . htmlspecialchars($tw['penawar']) . "</td>";
                            echo "<td>Rp " . number_format($tw['harga_sekarang'], 0, ',', '.') . "</td>";
                            echo "<td><strong>Rp " . number_format($tw['harga_tawar'], 0, ',', '.') . "</strong></td>";
                            echo "<td>" . htmlspecialchars($tw['status']) . "</td>";
                            echo "<td style='text-align: center;'>";
                            if ($tw['status'] === 'Pending') {
                            echo "<a href='tawar.php?aksi=terima&id=" . $tw['id'] . "' style='color: #10b981; font-weight: 600; text-decoration: none; margin-right: 10px;'>Terima";
                            echo "<a href='tawar.php?aksi=tolak&id=" . $tw['id'] . "' style='color: #ef4444; font-weight: 600; text-decoration: none;'>Tolak";
                            } else {
                            echo "Selesai";
                            }
                            echo "";
                            echo "";
                            }
                            } else {
                            echo "Tidak ada tawaran masuk.";
                            }
                            ?>
                        </tbody>
            </table>
        </section>

    </main>

    <!-- INI PEMANGGIL JS DARK MODE-NYA -->
    <script src="assets/js/app.js"></script>
</body>
</html>