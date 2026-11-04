<?php
require 'koneksi.php';

$error = "";

// Ambil id dari URL (saat buka halaman) atau dari form (saat submit)
$id = isset($_POST['id']) ? (int)$_POST['id'] : (int)($_GET['id'] ?? 0);

// Proses update
if (isset($_POST['update'])) {
    $nama    = trim($_POST['nama']);
    $alamat  = trim($_POST['alamat']);
    $jk      = $_POST['jenis_kelamin'] ?? "";
    $agama   = $_POST['agama'] ?? "";
    $sekolah = trim($_POST['sekolah_asal']);

    if ($nama == "" || $alamat == "" || $jk == "" || $agama == "" || $sekolah == "") {
        $error = "Semua field wajib diisi!";
    } else {
        $stmt = mysqli_prepare($koneksi,
            "UPDATE siswa SET nama=?, alamat=?, jenis_kelamin=?, agama=?, sekolah_asal=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "sssssi", $nama, $alamat, $jk, $agama, $sekolah, $id);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: index.php?pesan=edit");
            exit;
        } else {
            $error = "Gagal mengubah data.";
        }
    }
}

// Ambil data siswa
$stmt = mysqli_prepare($koneksi, "SELECT * FROM siswa WHERE id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$data) {
    header("Location: index.php");
    exit;
}

// Kalau form gagal divalidasi, tampilkan isian terakhir user
if ($error != "") {
    $data['nama']          = $nama;
    $data['alamat']        = $alamat;
    $data['jenis_kelamin'] = $jk;
    $data['agama']         = $agama;
    $data['sekolah_asal']  = $sekolah;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Siswa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Edit Siswa</h1>

    <?php if ($error != "") { ?>
        <div class="error"><?= $error ?></div>
    <?php } ?>

    <form action="" method="post">
        <input type="hidden" name="id" value="<?= $data['id'] ?>">

        <label>Nama Lengkap</label>
        <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>">

        <label>Alamat</label>
        <textarea name="alamat" rows="3"><?= htmlspecialchars($data['alamat']) ?></textarea>

        <label>Jenis Kelamin</label>
        <select name="jenis_kelamin">
            <option value="">-- Pilih --</option>
            <option value="Laki-laki" <?= $data['jenis_kelamin'] == "Laki-laki" ? "selected" : "" ?>>Laki-laki</option>
            <option value="Perempuan" <?= $data['jenis_kelamin'] == "Perempuan" ? "selected" : "" ?>>Perempuan</option>
        </select>

        <label>Agama</label>
        <select name="agama">
            <option value="">-- Pilih --</option>
            <?php foreach (["Islam", "Kristen", "Katolik", "Hindu", "Buddha", "Konghucu"] as $a) { ?>
                <option value="<?= $a ?>" <?= $data['agama'] == $a ? "selected" : "" ?>><?= $a ?></option>
            <?php } ?>
        </select>

        <label>Sekolah Asal</label>
        <input type="text" name="sekolah_asal" value="<?= htmlspecialchars($data['sekolah_asal']) ?>">

        <br><br>
        <button type="submit" name="update">Update</button>
        <a href="index.php" class="btn abu">Kembali</a>
    </form>
</div>
</body>
</html>
