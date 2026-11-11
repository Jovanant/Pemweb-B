<?php
require __DIR__.'/_init.php';
$pageTitle='Data Siswa';
$rows=$pdo->query('SELECT s.*,u.email FROM siswa s JOIN users u ON u.id_user=s.id_user ORDER BY s.nama_lengkap')->fetchAll();
include __DIR__.'/../includes/header.php';
?>
<div class="card p-4"><h1 class="h3 fw-bold">Data Calon Siswa</h1><div class="table-responsive"><table class="table table-hover"><thead><tr><th>Nama</th><th>NISN</th><th>Email</th><th>Asal Sekolah</th><th>HP</th></tr></thead><tbody><?php foreach($rows as $r): ?><tr><td><?= e($r['nama_lengkap']) ?></td><td><?= e($r['nisn']) ?></td><td><?= e($r['email']) ?></td><td><?= e($r['asal_sekolah']) ?></td><td><?= e($r['no_hp']) ?></td></tr><?php endforeach; ?><?php if(!$rows): ?><tr><td colspan="5">Belum ada data siswa.</td></tr><?php endif; ?></tbody></table></div></div>
<?php include __DIR__.'/../includes/footer.php'; ?>
