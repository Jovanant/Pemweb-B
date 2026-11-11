<?php
require __DIR__.'/_init.php';
$pageTitle='Seleksi PPDB';
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf();$id=(int)($_POST['id_pendaftaran']??0);$status=$_POST['hasil']??'';
 if(!in_array($status,['Diterima','Ditolak'],true))$error='Hasil seleksi tidak valid.';
 else{
  $pdo->beginTransaction();
  try{
   $s=$pdo->prepare('SELECT p.status,p.id_jurusan,j.kuota,(SELECT COUNT(*) FROM pendaftaran x WHERE x.id_jurusan=p.id_jurusan AND x.status="Diterima" AND x.id_pendaftaran<>p.id_pendaftaran) AS jumlah FROM pendaftaran p JOIN jurusan j ON j.id_jurusan=p.id_jurusan WHERE p.id_pendaftaran=? FOR UPDATE');
   $s->execute([$id]);$r=$s->fetch();if(!$r)throw new RuntimeException();
   if($status==='Diterima' && (int)$r['jumlah'] >= (int)$r['kuota'])throw new DomainException('Kuota jurusan sudah penuh.');
   $pdo->prepare('UPDATE pendaftaran SET status=?,tanggal_pengumuman=NOW() WHERE id_pendaftaran=?')->execute([$status,$id]);
   log_status($pdo,$id,$r['status'],$status,'Hasil seleksi diumumkan');$pdo->commit();flash('success','Hasil seleksi berhasil disimpan.');redirect('/admin/seleksi.php');
  }catch(DomainException $e){$pdo->rollBack();$error=$e->getMessage();}catch(Throwable $e){$pdo->rollBack();$error='Gagal menyimpan hasil seleksi.';}
 }
}
$rows=$pdo->query("SELECT p.*,s.nama_lengkap,j.nama_jurusan FROM pendaftaran p JOIN siswa s ON s.id_siswa=p.id_siswa JOIN jurusan j ON j.id_jurusan=p.id_jurusan WHERE p.status IN ('Diverifikasi','Diterima','Ditolak') ORDER BY p.tanggal_daftar")->fetchAll();
include __DIR__.'/../includes/header.php';
?>
<div class="card p-4"><h1 class="h3 fw-bold">Pengelolaan Hasil Seleksi</h1><p class="text-secondary">Hanya pendaftar yang sudah diverifikasi yang dapat diproses seleksinya.</p><?php if($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<div class="table-responsive"><table class="table"><thead><tr><th>Nomor</th><th>Nama</th><th>Jurusan</th><th>Status sekarang</th><th>Hasil seleksi</th></tr></thead><tbody>
<?php foreach($rows as $r): ?><tr><td><?= e($r['nomor_pendaftaran']) ?></td><td><?= e($r['nama_lengkap']) ?></td><td><?= e($r['nama_jurusan']) ?></td><td><?= e($r['status']) ?></td><td><form method="post" class="d-flex gap-2"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id_pendaftaran" value="<?= (int)$r['id_pendaftaran'] ?>"><select class="form-select form-select-sm" name="hasil"><option value="Diterima">Diterima</option><option value="Ditolak">Ditolak</option></select><button class="btn btn-sm btn-primary">Umumkan</button></form></td></tr><?php endforeach; ?>
<?php if(!$rows): ?><tr><td colspan="5">Belum ada pendaftar yang diverifikasi.</td></tr><?php endif; ?></tbody></table></div></div>
<?php include __DIR__.'/../includes/footer.php'; ?>
