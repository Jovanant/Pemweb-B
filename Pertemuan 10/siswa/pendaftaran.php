<?php
require __DIR__.'/_init.php';
$pageTitle='Pendaftaran PPDB';
if(!$profil){flash('warning','Lengkapi biodata terlebih dahulu.');redirect('/siswa/biodata.php');}
$existing=$pdo->prepare('SELECT * FROM pendaftaran WHERE id_siswa=? AND tahun_ajaran=?');
$year=date('Y').'/'.(date('Y')+1);$existing->execute([$profil['id_siswa'],$year]);$row=$existing->fetch();
$jurusan=$pdo->query('SELECT * FROM jurusan WHERE aktif=1 ORDER BY nama_jurusan')->fetchAll();
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf();
 if($row){$error='Kamu sudah memiliki pendaftaran untuk tahun ajaran ini.';}
 else{
  $id=(int)($_POST['id_jurusan']??0);$q=$pdo->prepare('SELECT id_jurusan FROM jurusan WHERE id_jurusan=? AND aktif=1');$q->execute([$id]);
  if(!$q->fetch())$error='Pilih jurusan yang tersedia.';
  else{
   $nomor='PPDB-'.date('Y').'-'.strtoupper(bin2hex(random_bytes(3)));
   $pdo->beginTransaction();
   try{$ins=$pdo->prepare("INSERT INTO pendaftaran (nomor_pendaftaran,id_siswa,id_jurusan,tahun_ajaran,status) VALUES (?,?,?,?, 'Menunggu')");$ins->execute([$nomor,$profil['id_siswa'],$id,$year]);$pid=(int)$pdo->lastInsertId();log_status($pdo,$pid,null,'Menunggu','Pendaftaran dikirim');$pdo->commit();flash('success','Pendaftaran berhasil dikirim. Nomor: '.$nomor);redirect('/siswa/status.php');}
   catch(Throwable $e){$pdo->rollBack();$error='Pendaftaran gagal diproses. Silakan coba lagi.';}
  }
 }
}
include __DIR__.'/../includes/header.php';
?>
<div class="card p-4"><h1 class="h3 fw-bold">Formulir Pendaftaran</h1>
<?php if($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<?php if($row): ?><div class="alert alert-info">Pendaftaran tahun <?= e($year) ?> sudah dibuat dengan nomor <strong><?= e($row['nomor_pendaftaran']) ?></strong>. Perubahan jurusan tidak tersedia setelah formulir dikirim.</div>
<?php else: ?><p class="text-secondary">Pilih satu program keahlian. Pastikan biodata sudah benar sebelum mengirim.</p>
<form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><div class="mb-3"><label class="form-label">Jurusan pilihan</label><select class="form-select" name="id_jurusan" required><option value="">Pilih jurusan</option><?php foreach($jurusan as $j): ?><option value="<?= (int)$j['id_jurusan'] ?>"><?= e($j['nama_jurusan']) ?> (kuota <?= (int)$j['kuota'] ?>)</option><?php endforeach; ?></select></div><button class="btn btn-primary" data-confirm="Kirim pendaftaran sekarang?">Kirim Pendaftaran</button></form><?php endif; ?>
</div>
<?php include __DIR__.'/../includes/footer.php'; ?>
