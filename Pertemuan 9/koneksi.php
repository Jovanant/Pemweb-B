<?php
// Pengaturan koneksi database (default XAMPP)
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_psb";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
