<?php
require_once __DIR__ . '/../config/app.php';
if (current_user()) redirect('/index.php');
$pageTitle = 'Registrasi';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $nama = trim($_POST['nama'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $pass = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    if ($nama === '') $errors[] = 'Nama wajib diisi.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email tidak valid.';
    if (strlen($pass) < 8) $errors[] = 'Password minimal 8 karakter.';
    if ($pass !== $confirm) $errors[] = 'Konfirmasi password tidak sama.';
    $check = $pdo->prepare('SELECT id_user FROM users WHERE email=?');
    $check->execute([$email]);
    if ($check->fetch()) $errors[] = 'Email sudah digunakan.';
    if (!$errors) {
        $stmt = $pdo->prepare("INSERT INTO users (nama,email,password,role) VALUES (?,?,?,'siswa')");
        $stmt->execute([$nama,$email,password_hash($pass,PASSWORD_DEFAULT)]);
        flash('success','Registrasi berhasil. Silakan login.');
        redirect('/auth/login.php');
    }
}
include __DIR__ . '/../includes/header.php';
?>
<div class="row justify-content-center"><div class="col-lg-6"><div class="card p-4">
<h1 class="h3 fw-bold">Buat Akun Calon Siswa</h1>
<?php foreach($errors as $err): ?><div class="alert alert-danger"><?= e($err) ?></div><?php endforeach; ?>
<form method="post">
<input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
<div class="mb-3"><label class="form-label">Nama lengkap</label><input class="form-control" name="nama" required value="<?= e($_POST['nama'] ?? '') ?>"></div>
<div class="mb-3"><label class="form-label">Email</label><input class="form-control" type="email" name="email" required value="<?= e($_POST['email'] ?? '') ?>"></div>
<div class="mb-3"><label class="form-label">Password (minimal 8 karakter)</label><input class="form-control" type="password" name="password" required minlength="8"></div>
<div class="mb-3"><label class="form-label">Konfirmasi password</label><input class="form-control" type="password" name="confirm_password" required></div>
<button class="btn btn-primary w-100">Daftar</button>
</form><p class="mt-3 mb-0 text-center">Sudah punya akun? <a href="login.php">Login</a></p>
</div></div></div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
