<?php
require_once __DIR__ . '/../config/app.php';
$user = current_user();
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($pageTitle ?? APP_NAME) ?> - SMK Nusantara</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>/assets/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
 <div class="container">
  <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>/index.php">SMK Nusantara</a>
  <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#mainNav"><span class="navbar-toggler-icon"></span></button>
  <div class="collapse navbar-collapse" id="mainNav">
   <ul class="navbar-nav ms-auto align-items-lg-center">
    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/index.php#jurusan">Jurusan</a></li>
    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/index.php#persyaratan">Persyaratan</a></li>
    <?php if ($user): ?>
      <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/<?= $user['role'] === 'admin' ? 'admin/dashboard.php' : 'siswa/dashboard.php' ?>">Dashboard</a></li>
      <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/auth/logout.php">Keluar</a></li>
    <?php else: ?>
      <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/auth/login.php">Login</a></li>
      <li class="nav-item ms-lg-2"><a class="btn btn-light btn-sm" href="<?= BASE_URL ?>/auth/register.php">Daftar Sekarang</a></li>
    <?php endif; ?>
   </ul>
  </div>
 </div>
</nav>
<main class="container py-4">
<?php show_flash(); ?>
