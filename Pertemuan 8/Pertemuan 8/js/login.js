// Simulasi login: tidak ada autentikasi nyata
var formLogin = document.getElementById('formLogin');
var inEmail = document.getElementById('lEmail');
var inSandi = document.getElementById('lSandi');

try {
  var tersimpan = localStorage.getItem('nusa_email_petugas');
  if (tersimpan) { inEmail.value = tersimpan; document.getElementById('ingat').checked = true; }
} catch (e) {}

document.getElementById('toggleSandi').addEventListener('click', function () {
  var tampil = inSandi.type === 'password';
  inSandi.type = tampil ? 'text' : 'password';
  this.querySelector('i').className = tampil ? 'bi bi-eye' : 'bi bi-eye-slash';
});

function err(el, msg) {
  el.classList.add('is-invalid');
  el.parentElement.querySelector('.invalid-feedback').textContent = msg;
}

formLogin.addEventListener('input', function (e) { e.target.classList.remove('is-invalid'); });

formLogin.addEventListener('submit', function (e) {
  e.preventDefault();
  inEmail.classList.remove('is-invalid');
  inSandi.classList.remove('is-invalid');
  var ok = true;
  if (!inEmail.value.trim()) { err(inEmail, 'Email petugas wajib diisi.'); ok = false; }
  else if (!isEmailValid(inEmail.value.trim())) { err(inEmail, 'Format email belum sesuai.'); ok = false; }
  if (!inSandi.value) { err(inSandi, 'Kata sandi wajib diisi.'); ok = false; }
  if (!ok) return;

  try {
    if (document.getElementById('ingat').checked) localStorage.setItem('nusa_email_petugas', inEmail.value.trim());
    else localStorage.removeItem('nusa_email_petugas');
  } catch (e2) {}
  window.location.href = 'daftar-anggota.html';
});
