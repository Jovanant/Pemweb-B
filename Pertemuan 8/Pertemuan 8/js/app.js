// Data & fungsi bersama (localStorage)
var KEY = 'nusa_anggota';

var SEED = [
  { id: 1, nama: 'Bima Pratama', nim: '202400118', prodi: 'Manajemen', telepon: '0813 1111 2222', email: 'bima.pratama@example.com', alamat: 'Jl. Merdeka No. 5, Kota Nusa', jenis: 'Mahasiswa', tanggal: '2026-10-20' },
  { id: 2, nama: 'Citra Maharani', nim: '202500073', prodi: 'Desain Komunikasi Visual', telepon: '0813 2222 3333', email: 'citra.maharani@example.com', alamat: 'Jl. Melati No. 8, Kota Nusa', jenis: 'Mahasiswa', tanggal: '2026-10-19' },
  { id: 3, nama: 'Dr. Ratna Lestari', nim: '19870315001', prodi: 'Ilmu Komunikasi', telepon: '0812 3333 4444', email: 'ratna.lestari@example.com', alamat: 'Jl. Anggrek No. 3, Kota Nusa', jenis: 'Dosen', tanggal: '2026-10-18' },
  { id: 4, nama: 'Yusuf Hidayat, M.T.', nim: '19891127002', prodi: 'Teknik Sipil', telepon: '0812 4444 5555', email: 'yusuf.hidayat@example.com', alamat: 'Jl. Kenanga No. 21, Kota Nusa', jenis: 'Dosen', tanggal: '2026-10-17' },
  { id: 5, nama: 'Sari Wulandari', nim: '19900218003', prodi: 'Administrasi Akademik', telepon: '0811 5555 6666', email: 'sari.wulandari@example.com', alamat: 'Jl. Dahlia No. 9, Kota Nusa', jenis: 'Tenaga kependidikan', tanggal: '2026-10-16' }
];

function getAnggota() {
  try {
    var raw = localStorage.getItem(KEY);
    if (raw) return JSON.parse(raw);
  } catch (e) {}
  // data awal
  saveAnggota(SEED);
  return SEED.slice();
}

// mengembalikan true jika berhasil, false jika gagal
function saveAnggota(list) {
  try {
    localStorage.setItem(KEY, JSON.stringify(list));
    return true;
  } catch (e) {
    return false;
  }
}

function isEmailValid(v) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v);
}

function formatTanggal(iso) {
  var bln = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
  var d = new Date(iso);
  return d.getDate() + ' ' + bln[d.getMonth()] + ' ' + d.getFullYear();
}

function formatTanggalPanjang(iso) {
  var bln = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
  var d = new Date(iso);
  return d.getDate() + ' ' + bln[d.getMonth()] + ' ' + d.getFullYear();
}

function inisial(nama) {
  var p = nama.replace(/^(Dr\.|Prof\.)\s*/, '').split(' ');
  var s = p[0].charAt(0) + (p.length > 1 ? p[1].charAt(0) : '');
  return s.toUpperCase();
}

function escapeHtml(s) {
  return String(s).replace(/[&<>"']/g, function (c) {
    return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
  });
}

// Penanda menu aktif pada halaman beranda (mengikuti scroll)
document.addEventListener('DOMContentLoaded', function () {
  var page = document.body.getAttribute('data-page');
  if (page !== 'beranda') return;
  var links = {
    beranda: document.querySelector('[data-nav="beranda"]'),
    tentang: document.querySelector('[data-nav="tentang"]'),
    layanan: document.querySelector('[data-nav="layanan"]')
  };
  function setActive(key) {
    for (var k in links) links[k].classList.toggle('active', k === key);
  }
  function onScroll() {
    var y = window.scrollY + 120;
    var t = document.getElementById('tentang').offsetTop;
    var l = document.getElementById('layanan').offsetTop;
    var next = document.getElementById('koleksi').offsetTop;
    if (y >= l && y < next) setActive('layanan');
    else if (y >= t && y < l) setActive('tentang');
    else setActive('beranda');
  }
  window.addEventListener('scroll', onScroll);
  onScroll();
});
