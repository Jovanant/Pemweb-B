<?php
require __DIR__.'/_init.php';
$id=(int)($_GET['id']??0);
$s=$pdo->prepare('SELECT nama_file,nama_asli,mime_type FROM berkas WHERE id_berkas=?');$s->execute([$id]);$b=$s->fetch();
if(!$b){http_response_code(404);exit('Dokumen tidak ditemukan.');}
$path=__DIR__.'/../storage/berkas/'.$b['nama_file'];
if(!is_file($path)){http_response_code(404);exit('File tidak tersedia.');}
header('Content-Type: '.$b['mime_type']);header('Content-Disposition: attachment; filename="'.str_replace('"','',$b['nama_asli']).'"');header('Content-Length: '.filesize($path));readfile($path);exit;
