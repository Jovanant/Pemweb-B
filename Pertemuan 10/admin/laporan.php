<?php
require __DIR__.'/_init.php';
$pageTitle='Laporan PPDB';
$filter=trim($_GET['status']??'');
$params=[];$where='';
if(in_array($filter,['Menunggu','Diverifikasi','Diterima','Ditolak'],true)){$where='WHERE p.status=?';$params[]=$filter;}
$sql="SELECT p.nomor_pendaftaran,p.tahun_ajaran,p.tanggal_daftar,p.status,s.nama_lengkap,s.nisn,s.jenis_kelamin,j.nama_jurusan FROM pendaftaran p JOIN siswa s ON s.id_siswa=p.id_siswa JOIN jurusan j ON j.id_jurusan=p.id_jurusan $where ORDER BY p.tanggal_daftar DESC";
$stmt=$pdo->prepare($sql);$stmt->execute($params);$rows=$stmt->fetchAll();
if(($_GET['export']??'')==='csv'){
 header('Content-Type: text/csv; charset=utf-8');header('Content-Disposition: attachment; filename="laporan-ppdb.csv"');
 $out=fopen('php://output','w');fputcsv($out,['Nomor Pendaftaran','Tahun Ajaran','Tanggal Daftar','Status','Nama','NISN','Jenis Kelamin','Jurusan']);
 foreach($rows as $r)fputcsv($out,$r);fclose($out);exit;
}
$summary=$pdo->query("SELECT status,COUNT(*) jumlah FROM pendaftaran GROUP BY status")->fetchAll();
include __DIR__.'/../includes/header.php';
?>
<div class="card p-4"><div class="d-flex flex-wrap justify-content-between align-items-center gap-2"><h1 class="h3 fw-bold mb-0">Laporan PPDB</h1><a class="btn btn-success" href="?<?= http_build_query(['status'=>$filter,'export'=>'csv']) ?>">Ekspor CSV</a></div>
<div class="row g-2 my-3"><?php foreach($summary as $s): ?><div class="col-6 col-md-3"><div class="bg-light rounded p-3"><div class="small text-secondary"><?= e($s['status']) ?></div><strong class="fs-4"><?= (int)$s['jumlah'] ?></strong></div></div><?php endforeach; ?></div>
<form method="get" class="row g-2 mb-3"><div class="col-md-4"><select class="form-select" name="status"><option value="">Semua status</option><?php foreach(['Menunggu','Diverifikasi','Diterima','Ditolak'] as $s): ?><option <?= $filter===$s?'selected':'' ?>><?= e($s) ?></option><?php endforeach; ?></select></div><div class="col-auto"><button class="btn btn-primary">Filter</button><a class="btn btn-light" href="laporan.php">Reset</a></div></form>
<div class="table-responsive"><table class="table"><thead><tr><th>Nomor</th><th>Nama</th><th>NISN</th><th>Jurusan</th><th>JK</th><th>Status</th><th>Tanggal</th></tr></thead><tbody><?php foreach($rows as $r): ?><tr><td><?= e($r['nomor_pendaftaran']) ?></td><td><?= e($r['nama_lengkap']) ?></td><td><?= e($r['nisn']) ?></td><td><?= e($r['nama_jurusan']) ?></td><td><?= e($r['jenis_kelamin']) ?></td><td><?= e($r['status']) ?></td><td><?= e($r['tanggal_daftar']) ?></td></tr><?php endforeach; ?><?php if(!$rows): ?><tr><td colspan="7">Tidak ada data.</td></tr><?php endif; ?></tbody></table></div></div>
<?php include __DIR__.'/../includes/footer.php'; ?>
