CREATE DATABASE IF NOT EXISTS db_ppdb_smk CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_ppdb_smk;

SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS riwayat_status;
DROP TABLE IF EXISTS berkas;
DROP TABLE IF EXISTS pendaftaran;
DROP TABLE IF EXISTS jurusan;
DROP TABLE IF EXISTS siswa;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE users (
 id_user INT AUTO_INCREMENT PRIMARY KEY,
 nama VARCHAR(120) NOT NULL,
 email VARCHAR(150) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL,
 role ENUM('admin','siswa') NOT NULL DEFAULT 'siswa',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE siswa (
 id_siswa INT AUTO_INCREMENT PRIMARY KEY,
 id_user INT NOT NULL UNIQUE,
 nisn CHAR(10) NOT NULL UNIQUE,
 nama_lengkap VARCHAR(150) NOT NULL,
 jenis_kelamin ENUM('L','P') NOT NULL,
 tempat_lahir VARCHAR(100) DEFAULT NULL,
 tanggal_lahir DATE DEFAULT NULL,
 alamat TEXT NOT NULL,
 asal_sekolah VARCHAR(150) NOT NULL,
 no_hp VARCHAR(25) NOT NULL,
 CONSTRAINT fk_siswa_user FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE jurusan (
 id_jurusan INT AUTO_INCREMENT PRIMARY KEY,
 kode_jurusan VARCHAR(10) NOT NULL UNIQUE,
 nama_jurusan VARCHAR(120) NOT NULL,
 kuota INT NOT NULL DEFAULT 0,
 deskripsi TEXT,
 aktif TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;

CREATE TABLE pendaftaran (
 id_pendaftaran INT AUTO_INCREMENT PRIMARY KEY,
 nomor_pendaftaran VARCHAR(30) NOT NULL UNIQUE,
 id_siswa INT NOT NULL,
 id_jurusan INT NOT NULL,
 tahun_ajaran VARCHAR(9) NOT NULL,
 tanggal_daftar TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 status ENUM('Menunggu','Diverifikasi','Diterima','Ditolak') NOT NULL DEFAULT 'Menunggu',
 tanggal_pengumuman DATETIME DEFAULT NULL,
 UNIQUE KEY uq_siswa_tahun (id_siswa,tahun_ajaran),
 CONSTRAINT fk_daftar_siswa FOREIGN KEY (id_siswa) REFERENCES siswa(id_siswa) ON DELETE CASCADE,
 CONSTRAINT fk_daftar_jurusan FOREIGN KEY (id_jurusan) REFERENCES jurusan(id_jurusan)
) ENGINE=InnoDB;

CREATE TABLE berkas (
 id_berkas INT AUTO_INCREMENT PRIMARY KEY,
 id_pendaftaran INT NOT NULL,
 jenis_berkas ENUM('KK','Akta','Rapor','Pas Foto') NOT NULL,
 nama_file VARCHAR(255) NOT NULL,
 nama_asli VARCHAR(255) NOT NULL,
 mime_type VARCHAR(100) NOT NULL,
 ukuran INT NOT NULL,
 tanggal_upload TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_berkas_daftar FOREIGN KEY (id_pendaftaran) REFERENCES pendaftaran(id_pendaftaran) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE riwayat_status (
 id_riwayat INT AUTO_INCREMENT PRIMARY KEY,
 id_pendaftaran INT NOT NULL,
 status_sebelumnya VARCHAR(30) DEFAULT NULL,
 status_baru VARCHAR(30) NOT NULL,
 catatan TEXT,
 id_admin INT DEFAULT NULL,
 tanggal_perubahan TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_riwayat_daftar FOREIGN KEY (id_pendaftaran) REFERENCES pendaftaran(id_pendaftaran) ON DELETE CASCADE,
 CONSTRAINT fk_riwayat_admin FOREIGN KEY (id_admin) REFERENCES users(id_user) ON DELETE SET NULL
) ENGINE=InnoDB;

INSERT INTO jurusan (kode_jurusan,nama_jurusan,kuota,deskripsi) VALUES
('RPL','Rekayasa Perangkat Lunak',36,'Mempelajari pengembangan perangkat lunak, pemrograman, dan basis data.'),
('TKJ','Teknik Komputer dan Jaringan',36,'Mempelajari komputer, jaringan, dan infrastruktur teknologi informasi.'),
('AKL','Akuntansi dan Keuangan Lembaga',36,'Mempelajari akuntansi, administrasi keuangan, dan pengelolaan transaksi.'),
('TBG','Tata Boga',36,'Mempelajari pengolahan makanan dan tata hidang.'),
('TKR','Teknik Kendaraan Ringan',36,'Mempelajari perawatan dan perbaikan kendaraan ringan.');

-- Password awal admin: password
-- Hash bcrypt untuk password 'password'. Ganti password setelah login.
INSERT INTO users (nama,email,password,role) VALUES
('Administrator','admin@smknusantara.test','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.','admin');
