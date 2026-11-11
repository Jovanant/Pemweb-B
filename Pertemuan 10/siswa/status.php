<?php
require __DIR__.'/_init.php';
$pageTitle='Status Pendaftaran';
if(!$profil){flash('warning','Lengkapi biodata terlebih dahulu.');redirect('/siswa/biodata.php');}
$q=$pdo->prepare('SELECT p.*,j.nama_jurusan FROM pendaftaran p JOIN jurusan j ON j.id_jurusan=p.id_jurusan WHERE p.id_siswa=? ORDER BY p.id_pendaftaran DESC LIMIT 1');$q->execute([$profil['id_siswa']]);$p=$q->fetch();
$history=[];if($p){$h=$pdo->prepare('SELECT * FROM riwayat_status WHERE id_pendaftaran=? ORDER BY tanggal_perubahan DESC');$h->execute([$p['id_pendaftaran']]);$history=$h->fetchAll();}
include __DIR__.'/../includes/header.php';
?>
<div class="card p-4"><h1 class="h3 fw-bold">Status Pendaftaran & Pengumuman</h1>
<?php if(!$p): ?><p class="text-secondary">Belum ada pendaftaran. <a href="pendaftaran.php">Daftar sekarang</a>.</p>
<?php else: ?><div class="row g-3 mb-3"><div class="col-md-6"><small class="text-secondary">Nomor pendaftaran</small><div class="fw-bold"><?= e($p['nomor_pendaftaran']) ?></div></div><div class="col-md-6"><small class="text-secondary">Jurusan</small><div class="fw-bold"><?= e($p['nama_jurusan']) ?></div></div><div class="col-md-6"><small class="text-secondary">Status</small><div><span class="badge text-bg-primary badge-status"><?= e($p['status']) ?></span></div></div><div class="col-md-6"><small class="text-secondary">Tanggal daftar</small><div><?= e($p['tanggal_daftar']) ?></div></div></div>
<?php if($p['status']==='Diterima'||$p['status']==='Ditolak'): ?><div class="alert <?= $p['status']==='Diterima'?'alert-success':'alert-danger' ?>"><strong>Hasil seleksi: <?= e($p['status']) ?></strong><br>Pengumuman tanggal <?= e($p['tanggal_pengumuman']??'-') ?></div><?php endif; ?>
<h2 class="h5 mt-3">Riwayat Status</h2><ul class="list-group"><?php foreach($history as $h): ?><li class="list-group-item"><strong><?= e($h['status_baru']) ?></strong> <span class="text-secondary small">— <?= e($h['tanggal_perubahan']) ?></span><?php if($h['catatan']): ?><div class="small text-secondary"><?= e($h['catatan']) ?></div><?php endif; ?></li><?php endforeach; ?></ul>
<?php endif; ?></div>
<?php include __DIR__.'/../includes/footer.php'; ?>
