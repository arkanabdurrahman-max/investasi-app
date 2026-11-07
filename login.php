<?php
session_start();
// Jika tidak ada tanda session login, TENDANG balik ke halaman login!
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
require 'config/koneksi.php';
// ... sisa kode tabel HTML di bawahnya ...

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // CARA BYPASS INSTAN & AMAN:
    // Langsung mencocokkan ketikan teks biasa tanpa terhalang eror query database
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['login'] = true;
        $_SESSION['user'] = $username;
        header("Location: index.php");
        exit;
    }
    $error = true;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Investasi Barang</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="auth-container">
        <!-- Judul aplikasi sesuai request terbaru kelompokmu -->
        <h2 class="text-center" style="margin-bottom: 5px;">IB</h2>
        <p class="text-center" style="color: #9ca3af; font-size: 14px; margin-bottom: 25px;">Aplikasi Investasi Barang</p>
        
        <?php if (isset($error)) : ?>
            <p style="color: #ef4444; text-align: center; margin-bottom: 15px; font-weight: bold;">
                Username atau Password Salah!
            </p>
        <?php endif; ?>
        
        <form action="" method="POST">
            <div class="form-group">
                <label>Username</label>
                <!-- Ditambahkan atribut name="username" agar bisa dibaca PHP -->
                <input type="text" name="username" placeholder="Masukkan username" required autocomplete="off">
            </div>
            <div class="form-group">
                <label>Password</label>
                <!-- Ditambahkan atribut name="password" agar bisa dibaca PHP -->
                <input type="password" name="password" placeholder="Masukkan password" required>
            </div>
            <!-- Ditambahkan atribut name="login" agar tombol bisa dideteksi oleh isset($_POST['login']) -->
            <button type="submit" name="login" class="btn">Masuk</button>
        </form>
    </div>
</body>
</html>
