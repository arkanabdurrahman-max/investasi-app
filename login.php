<?php
session_start();
require 'config/koneksi.php';

if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

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
    <h1 class="text-center" style="margin-bottom: 5px; color: #2ecc71; font-size: 28px; font-weight: bold;">IB</h1>
    <h3 class="text-center" style="color: aliceblue; font-size: 14px; margin-bottom: 20px; font-weight: normal;">Aplikasi Investasi Barang</h3>
    <h2 class="text-center" style="margin-bottom: 5px; color: aliceblue; font-size: 18px; margin-top: 15px;">Selamat Datang</h2>
    <p class="text-center" style="color: aliceblue; font-size: 13px; margin-bottom: 25px;">Silahkan masuk untuk melanjutkan</p>
        
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
