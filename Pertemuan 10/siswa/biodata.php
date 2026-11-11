<?php
require __DIR__.'/_init.php';
$pageTitle='Biodata Siswa';
$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf();
 $data=[
  trim($_POST['nisn']??''),trim($_POST['nama_lengkap']??''),$_POST['jenis_kelamin']??'',
  trim($_POST['tempat_lahir']??''),$_POST['tanggal_lahir']??'',trim($_POST['alamat']??''),
  trim($_POST['asal_sekolah']??''),trim($_POST['no_hp']??'')
 ];
 if(!preg_match('/^[0-9]{10}$/',$data[0]))$errors[]='NISN harus terdiri dari 10 digit.';
 if($data[1]==='')$errors[]='Nama lengkap wajib diisi.';
 if(!in_array($data[2],['L','P'],true))$errors[]='Jenis kelamin wajib dipilih.';
 if($data[4]==='')$errors[]='Tanggal lahir wajib diisi.';
 if($data[5]===''||$data[6]===''||$data[7]==='')$errors[]='Alamat, asal sekolah, dan nomor HP wajib diisi.';
 $q=$pdo->prepare('SELECT id_siswa FROM siswa WHERE nisn=? AND id_user<>?');$q->execute([$data[0],$idUser]);if($q->fetch())$errors[]='NISN sudah digunakan.';
 if(!$errors){
  if($profil){$sql='UPDATE siswa SET nisn=?,nama_lengkap=?,jenis_kelamin=?,tempat_lahir=?,tanggal_lahir=?,alamat=?,asal_sekolah=?,no_hp=? WHERE id_user=?';$pdo->prepare($sql)->execute([...$data,$idUser]);}
  else{$sql='INSERT INTO siswa (id_user,nisn,nama_lengkap,jenis_kelamin,tempat_lahir,tanggal_lahir,alamat,asal_sekolah,no_hp) VALUES (?,?,?,?,?,?,?,?,?)';$pdo->prepare($sql)->execute([$idUser,...$data]);}
  flash('success','Biodata berhasil disimpan.');redirect('/siswa/dashboard.php');
 }
}
$v=fn($key)=>e($_POST[$key]??($profil[$key]??''));
include __DIR__.'/../includes/header.php';
?>
<div class="card p-4"><h1 class="h3 fw-bold">Biodata Calon Siswa</h1>
<?php foreach($errors as $er): ?><div class="alert alert-danger"><?= e($er) ?></div><?php endforeach; ?>
<form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
<div class="row g-3">
<div class="col-md-6"><label class="form-label">NISN (10 digit)</label><input class="form-control" name="nisn" maxlength="10" required value="<?= $v('nisn') ?>"></div>
<div class="col-md-6"><label class="form-label">Nama lengkap</label><input class="form-control" name="nama_lengkap" required value="<?= $v('nama_lengkap') ?>"></div>
<div class="col-md-6"><label class="form-label">Jenis kelamin</label><select class="form-select" name="jenis_kelamin" required><option value="">Pilih</option><option value="L" <?= $v('jenis_kelamin')==='L'?'selected':'' ?>>Laki-laki</option><option value="P" <?= $v('jenis_kelamin')==='P'?'selected':'' ?>>Perempuan</option></select></div>
<div class="col-md-6"><label class="form-label">Tempat lahir</label><input class="form-control" name="tempat_lahir" value="<?= $v('tempat_lahir') ?>"></div>
<div class="col-md-6"><label class="form-label">Tanggal lahir</label><input class="form-control" type="date" name="tanggal_lahir" value="<?= $v('tanggal_lahir') ?>"></div>
<div class="col-md-6"><label class="form-label">Nomor HP</label><input class="form-control" name="no_hp" required value="<?= $v('no_hp') ?>"></div>
<div class="col-md-6"><label class="form-label">Asal sekolah</label><input class="form-control" name="asal_sekolah" required value="<?= $v('asal_sekolah') ?>"></div>
<div class="col-12"><label class="form-label">Alamat</label><textarea class="form-control" name="alamat" required rows="3"><?= $v('alamat') ?></textarea></div>
</div><button class="btn btn-primary mt-4">Simpan Biodata</button></form></div>
<?php include __DIR__.'/../includes/footer.php'; ?>
