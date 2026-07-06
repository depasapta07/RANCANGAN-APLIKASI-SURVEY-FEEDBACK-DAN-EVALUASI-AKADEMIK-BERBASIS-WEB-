-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 06 Jul 2026 pada 21.17
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `feedback`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admins`
--

CREATE TABLE `admins` (
  `id_admin` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admins`
--

INSERT INTO `admins` (`id_admin`, `username`, `password`) VALUES
(1, 'admin', '123');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jawaban`
--

CREATE TABLE `jawaban` (
  `id_jawaban` int(11) NOT NULL,
  `id_pertanyaan` int(11) DEFAULT NULL,
  `id_mahasiswa` int(11) DEFAULT NULL,
  `skor` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id_mahasiswa` int(11) NOT NULL,
  `npm` varchar(12) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_mhs` varchar(100) DEFAULT NULL,
  `prodi` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mahasiswa`
--

INSERT INTO `mahasiswa` (`id_mahasiswa`, `npm`, `password`, `nama_mhs`, `prodi`) VALUES
(3, '251106040016', '222', 'depa', 'informatika'),
(16, '241063117001', '12345678', 'Ahmad Faisal', 'Teknik Informatika'),
(17, '241063117002', '12345678', 'Rian Hidayat', 'Teknik Informatika'),
(18, '241063117003', '12345678', 'Siti Aminah', 'Teknik Informatika'),
(19, '251063117015', '12345678', 'Dewi Lestari', 'Teknik Informatika'),
(20, '251063117022', '12345678', 'Bagas Pramudya', 'Teknik Informatika'),
(21, '251106040010', '122125', 'Nur Aulia Deswita', 'akutansi');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pertanyaan`
--

CREATE TABLE `pertanyaan` (
  `id_pertanyaan` int(11) NOT NULL,
  `id_survey` int(11) DEFAULT NULL,
  `teks_pertanyaan` text NOT NULL,
  `jenis_pilihan` enum('skala_likert','pilihan_ganda','esai') DEFAULT 'skala_likert'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `surveys`
--

CREATE TABLE `surveys` (
  `id_survey` int(11) NOT NULL,
  `judul_survey` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `target_prodi` varchar(100) NOT NULL,
  `status` enum('aktif','non-aktif','draft') DEFAULT 'draft'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `surveys`
--

INSERT INTO `surveys` (`id_survey`, `judul_survey`, `deskripsi`, `tanggal_mulai`, `tanggal_selesai`, `target_prodi`, `status`) VALUES
(17, 'Evaluasi Kinerja Dosen Semester Ganjil', 'Penilaian terhadap metode pengajaran dan kompetensi dosen selama satu semester.', '2026-07-01', '2026-07-20', 'Semua Prodi', 'aktif'),
(18, 'Survei Kepuasan Layanan BAUK', 'Evaluasi efisiensi dan keramahan pelayanan administrasi keuangan kampus.', '2026-07-03', '2026-07-15', 'Semua Prodi', 'aktif'),
(19, 'Kuesioner Fasilitas Laboratorium Komputer', 'Mengukur kelayakan hardware dan software pendukung praktikum mahasiswa.', '2026-07-05', '2026-07-25', 'Teknik Informatika', 'aktif'),
(20, 'Survei Kebutuhan Buku Perpustakaan Utama', 'Pendataan usulan judul buku baru yang paling dibutuhkan mahasiswa tahun ini.', '2026-07-10', '2026-08-05', 'Semua Prodi', 'draft'),
(21, 'Evaluasi Sistem KRS Online (Siakad)', 'Feedback performa server dan kemudahan antarmuka sistem pengisian KRS.', '2026-07-12', '2026-07-30', 'Semua Prodi', 'aktif'),
(22, 'Survei Minat Bakat Organisasi Mahasiswa', 'Pemetaan ketertarikan mahasiswa baru terhadap UKM dan BEM fakultas.', '2026-07-15', '2026-08-15', 'Semua Prodi', 'draft'),
(23, 'Evaluasi Pelaksanaan Kuliah Kerja Nyata (KKN)', 'Kuesioner mengenai dampak program kerja KKN terhadap masyarakat desa binaan.', '2026-05-01', '2026-05-30', 'Sistem Informasi', 'non-aktif'),
(24, 'Survei Kualitas Jaringan Wi-Fi Kampus', 'Mengukur kecepatan dan stabilitas koneksi internet di area gedung perkuliahan.', '2026-07-08', '2026-07-28', 'Semua Prodi', 'aktif'),
(25, 'Kuesioner Kesiapan Karier Mahasiswa Akhir', 'Pendataan kesiapan mental dan skill mahasiswa dalam menghadapi dunia kerja.', '2026-07-11', '2026-08-11', 'Manajemen', 'aktif'),
(26, 'Survei Kebersihan Lingkungan dan Kantin', 'Penilaian fasilitas sanitasi, kenyamanan tempat makan, dan kebersihan kampus.', '2026-04-10', '2026-04-30', 'Semua Prodi', 'non-aktif');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `jawaban`
--
ALTER TABLE `jawaban`
  ADD PRIMARY KEY (`id_jawaban`),
  ADD KEY `id_pertanyaan` (`id_pertanyaan`),
  ADD KEY `id_mahasiswa` (`id_mahasiswa`);

--
-- Indeks untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id_mahasiswa`),
  ADD UNIQUE KEY `nim` (`npm`);

--
-- Indeks untuk tabel `pertanyaan`
--
ALTER TABLE `pertanyaan`
  ADD PRIMARY KEY (`id_pertanyaan`),
  ADD KEY `id_survey` (`id_survey`);

--
-- Indeks untuk tabel `surveys`
--
ALTER TABLE `surveys`
  ADD PRIMARY KEY (`id_survey`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admins`
--
ALTER TABLE `admins`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `jawaban`
--
ALTER TABLE `jawaban`
  MODIFY `id_jawaban` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id_mahasiswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `pertanyaan`
--
ALTER TABLE `pertanyaan`
  MODIFY `id_pertanyaan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `surveys`
--
ALTER TABLE `surveys`
  MODIFY `id_survey` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `jawaban`
--
ALTER TABLE `jawaban`
  ADD CONSTRAINT `jawaban_ibfk_1` FOREIGN KEY (`id_pertanyaan`) REFERENCES `pertanyaan` (`id_pertanyaan`) ON DELETE CASCADE,
  ADD CONSTRAINT `jawaban_ibfk_2` FOREIGN KEY (`id_mahasiswa`) REFERENCES `mahasiswa` (`id_mahasiswa`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pertanyaan`
--
ALTER TABLE `pertanyaan`
  ADD CONSTRAINT `pertanyaan_ibfk_1` FOREIGN KEY (`id_survey`) REFERENCES `surveys` (`id_survey`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
