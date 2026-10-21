var PER_HALAMAN = 6;
var anggota = getAnggota();
var halaman = 1;
var barisBaruId = null;
var aktifId = null;      // anggota yang sedang dilihat/diedit/dihapus
var modeLihat = false;
var gagalSimpan = null;  // fungsi untuk "Coba lagi"

var modalEdit = new bootstrap.Modal(document.getElementById('modalEdit'));
var modalHapus = new bootstrap.Modal(document.getElementById('modalHapus'));

// notifikasi dari halaman pendaftaran
var baruId = sessionStorage.getItem('nusa_baru');
if (baruId) {
  sessionStorage.removeItem('nusa_baru');
  var b = anggota.find(function (a) { return String(a.id) === baruId; });
  if (b) {
    barisBaruId = b.id;
    tampilNotif('ok', '<strong>' + escapeHtml(b.nama) + ' berhasil ditambahkan</strong><br>NIM ' + escapeHtml(b.nim) + ' kini tercantum dalam daftar. Total ' + anggota.length + ' anggota, termasuk anggota baru ini.');
  }
}

function tampilNotif(tipe, html, retry) {
  var cls = tipe === 'ok' ? 'alert-nusa-ok' : 'alert-nusa-err';
  var icon = tipe === 'ok' ? 'bi-check-circle' : 'bi-exclamation-circle';
  var btn = retry ? '<button type="button" class="btn btn-sm btn-link text-decoration-none fw-semibold p-0 ms-3" id="btnRetry" style="color:var(--red)">Coba lagi</button>' : '';
  document.getElementById('notif').innerHTML =
    '<div class="alert ' + cls + ' d-flex align-items-start gap-2 mb-3" role="alert"><i class="bi ' + icon + '"></i><div class="flex-grow-1">' + html + '</div>' + btn +
    '<button type="button" class="btn-close btn-sm" id="btnTutupNotif" aria-label="Tutup"></button></div>';
  document.getElementById('btnTutupNotif').onclick = function () { document.getElementById('notif').innerHTML = ''; };
  if (retry) document.getElementById('btnRetry').onclick = retry;
}

function hitungStat() {
  var m = 0, d = 0, t = 0;
  anggota.forEach(function (a) {
    if (a.jenis === 'Mahasiswa') m++;
    else if (a.jenis === 'Dosen') d++;
    else t++;
  });
  document.getElementById('stTotal').textContent = anggota.length;
  document.getElementById('stMhs').textContent = m;
  document.getElementById('stDosen').textContent = d;
  document.getElementById('stTendik').textContent = t;
}

function hasilFilter() {
  var q = document.getElementById('cari').value.trim().toLowerCase();
  var j = document.getElementById('filterJenis').value;
  return anggota.filter(function (a) {
    var cocokCari = !q || a.nama.toLowerCase().indexOf(q) > -1 || a.nim.toLowerCase().indexOf(q) > -1;
    var cocokJenis = !j || a.jenis === j;
    return cocokCari && cocokJenis;
  });
}

function render() {
  hitungStat();
  var data = hasilFilter();
  var totalHal = Math.max(1, Math.ceil(data.length / PER_HALAMAN));
  if (halaman > totalHal) halaman = totalHal;
  var mulai = (halaman - 1) * PER_HALAMAN;
  var tampil = data.slice(mulai, mulai + PER_HALAMAN);

  var html = '';
  if (tampil.length === 0) {
    html = '<tr><td colspan="6" class="text-center text-muted-2 py-5">Tidak ada anggota yang cocok dengan pencarian.</td></tr>';
  }
  tampil.forEach(function (a) {
    var baru = a.id === barisBaruId;
    html += '<tr class="' + (baru ? 'row-new' : '') + '">' +
      '<td><span class="avatar">' + inisial(a.nama) + '</span><span class="fw-semibold" style="color:var(--green)">' + escapeHtml(a.nama) + '</span>' +
      '<div class="small text-muted-2" style="margin-left:42px">' + (baru ? 'Anggota baru · ' : '') + 'Aktif</div></td>' +
      '<td>' + escapeHtml(a.nim) + '</td>' +
      '<td><span class="chip">' + escapeHtml(a.jenis) + '</span></td>' +
      '<td>' + escapeHtml(a.prodi) + '</td>' +
      '<td>' + formatTanggal(a.tanggal) + '</td>' +
      '<td class="text-nowrap">' +
      '<button class="icon-btn me-1" title="Lihat detail" onclick="bukaDetail(' + a.id + ', true)"><i class="bi bi-eye"></i></button>' +
      '<button class="icon-btn me-1" title="Edit" onclick="bukaDetail(' + a.id + ', false)"><i class="bi bi-pencil"></i></button>' +
      '<button class="icon-btn del" title="Hapus" onclick="bukaHapus(' + a.id + ')"><i class="bi bi-trash"></i></button>' +
      '</td></tr>';
  });
  document.getElementById('tbody').innerHTML = html;

  document.getElementById('infoJumlah').textContent = data.length + ' anggota';
  document.getElementById('infoTampil').textContent = data.length === 0 ? 'Menampilkan 0 anggota' :
    'Menampilkan ' + (mulai + 1) + '–' + (mulai + tampil.length) + ' dari ' + data.length + ' anggota';

  var p = '<li class="page-item ' + (halaman === 1 ? 'disabled' : '') + '"><a class="page-link" href="#" onclick="ke(' + (halaman - 1) + ');return false"><i class="bi bi-chevron-left"></i></a></li>';
  for (var i = 1; i <= totalHal; i++) {
    p += '<li class="page-item ' + (i === halaman ? 'active' : '') + '"><a class="page-link" href="#" onclick="ke(' + i + ');return false">' + i + '</a></li>';
  }
  p += '<li class="page-item ' + (halaman === totalHal ? 'disabled' : '') + '"><a class="page-link" href="#" onclick="ke(' + (halaman + 1) + ');return false"><i class="bi bi-chevron-right"></i></a></li>';
  document.getElementById('pager').innerHTML = p;
}

function ke(n) { halaman = n; render(); }

document.getElementById('cari').addEventListener('input', function () { halaman = 1; render(); });
document.getElementById('filterJenis').addEventListener('change', function () { halaman = 1; render(); });

// ---------- Detail & edit ----------
var kolom = { nama: 'eNama', nim: 'eNim', prodi: 'eProdi', telepon: 'eTelepon', email: 'eEmail', alamat: 'eAlamat', jenis: 'eJenis' };

function bersihkanErrorEdit() {
  document.querySelectorAll('#formEdit .is-invalid').forEach(function (el) { el.classList.remove('is-invalid'); });
  document.getElementById('mAlert').classList.add('d-none');
}

function bukaDetail(id, lihat) {
  var a = anggota.find(function (x) { return x.id === id; });
  if (!a) return;
  aktifId = id;
  modeLihat = lihat;
  bersihkanErrorEdit();
  for (var k in kolom) {
    var el = document.getElementById(kolom[k]);
    el.value = a[k];
    el.disabled = lihat;
  }
  document.getElementById('mJudul').textContent = lihat ? 'Detail anggota' : 'Detail & edit anggota';
  document.getElementById('mSub').textContent = lihat ? 'Informasi identitas anggota.' : 'Tinjau identitas dan perbarui data anggota.';
  document.getElementById('mAvatar').textContent = inisial(a.nama);
  document.getElementById('mNamaHead').textContent = a.nama;
  document.getElementById('mMeta').textContent = 'Terdaftar ' + formatTanggalPanjang(a.tanggal) + ' · NUS-' + a.tanggal.slice(0, 4) + '-' + String(a.id).padStart(4, '0');
  document.getElementById('btnSimpan').classList.toggle('d-none', lihat);
  document.getElementById('btnBatal').textContent = lihat ? 'Tutup' : 'Batal';
  modalEdit.show();
}

function errEdit(id, msg) {
  var el = document.getElementById(id);
  el.classList.add('is-invalid');
  var fb = el.parentElement.querySelector('.invalid-feedback');
  if (fb) fb.textContent = msg;
}

document.getElementById('formEdit').addEventListener('input', function (e) { e.target.classList.remove('is-invalid'); });

document.getElementById('btnSimpan').addEventListener('click', function () {
  bersihkanErrorEdit();
  var v = {};
  for (var k in kolom) v[k] = document.getElementById(kolom[k]).value.trim();
  var errors = 0;
  var label = { nama: 'Nama lengkap', nim: 'NIM/NIP', prodi: 'Program studi', telepon: 'Nomor telepon', email: 'Email', alamat: 'Alamat' };
  for (var f in label) {
    if (!v[f]) { errEdit(kolom[f], label[f] + ' wajib diisi.'); errors++; }
  }
  if (v.email && !isEmailValid(v.email)) { errEdit('eEmail', 'Masukkan alamat email yang valid.'); errors++; }
  if (v.telepon && !/^[0-9+\-\s]{8,16}$/.test(v.telepon)) { errEdit('eTelepon', 'Nomor telepon hanya boleh berisi angka (8-16 karakter).'); errors++; }
  var dup = anggota.find(function (a) { return a.nim === v.nim && a.id !== aktifId; });
  if (v.nim && dup) { errEdit('eNim', 'NIM/NIP sudah terdaftar atas nama ' + dup.nama + '.'); errors++; }

  if (errors) {
    var al = document.getElementById('mAlert');
    al.textContent = 'Ada ' + errors + ' data yang perlu diperiksa. Perbaiki sebelum menyimpan.';
    al.classList.remove('d-none');
    return;
  }

  var lama = anggota.map(function (a) { return Object.assign({}, a); });
  var a = anggota.find(function (x) { return x.id === aktifId; });
  for (var k2 in kolom) a[k2] = v[k2];

  if (!saveAnggota(anggota)) {
    anggota = lama; // batalkan perubahan di memori
    modalEdit.hide();
    render();
    tampilNotif('err', 'Gagal menyimpan: penyimpanan browser penuh. Perubahan belum tersimpan; kosongkan ruang lalu coba lagi.');
    return;
  }
  modalEdit.hide();
  render();
  tampilNotif('ok', '<strong>' + escapeHtml(a.nama) + ' berhasil diperbarui</strong><br>Perubahan data anggota sudah disimpan.');
});

// ---------- Hapus ----------
function bukaHapus(id) {
  var a = anggota.find(function (x) { return x.id === id; });
  if (!a) return;
  aktifId = id;
  document.getElementById('hNama').textContent = a.nama;
  document.getElementById('hNama2').textContent = a.nama;
  document.getElementById('hAvatar').textContent = inisial(a.nama);
  document.getElementById('hMeta').textContent = a.nim + ' · ' + a.jenis + ' · ' + a.prodi;
  modalHapus.show();
}

function hapusAktif() {
  var idx = anggota.findIndex(function (x) { return x.id === aktifId; });
  if (idx < 0) return;
  var dihapus = anggota[idx];
  var salinan = anggota.slice();
  salinan.splice(idx, 1);
  if (!saveAnggota(salinan)) {
    tampilNotif('err', 'Gagal menyimpan: penyimpanan browser penuh. Perubahan belum tersimpan; kosongkan ruang lalu coba lagi.', hapusAktif);
    return;
  }
  anggota = salinan;
  render();
  tampilNotif('ok', '<strong>' + escapeHtml(dihapus.nama) + ' berhasil dihapus</strong><br>Data anggota telah dihapus dari daftar.');
}

document.getElementById('btnHapus').addEventListener('click', function () {
  modalHapus.hide();
  hapusAktif();
});

render();
