<?php
session_start();
if (!isset($_SESSION['login'])) { header("Location: login.php"); exit; }
require 'config/koneksi.php';

$id = intval($_GET['id']);
if (mysqli_query($conn, "DELETE FROM investasi_barang WHERE id = $id")) {
    header("Location: index.php");
}
?>