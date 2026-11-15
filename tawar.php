<?php
session_start();
if (!isset($_SESSION['login'])) { header("Location: login.php"); exit; }
require 'config/koneksi.php';

$user_id = intval($_SESSION['user_id']);

// 1. Kirim Tawaran Baru
if (isset($_POST['kirim_tawar'])) {
    $barang_id = intval($_POST['barang_id']);
    $harga_tawar = intval($_POST['harga_tawar']);

    $query = "INSERT INTO penawaran (barang_id, penawar_id, harga_tawar, status) 
              VALUES ($barang_id, $user_id, $harga_tawar, 'Pending')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Tawaran harga berhasil dikirim!'); window.location.href='index.php#pasar-tawar';</script>";
        exit;
    }
}

// 2. Terima atau Tolak Tawaran
if (isset($_GET['aksi']) && isset($_GET['id'])) {
    $tawar_id = intval($_GET['id']);
    $aksi = ($_GET['aksi'] === 'terima') ? 'Diterima' : 'Ditolak';

    $cek = mysqli_query($conn, "SELECT p.id FROM penawaran p 
                                JOIN investasi_barang b ON p.barang_id = b.id 
                                WHERE p.id = $tawar_id AND b.user_id = $user_id");
    
    if (mysqli_num_rows($cek) > 0) {
        mysqli_query($conn, "UPDATE penawaran SET status = '$aksi' WHERE id = $tawar_id");
        echo "<script>alert('Status penawaran diubah menjadi $aksi!'); window.location.href='index.php#tawaran-masuk';</script>";
        exit;
    }
}
?>
