<?php
require __DIR__.'/_init.php';
$pageTitle='Upload Berkas';
if(!$profil){flash('warning','Lengkapi biodata terlebih dahulu.');redirect('/siswa/biodata.php');}
$q=$pdo->prepare('SELECT * FROM pendaftaran WHERE id_siswa=? ORDER BY id_pendaftaran DESC LIMIT 1');$q->execute([$profil['id_siswa']]);$p=$q->fetch();
if(!$p){flash('warning','Buat pendaftaran terlebih dahulu.');redirect('/siswa/pendaftaran.php');}
$allowed=['jpg'=>'image/jpeg','jpeg'=>'image/jpeg','png'=>'image/png','pdf'=>'application/pdf'];
$types=['KK'=>'Kartu Keluarga','Akta'=>'Akta Kelahiran','Rapor'=>'Rapor','Pas Foto'=>'Pas Foto'];
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf();
 $jenis=$_POST['jenis_berkas']??'';
 if(!isset($types[$jenis]))$error='Jenis dokumen tidak valid.';
 elseif(empty($_FILES['dokumen'])||$_FILES['dokumen']['error']!==UPLOAD_ERR_OK)$error='Pilih file yang berhasil diunggah.';
 else{
  $f=$_FILES['dokumen'];$ext=strtolower(pathinfo($f['name'],PATHINFO_EXTENSION));
  $mime=(new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
  if($f['size']>2*1024*1024)$error='Ukuran file maksimal 2 MB.';
  elseif(!isset($allowed[$ext])||$allowed[$ext]!==$mime)$error='Format file harus JPG, JPEG, PNG, atau PDF.';
  else{
   $dir=__DIR__.'/../storage/berkas';if(!is_dir($dir))mkdir($dir,0750,true);
   $safe=bin2hex(random_bytes(16)).'.'.$ext;$dest=$dir.'/'.$safe;
   if(move_uploaded_file($f['tmp_name'],$dest)){
    $stmt=$pdo->prepare('INSERT INTO berkas (id_pendaftaran,jenis_berkas,nama_file,nama_asli,mime_type,ukuran) VALUES (?,?,?,?,?,?)');
    $stmt->execute([$p['id_pendaftaran'],$jenis,$safe,basename($f['name']),$mime,$f['size']]);
    flash('success','Dokumen berhasil diunggah.');redirect('/siswa/upload_berkas.php');
   }else $error='Gagal menyimpan file.';
  }
 }
}
$list=$pdo->prepare('SELECT * FROM berkas WHERE id_pendaftaran=? ORDER BY tanggal_upload DESC');$list->execute([$p['id_pendaftaran']]);$berkas=$list->fetchAll();
include __DIR__.'/../includes/header.php';
?>
<div class="card p-4"><h1 class="h3 fw-bold">Upload Dokumen Persyaratan</h1><p class="text-secondary">Format JPG, JPEG, PNG, PDF. Maksimal 2 MB per file.</p>
<?php if($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<form method="post" enctype="multipart/form-data" class="row g-3"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
<div class="col-md-4"><label class="form-label">Jenis dokumen</label><select class="form-select" name="jenis_berkas" required><?php foreach($types as $k=>$v): ?><option value="<?= e($k) ?>"><?= e($v) ?></option><?php endforeach; ?></select></div>
<div class="col-md-6"><label class="form-label">Pilih file</label><input class="form-control" type="file" name="dokumen" accept=".jpg,.jpeg,.png,.pdf" required></div>
<div class="col-md-2 d-flex align-items-end"><button class="btn btn-primary w-100">Upload</button></div></form>
<hr><h2 class="h5">Dokumen yang sudah diunggah</h2>
<div class="table-responsive"><table class="table"><thead><tr><th>Jenis</th><th>Nama file</th><th>Tanggal</th></tr></thead><tbody><?php foreach($berkas as $b): ?><tr><td><?= e($types[$b['jenis_berkas']]??$b['jenis_berkas']) ?></td><td><?= e($b['nama_asli']) ?></td><td><?= e($b['tanggal_upload']) ?></td></tr><?php endforeach; ?><?php if(!$berkas): ?><tr><td colspan="3" class="text-secondary">Belum ada dokumen.</td></tr><?php endif; ?></tbody></table></div>
</div>
<?php include __DIR__.'/../includes/footer.php'; ?>
