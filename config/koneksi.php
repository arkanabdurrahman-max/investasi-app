<?php
$host = "localhost"; // Nama host server XAMPP kamu
$user = "root";      // Username bawaan XAMPP MySQL
$pass = "";          // Password bawaan XAMPP MySQL (kosongkan)
$db   = "db_investasi"; // Nama database yang kamu klik GO tadi di phpMyAdmin

// Perintah untuk mengaktifkan koneksi ke MySQL
$conn = mysqli_connect($host, $user, $pass, $db);

// Cek jika koneksi gagal, sistem akan langsung memunculkan pesan error
if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>