<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/database.php';

define('APP_NAME', 'PPDB Online SMK Nusantara');
define('BASE_URL', '/ppdb-smk');

function e(?string $value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
function redirect(string $path): never {
    header('Location: ' . BASE_URL . $path);
    exit;
}
function flash(string $type, string $message): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}
function show_flash(): void {
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        echo '<div class="alert alert-' . e($f['type']) . ' alert-dismissible fade show" role="alert">'
            . e($f['message']) . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        unset($_SESSION['flash']);
    }
}
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
function verify_csrf(): void {
    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) || !hash_equals(csrf_token(), $token)) {
        http_response_code(419);
        exit('Permintaan tidak valid. Silakan kembali dan coba lagi.');
    }
}
function current_user(): ?array {
    return $_SESSION['user'] ?? null;
}
function require_login(): void {
    if (!current_user()) {
        flash('warning', 'Silakan login terlebih dahulu.');
        redirect('/auth/login.php');
    }
}
function require_role(string $role): void {
    require_login();
    if (current_user()['role'] !== $role) {
        http_response_code(403);
        exit('Akses ditolak.');
    }
}
function log_status(PDO $pdo, int $idPendaftaran, ?string $old, string $new, ?string $note = null): void {
    $stmt = $pdo->prepare('INSERT INTO riwayat_status (id_pendaftaran, status_sebelumnya, status_baru, catatan, id_admin) VALUES (?, ?, ?, ?, ?)');
    $adminId = (current_user() && current_user()['role'] === 'admin') ? current_user()['id_user'] : null;
    $stmt->execute([$idPendaftaran, $old, $new, $note, $adminId]);
}
