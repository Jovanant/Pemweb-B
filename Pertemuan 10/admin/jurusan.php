<?php
require __DIR__.'/_init.php';
$pageTitle='Kelola Jurusan';
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf();$action=$_POST['action']??'';
 if($action==='save'){
  $id=(int)($_POST['id_jurusan']??0);$kode=strtoupper(trim($_POST['kode_jurusan']??''));$nama=trim($_POST['nama_jurusan']??'');$kuota=max(0,(int)($_POST['kuota']??0));$desc=trim($_POST['deskripsi']??'');
  if($kode===''||$nama==='')$error='Kode dan nama jurusan wajib diisi.';
  else{if($id){$s=$pdo->prepare('UPDATE jurusan SET kode_jurusan=?,nama_jurusan=?,kuota=?,deskripsi=? WHERE id_jurusan=?');$s->execute([$kode,$nama,$kuota,$desc,$id]);}else{$s=$pdo->prepare('INSERT INTO jurusan (kode_jurusan,nama_jurusan,kuota,deskripsi) VALUES (?,?,?,?)');$s->execute([$kode,$nama,$kuota,$desc]);}flash('success','Data jurusan disimpan.');redirect('/admin/jurusan.php');}
 }elseif($action==='toggle'){$id=(int)$_POST['id_jurusan'];$pdo->prepare('UPDATE jurusan SET aktif=1-aktif WHERE id_jurusan=?')->execute([$id]);flash('success','Status jurusan diperbarui.');redirect('/admin/jurusan.php');}
}
$edit=null;if(isset($_GET['edit'])){$s=$pdo->prepare('SELECT * FROM jurusan WHERE id_jurusan=?');$s->execute([(int)$_GET['edit']]);$edit=$s->fetch();}
$rows=$pdo->query('SELECT * FROM jurusan ORDER BY nama_jurusan')->fetchAll();
include __DIR__.'/../includes/header.php';
?>
<div class="row g-3"><div class="col-lg-5"><div class="card p-4"><h1 class="h4 fw-bold"><?= $edit?'Ubah':'Tambah' ?> Jurusan</h1><?php if($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="save"><input type="hidden" name="id_jurusan" value="<?= (int)($edit['id_jurusan']??0) ?>">
<div class="mb-3"><label class="form-label">Kode</label><input class="form-control" name="kode_jurusan" required value="<?= e($edit['kode_jurusan']??'') ?>"></div>
<div class="mb-3"><label class="form-label">Nama jurusan</label><input class="form-control" name="nama_jurusan" required value="<?= e($edit['nama_jurusan']??'') ?>"></div>
<div class="mb-3"><label class="form-label">Kuota</label><input class="form-control" type="number" min="0" name="kuota" value="<?= (int)($edit['kuota']??0) ?>"></div>
<div class="mb-3"><label class="form-label">Deskripsi</label><textarea class="form-control" name="deskripsi"><?= e($edit['deskripsi']??'') ?></textarea></div>
<button class="btn btn-primary">Simpan</button> <a class="btn btn-light" href="jurusan.php">Reset</a></form></div></div>
<div class="col-lg-7"><div class="card p-4"><h2 class="h4">Daftar Jurusan</h2><div class="table-responsive"><table class="table"><thead><tr><th>Jurusan</th><th>Kuota</th><th>Aktif</th><th>Aksi</th></tr></thead><tbody><?php foreach($rows as $r): ?><tr><td><?= e($r['nama_jurusan']) ?><div class="small text-secondary"><?= e($r['kode_jurusan']) ?></div></td><td><?= (int)$r['kuota'] ?></td><td><?= $r['aktif']?'Ya':'Tidak' ?></td><td class="text-nowrap"><a class="btn btn-sm btn-outline-primary" href="?edit=<?= (int)$r['id_jurusan'] ?>">Edit</a> <form method="post" class="d-inline"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="toggle"><input type="hidden" name="id_jurusan" value="<?= (int)$r['id_jurusan'] ?>"><button class="btn btn-sm btn-outline-secondary">Aktif/nonaktif</button></form></td></tr><?php endforeach; ?></tbody></table></div></div></div></div>
<?php include __DIR__.'/../includes/footer.php'; ?>
