<?php
require 'koneksi.php';

$result = mysqli_query($koneksi, "SELECT * FROM siswa ORDER BY id DESC");

$pesan = "";
if (isset($_GET['pesan'])) {
    if ($_GET['pesan'] == "tambah") $pesan = "Data berhasil ditambahkan.";
    if ($_GET['pesan'] == "edit")   $pesan = "Data berhasil diubah.";
    if ($_GET['pesan'] == "hapus")  $pesan = "Data berhasil dihapus.";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pendaftaran Siswa Baru</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Data Pendaftaran Siswa Baru</h1>

    <?php if ($pesan != "") { ?>
        <div class="pesan"><?= $pesan ?></div>
    <?php } ?>

    <a href="tambah.php" class="btn hijau">+ Tambah Siswa</a>

    <table>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Alamat</th>
            <th>Jenis Kelamin</th>
            <th>Agama</th>
            <th>Sekolah Asal</th>
            <th>Aksi</th>
        </tr>
        <?php
        $no = 1;
        while ($row = mysqli_fetch_assoc($result)) {
        ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['nama']) ?></td>
            <td><?= htmlspecialchars($row['alamat']) ?></td>
            <td><?= htmlspecialchars($row['jenis_kelamin']) ?></td>
            <td><?= htmlspecialchars($row['agama']) ?></td>
            <td><?= htmlspecialchars($row['sekolah_asal']) ?></td>
            <td>
                <a href="edit.php?id=<?= $row['id'] ?>" class="btn">Edit</a>
                <form class="inline" action="hapus.php" method="post"
                      onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="submit" class="merah">Hapus</button>
                </form>
            </td>
        </tr>
        <?php } ?>

        <?php if (mysqli_num_rows($result) == 0) { ?>
        <tr><td colspan="7">Belum ada data.</td></tr>
        <?php } ?>
    </table>
</div>
</body>
</html>
