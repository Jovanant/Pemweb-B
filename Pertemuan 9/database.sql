-- Jalankan file ini lewat phpMyAdmin (tab Import / SQL)
CREATE DATABASE IF NOT EXISTS db_psb;
USE db_psb;

CREATE TABLE IF NOT EXISTS siswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    alamat TEXT NOT NULL,
    jenis_kelamin VARCHAR(20) NOT NULL,
    agama VARCHAR(20) NOT NULL,
    sekolah_asal VARCHAR(100) NOT NULL
);

-- Data contoh (boleh dihapus)
INSERT INTO siswa (nama, alamat, jenis_kelamin, agama, sekolah_asal) VALUES
('Budi Santoso', 'Jl. Mawar No. 1, Surabaya', 'Laki-laki', 'Islam', 'SMP Negeri 1 Surabaya'),
('Siti Aminah', 'Jl. Melati No. 5, Sidoarjo', 'Perempuan', 'Islam', 'SMP Negeri 2 Sidoarjo');
