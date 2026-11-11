<?php
require_once __DIR__ . '/../config/app.php';
require_role('siswa');
$idUser = (int)current_user()['id_user'];
$stmt = $pdo->prepare('SELECT * FROM siswa WHERE id_user=?');
$stmt->execute([$idUser]);
$profil = $stmt->fetch();
