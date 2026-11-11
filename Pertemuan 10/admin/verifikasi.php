<?php
require __DIR__.'/_init.php';
$pageTitle='Verifikasi Pendaftaran';
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf();$id=(int)($_POST['id_pendaftaran']??0);$status=$_POST['status']??'';$note=trim($_POST['catatan']??'');
 if(!in_array($status,['Diverifikasi','Menunggu','Ditolak'],true)){flash('danger','Status tidak valid.');redirect('/admin/verifikasi.php');}
 $pdo->beginTransaction();
 try{$s=$pdo->prepare('SELECT status FROM pendaftaran WHERE id_pendaftaran=? FOR UPDATE');$s->execute([$id]);$old=$s->fetchColumn();if($old===false)throw new RuntimeException();$pdo->prepare('UPDATE pendaftaran SET status=? WHERE id_pendaftaran=?')->execute([$status,$id]);log_status($pdo,$id,$old,$status,$note);$pdo->commit();flash('success','Status pendaftaran diperbarui.');}
 catch(Throwable $e){$pdo->rollBack();flash('danger','Gagal memperbarui status.');}
 redirect('/admin/verifikasi.php');
}
$rows=$pdo->query("SELECT p.*,s.nama_lengkap,s.nisn,j.nama_jurusan FROM pendaftaran p JOIN siswa s ON s.id_siswa=p.id_siswa JOIN jurusan j ON j.id_jurusan=p.id_jurusan ORDER BY p.tanggal_daftar DESC")->fetchAll();
include __DIR__.'/../includes/header.php';
?>
<div class="card p-4"><h1 class="h3 fw-bold">Verifikasi Pendaftaran</h1><div class="table-responsive"><table class="table"><thead><tr><th>Nomor / Siswa</th><th>Jurusan</th><th>Status</th><th>Ubah status</th></tr></thead><tbody>
<?php foreach($rows as $r): ?><tr><td><strong><?= e($r['nomor_pendaftaran']) ?></strong><br><?= e($r['nama_lengkap']) ?><div class="small text-secondary">NISN <?= e($r['nisn']) ?></div></td><td><?= e($r['nama_jurusan']) ?></td><td><?= e($r['status']) ?></td><td><form method="post" class="d-flex flex-column gap-2"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id_pendaftaran" value="<?= (int)$r['id_pendaftaran'] ?>"><select class="form-select form-select-sm" name="status"><option>Menunggu</option><option <?= $r['status']==='Diverifikasi'?'selected':'' ?>>Diverifikasi</option><option <?= $r['status']==='Ditolak'?'selected':'' ?>>Ditolak</option></select><input class="form-control form-control-sm" name="catatan" placeholder="Catatan (opsional)"><button class="btn btn-sm btn-primary">Simpan</button></form></td></tr><?php endforeach; ?>
<?php if(!$rows): ?><tr><td colspan="4">Belum ada pendaftaran.</td></tr><?php endif; ?></tbody></table></div></div>
<?php include __DIR__.'/../includes/footer.php'; ?>
