<?php
session_start();
require 'config/koneksi.php';

// Jika sudah login, langsung lempar ke dashboard
if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        // Validasi password hash aman
        if (password_verify($password, $row['password'])) {
            $_SESSION['login'] = true;
            $_SESSION['user'] = $row['username'];
            header("Location: index.php");
            exit;
        }
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
        <h2 class="text-center">Sign In</h2>
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