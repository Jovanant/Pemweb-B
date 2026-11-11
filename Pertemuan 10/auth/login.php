<?php
require_once __DIR__ . '/../config/app.php';
if (current_user()) redirect('/index.php');
$pageTitle = 'Login';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare('SELECT id_user,nama,email,password,role FROM users WHERE email=?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password,$user['password'])) {
        session_regenerate_id(true);
        unset($user['password']);
        $_SESSION['user'] = $user;
        redirect($user['role'] === 'admin' ? '/admin/dashboard.php' : '/siswa/dashboard.php');
    }
    $error = 'Email atau password salah.';
}
include __DIR__ . '/../includes/header.php';
?>
<div class="row justify-content-center"><div class="col-lg-5"><div class="card p-4">
<h1 class="h3 fw-bold">Login</h1><?php if($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
<div class="mb-3"><label class="form-label">Email</label><input class="form-control" type="email" name="email" required></div>
<div class="mb-3"><label class="form-label">Password</label><input class="form-control" type="password" name="password" required></div>
<button class="btn btn-primary w-100">Masuk</button></form>
<p class="mt-3 mb-0 text-center">Belum punya akun? <a href="register.php">Registrasi</a></p>
<div class="alert alert-info mt-3 mb-0 small">Akun admin awal: admin@smknusantara.test / Admin123! (ganti setelah login).</div>
</div></div></div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
