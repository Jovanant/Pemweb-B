<?php
require __DIR__ . '/_init.php';
$pageTitle='Dashboard Siswa';
$pendaftaran=null;
if($profil){$s=$pdo->prepare('SELECT p.*,j.nama_jurusan,j.kode_jurusan FROM pendaftaran p JOIN jurusan j ON j.id_jurusan=p.id_jurusan WHERE p.id_siswa=? ORDER BY p.id_pendaftaran DESC LIMIT 1');$s->execute([$profil['id_siswa']]);$pendaftaran=$s->fetch();}
include __DIR__.'/../includes/header.php';
?>
<h1 class="h3 fw-bold mb-1">Dashboard Siswa</h1><p class="text-secondary">Selamat datang, <?= e(current_user()['nama']) ?>.</p>
<div class="row g-3">
<div class="col-md-6"><div class="card p-4 h-100"><h2 class="h5">Biodata</h2><?php if($profil): ?><p><?= e($profil['nama_lengkap']) ?><br><small class="text-secondary">NISN: <?= e($profil['nisn']) ?></small></p><?php else: ?><p class="text-secondary">Biodata belum dilengkapi.</p><?php endif; ?><a class="btn btn-outline-primary mt-auto" href="biodata.php">Isi / Ubah Biodata</a></div></div>
<div class="col-md-6"><div class="card p-4 h-100"><h2 class="h5">Pendaftaran</h2><?php if($pendaftaran): ?><p><?= e($pendaftaran['nomor_pendaftaran']) ?><br><?= e($pendaftaran['nama_jurusan']) ?><br><span class="badge text-bg-primary"><?= e($pendaftaran['status']) ?></span></p><?php else: ?><p class="text-secondary">Belum ada pendaftaran.</p><?php endif; ?><a class="btn btn-primary mt-auto" href="pendaftaran.php">Kelola Pendaftaran</a></div></div>
<div class="col-md-6"><div class="card p-4 h-100"><h2 class="h5">Dokumen Persyaratan</h2><p class="text-secondary">Unggah KK, akta kelahiran, rapor, dan pas foto.</p><a class="btn btn-outline-primary mt-auto" href="upload_berkas.php">Upload Dokumen</a></div></div>
<div class="col-md-6"><div class="card p-4 h-100"><h2 class="h5">Status & Pengumuman</h2><p class="text-secondary">Pantau verifikasi dan hasil seleksi.</p><a class="btn btn-outline-primary mt-auto" href="status.php">Lihat Status</a></div></div>
</div>
<?php include __DIR__.'/../includes/footer.php'; ?>
