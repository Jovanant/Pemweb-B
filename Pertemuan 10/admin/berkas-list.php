<?php
require __DIR__.'/_init.php';
$pageTitle='Dokumen Pendaftar';
$rows=$pdo->query('SELECT b.*,p.nomor_pendaftaran,s.nama_lengkap FROM berkas b JOIN pendaftaran p ON p.id_pendaftaran=b.id_pendaftaran JOIN siswa s ON s.id_siswa=p.id_siswa ORDER BY b.tanggal_upload DESC')->fetchAll();
include __DIR__.'/../includes/header.php';
?>
<div class="card p-4"><h1 class="h3 fw-bold">Dokumen Pendaftar</h1><div class="table-responsive"><table class="table"><thead><tr><th>Pendaftar</th><th>Jenis</th><th>Nama file</th><th>Aksi</th></tr></thead><tbody><?php foreach($rows as $r): ?><tr><td><?= e($r['nama_lengkap']) ?><div class="small text-secondary"><?= e($r['nomor_pendaftaran']) ?></div></td><td><?= e($r['jenis_berkas']) ?></td><td><?= e($r['nama_asli']) ?></td><td><a class="btn btn-sm btn-outline-primary" href="berkas.php?id=<?= (int)$r['id_berkas'] ?>">Unduh</a></td></tr><?php endforeach; ?><?php if(!$rows): ?><tr><td colspan="4">Belum ada dokumen.</td></tr><?php endif; ?></tbody></table></div></div>
<?php include __DIR__.'/../includes/footer.php'; ?>
