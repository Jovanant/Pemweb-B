<?php
require 'koneksi.php';

// Hapus hanya lewat tombol (method POST) dari index.php
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['id'])) {
    $id = (int)$_POST['id'];

    $stmt = mysqli_prepare($koneksi, "DELETE FROM siswa WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    header("Location: index.php?pesan=hapus");
    exit;
}

header("Location: index.php");
exit;
