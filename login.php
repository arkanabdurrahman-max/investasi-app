<?php
session_start();
require 'config/koneksi.php';

// Jika user SUDAH login sebelumnya, langsung dialihkan ke dashboard utama
if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // CARA BYPASS INSTAN & AMAN:
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
        <!-- Judul aplikasi utama kelompokmu -->
        <h1 class="text-center" style="margin-bottom: 5px; color: #2ecc71; font-size: 28px; font-weight: bold;">IB</h1>
        <p class="text-center" style="color: #9ca3af; font-size: 14px; margin-bottom: 25px;">Aplikasi Investasi Barang</p>
        
        <!-- Notifikasi kalau ketikan akun keliru -->
        <?php if (isset($error)) : ?>
            <p style="color: #ef4444; text-align: center; margin-bottom: 15px; font-weight: bold;">
                Username atau Password Salah!
            </p>
        <?php endif; ?>
        
        <form action="" method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Masukkan username" required autocomplete="off">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Masukkan password" required>
            </div>
            <button type="submit" name="login" class="btn">Masuk</button>
        </form>
    </div>
</body>
</html>
