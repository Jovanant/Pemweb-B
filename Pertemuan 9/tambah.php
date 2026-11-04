<?php
require 'koneksi.php';

$error = "";
$nama = $alamat = $jk = $agama = $sekolah = "";

if (isset($_POST['simpan'])) {
    $nama    = trim($_POST['nama']);
    $alamat  = trim($_POST['alamat']);
    $jk      = $_POST['jenis_kelamin'] ?? "";
    $agama   = $_POST['agama'] ?? "";
    $sekolah = trim($_POST['sekolah_asal']);

    if ($nama == "" || $alamat == "" || $jk == "" || $agama == "" || $sekolah == "") {
        $error = "Semua field wajib diisi!";
    } else {
        $stmt = mysqli_prepare($koneksi,
            "INSERT INTO siswa (nama, alamat, jenis_kelamin, agama, sekolah_asal) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssss", $nama, $alamat, $jk, $agama, $sekolah);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: index.php?pesan=tambah");
            exit;
        } else {
            $error = "Gagal menyimpan data.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Siswa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Tambah Siswa</h1>

    <?php if ($error != "") { ?>
        <div class="error"><?= $error ?></div>
    <?php } ?>

    <form action="" method="post">
        <label>Nama Lengkap</label>
        <input type="text" name="nama" value="<?= htmlspecialchars($nama) ?>">

        <label>Alamat</label>
        <textarea name="alamat" rows="3"><?= htmlspecialchars($alamat) ?></textarea>

        <label>Jenis Kelamin</label>
        <select name="jenis_kelamin">
            <option value="">-- Pilih --</option>
            <option value="Laki-laki" <?= $jk == "Laki-laki" ? "selected" : "" ?>>Laki-laki</option>
            <option value="Perempuan" <?= $jk == "Perempuan" ? "selected" : "" ?>>Perempuan</option>
        </select>

        <label>Agama</label>
        <select name="agama">
            <option value="">-- Pilih --</option>
            <?php foreach (["Islam", "Kristen", "Katolik", "Hindu", "Buddha", "Konghucu"] as $a) { ?>
                <option value="<?= $a ?>" <?= $agama == $a ? "selected" : "" ?>><?= $a ?></option>
            <?php } ?>
        </select>

        <label>Sekolah Asal</label>
        <input type="text" name="sekolah_asal" value="<?= htmlspecialchars($sekolah) ?>">

        <br><br>
        <button type="submit" name="simpan">Simpan</button>
        <a href="index.php" class="btn abu">Kembali</a>
    </form>
</div>
</body>
</html>
