<?php
require __DIR__.'/_init.php';
$pageTitle='Dashboard Admin';
$total=(int)$pdo->query('SELECT COUNT(*) FROM pendaftaran')->fetchColumn();
$menunggu=(int)$pdo->query("SELECT COUNT(*) FROM pendaftaran WHERE status='Menunggu'")->fetchColumn();
$diterima=(int)$pdo->query("SELECT COUNT(*) FROM pendaftaran WHERE status='Diterima'")->fetchColumn();
$ditolak=(int)$pdo->query("SELECT COUNT(*) FROM pendaftaran WHERE status='Ditolak'")->fetchColumn();
$by=$pdo->query('SELECT j.nama_jurusan,COUNT(p.id_pendaftaran) jumlah FROM jurusan j LEFT JOIN pendaftaran p ON p.id_jurusan=j.id_jurusan GROUP BY j.id_jurusan ORDER BY j.nama_jurusan')->fetchAll();
include __DIR__.'/../includes/header.php';
?>
<h1 class="h3 fw-bold">Dashboard Admin</h1><p class="text-secondary">Ringkasan penerimaan peserta didik baru.</p>
<div class="row g-3 mb-4"><?php foreach(['Total Pendaftar'=>$total,'Menunggu'=>$menunggu,'Diterima'=>$diterima,'Ditolak'=>$ditolak] as $label=>$num): ?><div class="col-6 col-lg-3"><div class="card card-stat p-3"><div class="text-secondary"><?= e($label) ?></div><div class="display-6 fw-bold"><?= $num ?></div></div></div><?php endforeach; ?></div>
<div class="card p-4"><h2 class="h5">Pendaftar per Jurusan</h2><div class="table-responsive"><table class="table"><thead><tr><th>Jurusan</th><th>Jumlah pendaftar</th></tr></thead><tbody><?php foreach($by as $b): ?><tr><td><?= e($b['nama_jurusan']) ?></td><td><?= (int)$b['jumlah'] ?></td></tr><?php endforeach; ?></tbody></table></div></div>
<div class="row g-2 mt-3"><?php foreach(['siswa.php'=>'Data Siswa','jurusan.php'=>'Kelola Jurusan','verifikasi.php'=>'Verifikasi','seleksi.php'=>'Seleksi','laporan.php'=>'Laporan','berkas-list.php'=>'Dokumen'] as $url=>$label): ?><div class="col-6 col-md"><a class="btn btn-outline-primary w-100" href="<?= e($url) ?>"><?= e($label) ?></a></div><?php endforeach; ?></div>
<?php include __DIR__.'/../includes/footer.php'; ?>
