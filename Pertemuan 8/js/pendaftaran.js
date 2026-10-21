var form = document.getElementById('formDaftar');
var alertForm = document.getElementById('alertForm');
var modalSukses = new bootstrap.Modal(document.getElementById('modalSukses'));

function setError(input, msg) {
  input.classList.add('is-invalid');
  var fb = input.parentElement.querySelector('.invalid-feedback');
  if (fb) fb.textContent = msg;
}

function clearErrors() {
  form.querySelectorAll('.is-invalid').forEach(function (el) { el.classList.remove('is-invalid'); });
  alertForm.classList.add('d-none');
}

form.addEventListener('submit', function (e) {
  e.preventDefault();
  clearErrors();

  var f = {
    nama: document.getElementById('nama'),
    nim: document.getElementById('nim'),
    prodi: document.getElementById('prodi'),
    telepon: document.getElementById('telepon'),
    email: document.getElementById('email'),
    alamat: document.getElementById('alamat'),
    setuju: document.getElementById('setuju')
  };
  var jenis = form.querySelector('input[name="jenis"]:checked').value;
  var errors = 0;

  // kolom wajib
  var wajib = { nama: 'Nama lengkap wajib diisi.', nim: 'NIM/NIP wajib diisi.', prodi: 'Program studi wajib dipilih.', telepon: 'Nomor telepon wajib diisi.', email: 'Email wajib diisi.', alamat: 'Alamat wajib diisi.' };
  for (var k in wajib) {
    if (!f[k].value.trim()) { setError(f[k], wajib[k]); errors++; }
  }

  // format email
  if (f.email.value.trim() && !isEmailValid(f.email.value.trim())) {
    setError(f.email, 'Masukkan alamat email yang valid, misalnya nama@example.com.');
    errors++;
  }

  // format telepon (angka, spasi, +, -)
  if (f.telepon.value.trim() && !/^[0-9+\-\s]{8,16}$/.test(f.telepon.value.trim())) {
    setError(f.telepon, 'Nomor telepon hanya boleh berisi angka (8-16 karakter).');
    errors++;
  }

  // keunikan NIM/NIP
  var list = getAnggota();
  var nim = f.nim.value.trim();
  if (nim) {
    var ada = list.find(function (a) { return a.nim === nim; });
    if (ada) {
      setError(f.nim, 'NIM/NIP sudah terdaftar atas nama ' + ada.nama + '. Gunakan NIM/NIP milikmu.');
      errors++;
    }
  }

  if (!f.setuju.checked) { f.setuju.classList.add('is-invalid'); errors++; }

  if (errors > 0) {
    alertForm.textContent = 'Ada ' + errors + ' data yang perlu diperiksa. Perbaiki sebelum menyimpan.';
    alertForm.classList.remove('d-none');
    return;
  }

  var maxId = list.reduce(function (m, a) { return Math.max(m, a.id); }, 0);
  var baru = {
    id: maxId + 1,
    nama: f.nama.value.trim(),
    nim: nim,
    prodi: f.prodi.value,
    telepon: f.telepon.value.trim(),
    email: f.email.value.trim(),
    alamat: f.alamat.value.trim(),
    jenis: jenis,
    tanggal: new Date().toISOString().slice(0, 10)
  };
  list.unshift(baru);

  if (!saveAnggota(list)) {
    alertForm.textContent = 'Gagal menyimpan: penyimpanan browser penuh atau tidak tersedia. Kosongkan ruang lalu coba lagi.';
    alertForm.classList.remove('d-none');
    return;
  }

  sessionStorage.setItem('nusa_baru', baru.id);

  document.getElementById('sNamaJudul').textContent = 'Selamat bergabung, ' + baru.nama.split(' ')[0] + '!';
  document.getElementById('sNama').textContent = baru.nama;
  document.getElementById('sNim').textContent = baru.nim;
  document.getElementById('sProdi').textContent = baru.prodi;
  document.getElementById('sEmail').textContent = baru.email;
  document.getElementById('sJenis').textContent = baru.jenis;
  document.getElementById('sInfo').textContent = 'Total menjadi ' + list.length + ' anggota. Tersimpan lokal di browser (localStorage), tanpa pengiriman ke server.';
  modalSukses.show();
  form.reset();
});

// hapus pesan error saat pengguna mengetik
form.addEventListener('input', function (e) {
  e.target.classList.remove('is-invalid');
});
