<?php 
session_start();
require_once 'config/koneksi.php';

if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

$error = "";
$sukses = "";

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = $_POST['password'];
    $konfirmasi = $_POST['konfirmasi_password'];

    // Cek apakah username sudah dipakai orang lain
    $cek_user = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    
    if (mysqli_num_rows($cek_user) > 0) {
        $error = "Username sudah terdaftar, silakan pilih username lain!";
    } elseif ($password !== $konfirmasi) {
        $error = "Konfirmasi password tidak cocok!";
    } else {
        // Enkripsi password dengan MD5 agar tidak terlihat di database
        $password_md5 = md5($password);
        $query = "INSERT INTO users (username, password) VALUES ('$username', '$password_md5')";
        
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Akun berhasil dibuat! Silakan login.'); window.location.href='login.php';</script>";
            exit;
        } else {
            $error = "Gagal mendaftarkan akun!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Aplikasi Investasi Barang</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <div style="text-align: right; padding: 20px;">
        <button id="theme-toggle" class="theme-switch-btn">🌙 Dark Mode</button>
    </div>

    <div class="login-container" style="max-width: 400px; margin: 20px auto; padding: 30px; border-radius: 8px;">
        <h2 style="margin-top: 0; text-align: center;">Daftar Akun Baru</h2>

        <?php if ($error !== "") : ?>
            <p class="error-msg" style="color: #ef4444; font-weight: bold; text-align: center;"><?= $error; ?></p>
        <?php endif; ?>

        <form action="" method="post">
            <div style="margin-bottom: 15px;">
                <label for="username" style="display: block; margin-bottom: 5px; font-weight: 600;">Username Baru</label>
                <input type="text" name="username" id="username" required autocomplete="off">
            </div>

            <div style="margin-bottom: 15px;">
                <label for="password" style="display: block; margin-bottom: 5px; font-weight: 600;">Password</label>
                <input type="password" name="password" id="password" required>
            </div>

            <div style="margin-bottom: 20px;">
                <label for="konfirmasi_password" style="display: block; margin-bottom: 5px; font-weight: 600;">Ulangi Password</label>
                <input type="password" name="konfirmasi_password" id="konfirmasi_password" required>
            </div>

            <button type="submit" name="register" style="width: 100%; padding: 12px; font-size: 16px;">Daftar Sekarang</button>
        </form>

        <p style="text-align: center; margin-top: 15px; font-size: 14px;">
            Sudah punya akun? <a href="login.php" style="color: #3b82f6; font-weight: bold; text-decoration: none;">Login di sini</a>
        </p>
    </div>

    <script src="assets/js/app.js"></script>
</body>
</html>
