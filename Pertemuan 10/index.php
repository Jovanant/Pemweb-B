<?php
require_once __DIR__ . '/config/app.php';
$pageTitle = 'Beranda';
$jurusan = $pdo->query("SELECT * FROM jurusan WHERE aktif=1 ORDER BY nama_jurusan")->fetchAll();
include __DIR__ . '/includes/header.php';
?>
<section class="hero mb-5">
 <span class="badge bg-light text-primary mb-3">Penerimaan Peserta Didik Baru</span>
 <h1 class="display-5 fw-bold">Buka Langkah Menuju Masa Depanmu</h1>
 <p class="lead col-lg-8">Bergabunglah dengan SMK Nusantara. Pilih program keahlian, lengkapi data, dan pantau proses pendaftaran secara online.</p>
 <a class="btn btn-light btn-lg mt-2" href="<?= BASE_URL ?>/auth/register.php">Mulai Pendaftaran</a>
</section>
<section id="jurusan" class="mb-5">
 <div class="d-flex justify-content-between align-items-center mb-3"><h2 class="h3 fw-bold">Program Keahlian</h2><span class="text-secondary">Tahun Ajaran <?= date('Y') ?>/<?= date('Y')+1 ?></span></div>
 <div class="row g-3">
 <?php foreach ($jurusan as $j): ?>
 <div class="col-md-6 col-lg-4"><div class="card h-100 p-4"><span class="text-primary fw-bold"><?= e($j['kode_jurusan']) ?></span><h3 class="h5 mt-2"><?= e($j['nama_jurusan']) ?></h3><p class="text-secondary mb-2"><?= e($j['deskripsi']) ?></p><small>Kuota: <?= (int)$j['kuota'] ?> siswa</small></div></div>
 <?php endforeach; ?>
 </div>
</section>
<section id="persyaratan" class="card p-4">
 <h2 class="h4 fw-bold">Persyaratan Pendaftaran</h2>
 <p class="text-secondary">Siapkan Kartu Keluarga, akta kelahiran, rapor, dan pas foto dalam format JPG, JPEG, PNG, atau PDF (maksimal 2 MB per dokumen).</p>
 <p class="mb-0">Alur: buat akun → lengkapi biodata → pilih jurusan → unggah dokumen → kirim pendaftaran → pantau status.</p>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
