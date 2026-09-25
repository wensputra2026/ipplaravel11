-- Database Schema & Master Data for E-IPP
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE `activity_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `activity_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `module` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_activity_type` (`activity_type`),
  CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2587 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `app_settings`;
CREATE TABLE `app_settings` (
  `setting_key` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`setting_key`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `gtk`;
CREATE TABLE `gtk` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Path to foto file',
  `nama` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `nuptk` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `jk` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `tempat_lahir` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `tanggal_lahir` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `nip` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status_kepegawaian` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `jenis_ptk` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `agama` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `alamat_jalan` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `rt` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `rw` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `nama_dusun` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `desa_kelurahan` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `kecamatan` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `kode_pos` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `telepon` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `hp` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `email` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `tugas_tambahan` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `sk_cpns` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `tanggal_cpns` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `sk_pengangkatan` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `tmt_pengangkatan` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `lembaga_pengangkatan` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `pangkat_golongan` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `sumber_gaji` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `nama_ibu_kandung` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status_perkawinan` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `nama_suami_istri` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `nip_suami_istri` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `pekerjaan_suami_istri` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `tmt_pns` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `sudah_lisensi_kepala_sekolah` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `pernah_diklat_kepengawasan` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `keahlian_braille` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `keahlian_bahasa_isyarat` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `npwp` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `nama_wajib_pajak` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `kewarganegaraan` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `bank` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `nomor_rekening_bank` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `rekening_atas_nama` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `nik` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `no_kk` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `karpeg` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `karis_karsu` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `lintang` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `bujur` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `nuks` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `kelas`;
CREATE TABLE `kelas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_kelas` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tingkat` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `wali_kelas_id` int DEFAULT NULL,
  `tahun_ajaran` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '2026',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `semester` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jurusan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `wali_kelas_id` (`wali_kelas_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `master_kategori_siswa`;
CREATE TABLE `master_kategori_siswa` (
  `id_kategori` int NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `master_pekerjaan`;
CREATE TABLE `master_pekerjaan` (
  `id_pekerjaan` int NOT NULL AUTO_INCREMENT,
  `nama_pekerjaan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_pekerjaan`),
  UNIQUE KEY `nama_pekerjaan` (`nama_pekerjaan`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `master_penghasilan`;
CREATE TABLE `master_penghasilan` (
  `id_penghasilan` int NOT NULL AUTO_INCREMENT,
  `range_penghasilan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_penghasilan`),
  UNIQUE KEY `range_penghasilan` (`range_penghasilan`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `master_sumber_biaya`;
CREATE TABLE `master_sumber_biaya` (
  `id_sumber_biaya` int NOT NULL AUTO_INCREMENT,
  `nama_sumber_biaya` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_sumber_biaya`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `master_tahun_ajaran`;
CREATE TABLE `master_tahun_ajaran` (
  `id_tahun_ajaran` int NOT NULL AUTO_INCREMENT,
  `tahun_ajaran` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_tahun_ajaran`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `siswa`;
CREATE TABLE `siswa` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nis` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nisn` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kelas_id` int DEFAULT NULL,
  `nama_siswa` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jk` enum('L','P') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agama` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tempat_lahir` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `rt` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rw` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dusun` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kelurahan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sumber_biaya` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kategori_siswa` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tahun_ajaran` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `semester` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Aktif',
  `tanggal_lulus` date DEFAULT NULL,
  `tahun_lulus` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_ijazah` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan_kelulusan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `nama_ayah` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pekerjaan_ayah` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `penghasilan_ayah` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat_ayah` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `nama_ibu` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pekerjaan_ibu` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `penghasilan_ibu` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat_ibu` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `jml_tanggungan_ortu` int DEFAULT '0',
  `nama_wali_l` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pekerjaan_wali_l` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `penghasilan_wali_l` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat_wali_l` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `nama_wali_p` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pekerjaan_wali_p` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `penghasilan_wali_p` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat_wali_p` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `jml_tanggungan_wali` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `dok_kk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dok_sktm` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dok_slip_gaji` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dok_bansos` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kategori_ipp` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nominal_ipp` int DEFAULT '0',
  `ket_bansos` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `keadaan_rumah` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ket_kondisi_rumah` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status_kepemilikan_rumah` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kondisi_kerentanan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `kepemilikan_hp` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pemanfaatan_hp` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `akses_ortu_hp` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `akses_internet` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_kk` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jarak_sekolah` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `waktu_tempuh` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `moda_transportasi` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `moda_transportasi_lainnya` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `biaya_transportasi` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_kepemilikan_kendaraan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=443 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `level` enum('admin','wali') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'wali',
  `kelas_id` int DEFAULT NULL,
  `gtk_id` int DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `walikelas`;
CREATE TABLE `walikelas` (
  `id_walikelas` int NOT NULL AUTO_INCREMENT,
  `id_gtk` int NOT NULL,
  `id_kelas` int NOT NULL,
  `tahun_ajaran` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `semester` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_walikelas`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `app_settings` (`setting_key`, `setting_value`) VALUES ('active_semester', 'Ganjil');
INSERT INTO `app_settings` (`setting_key`, `setting_value`) VALUES ('active_tahun_ajaran', '2026/2027');
INSERT INTO `app_settings` (`setting_key`, `setting_value`) VALUES ('app_name', 'E-IPP');
INSERT INTO `app_settings` (`setting_key`, `setting_value`) VALUES ('school_logo', 'logo_1767853884.png');
INSERT INTO `app_settings` (`setting_key`, `setting_value`) VALUES ('school_name', 'SMAN Benlutu');

INSERT INTO `master_kategori_siswa` (`id_kategori`, `nama_kategori`) VALUES ('1', 'Anak Panti Asuhan');
INSERT INTO `master_kategori_siswa` (`id_kategori`, `nama_kategori`) VALUES ('2', 'Anak Korban Bencana');
INSERT INTO `master_kategori_siswa` (`id_kategori`, `nama_kategori`) VALUES ('3', 'Anak Terlantar');
INSERT INTO `master_kategori_siswa` (`id_kategori`, `nama_kategori`) VALUES ('4', 'Anak dari Ortu Berkebutuhan Khusus');
INSERT INTO `master_kategori_siswa` (`id_kategori`, `nama_kategori`) VALUES ('5', 'Anak dari Ortu Sakit Menahun');

INSERT INTO `master_pekerjaan` (`id_pekerjaan`, `nama_pekerjaan`) VALUES ('11', 'Buruh');
INSERT INTO `master_pekerjaan` (`id_pekerjaan`, `nama_pekerjaan`) VALUES ('6', 'Karyawan Swasta');
INSERT INTO `master_pekerjaan` (`id_pekerjaan`, `nama_pekerjaan`) VALUES ('14', 'Lainnya');
INSERT INTO `master_pekerjaan` (`id_pekerjaan`, `nama_pekerjaan`) VALUES ('2', 'Nelayan');
INSERT INTO `master_pekerjaan` (`id_pekerjaan`, `nama_pekerjaan`) VALUES ('8', 'Pedagang Besar');
INSERT INTO `master_pekerjaan` (`id_pekerjaan`, `nama_pekerjaan`) VALUES ('7', 'Pedagang Kecil');
INSERT INTO `master_pekerjaan` (`id_pekerjaan`, `nama_pekerjaan`) VALUES ('12', 'Pensiunan');
INSERT INTO `master_pekerjaan` (`id_pekerjaan`, `nama_pekerjaan`) VALUES ('3', 'Petani');
INSERT INTO `master_pekerjaan` (`id_pekerjaan`, `nama_pekerjaan`) VALUES ('4', 'Peternak');
INSERT INTO `master_pekerjaan` (`id_pekerjaan`, `nama_pekerjaan`) VALUES ('5', 'PNS/TNI/Polri');
INSERT INTO `master_pekerjaan` (`id_pekerjaan`, `nama_pekerjaan`) VALUES ('13', 'Tenaga Kerja Indonesia (TKI)');
INSERT INTO `master_pekerjaan` (`id_pekerjaan`, `nama_pekerjaan`) VALUES ('1', 'Tidak Bekerja');
INSERT INTO `master_pekerjaan` (`id_pekerjaan`, `nama_pekerjaan`) VALUES ('9', 'Wiraswasta');
INSERT INTO `master_pekerjaan` (`id_pekerjaan`, `nama_pekerjaan`) VALUES ('10', 'Wirausaha');

INSERT INTO `master_penghasilan` (`id_penghasilan`, `range_penghasilan`) VALUES ('22', 'Rp 2.700.000');
INSERT INTO `master_penghasilan` (`id_penghasilan`, `range_penghasilan`) VALUES ('23', 'Rp 2.900.000');
INSERT INTO `master_penghasilan` (`id_penghasilan`, `range_penghasilan`) VALUES ('21', 'Rp 3.200.000');
INSERT INTO `master_penghasilan` (`id_penghasilan`, `range_penghasilan`) VALUES ('20', 'Rp 3.800.000');
INSERT INTO `master_penghasilan` (`id_penghasilan`, `range_penghasilan`) VALUES ('24', 'Rp 4.200.000');
INSERT INTO `master_penghasilan` (`id_penghasilan`, `range_penghasilan`) VALUES ('25', 'Rp 4.800.000');
INSERT INTO `master_penghasilan` (`id_penghasilan`, `range_penghasilan`) VALUES ('26', 'Rp 5.500.000');
INSERT INTO `master_penghasilan` (`id_penghasilan`, `range_penghasilan`) VALUES ('27', 'Rp 8.000.000');
INSERT INTO `master_penghasilan` (`id_penghasilan`, `range_penghasilan`) VALUES ('30', 'Tidak Berpenghasilan');

INSERT INTO `master_sumber_biaya` (`id_sumber_biaya`, `nama_sumber_biaya`) VALUES ('1', 'Orangtua Kandung');
INSERT INTO `master_sumber_biaya` (`id_sumber_biaya`, `nama_sumber_biaya`) VALUES ('2', 'Wali');
INSERT INTO `master_sumber_biaya` (`id_sumber_biaya`, `nama_sumber_biaya`) VALUES ('3', 'Pihak Lainnya');

INSERT INTO `master_tahun_ajaran` (`id_tahun_ajaran`, `tahun_ajaran`) VALUES ('2', '2025/2026');
INSERT INTO `master_tahun_ajaran` (`id_tahun_ajaran`, `tahun_ajaran`) VALUES ('3', '2026/2027');

INSERT INTO `kelas` (`id`, `nama_kelas`, `tingkat`, `wali_kelas_id`, `tahun_ajaran`, `created_at`, `semester`, `jurusan`) VALUES ('1', 'X Merdeka 1', 'X', NULL, '2025/2026', '2026-01-08 18:08:33', 'Genap', 'Kurikulum SMA Merdeka');
INSERT INTO `kelas` (`id`, `nama_kelas`, `tingkat`, `wali_kelas_id`, `tahun_ajaran`, `created_at`, `semester`, `jurusan`) VALUES ('2', 'X Merdeka 2', 'X', NULL, '2025/2026', '2026-01-08 18:08:33', 'Genap', 'Kurikulum SMA Merdeka');
INSERT INTO `kelas` (`id`, `nama_kelas`, `tingkat`, `wali_kelas_id`, `tahun_ajaran`, `created_at`, `semester`, `jurusan`) VALUES ('3', 'X Merdeka 3', 'X', NULL, '2025/2026', '2026-01-08 18:08:33', 'Genap', 'Kurikulum SMA Merdeka');
INSERT INTO `kelas` (`id`, `nama_kelas`, `tingkat`, `wali_kelas_id`, `tahun_ajaran`, `created_at`, `semester`, `jurusan`) VALUES ('4', 'X Merdeka 4', 'X', NULL, '2025/2026', '2026-01-08 18:08:33', 'Genap', 'Kurikulum SMA Merdeka');
INSERT INTO `kelas` (`id`, `nama_kelas`, `tingkat`, `wali_kelas_id`, `tahun_ajaran`, `created_at`, `semester`, `jurusan`) VALUES ('5', 'X Merdeka 5', 'X', NULL, '2025/2026', '2026-01-08 18:08:33', 'Genap', 'Kurikulum SMA Merdeka');
INSERT INTO `kelas` (`id`, `nama_kelas`, `tingkat`, `wali_kelas_id`, `tahun_ajaran`, `created_at`, `semester`, `jurusan`) VALUES ('6', 'XI Merdeka 1', 'XI', NULL, '2025/2026', '2026-01-08 18:08:33', 'Genap', 'Kurikulum SMA Merdeka');
INSERT INTO `kelas` (`id`, `nama_kelas`, `tingkat`, `wali_kelas_id`, `tahun_ajaran`, `created_at`, `semester`, `jurusan`) VALUES ('7', 'XI Merdeka 2', 'XI', NULL, '2025/2026', '2026-01-08 18:08:33', 'Genap', 'Kurikulum SMA Merdeka');
INSERT INTO `kelas` (`id`, `nama_kelas`, `tingkat`, `wali_kelas_id`, `tahun_ajaran`, `created_at`, `semester`, `jurusan`) VALUES ('8', 'XI Merdeka 3', 'XI', NULL, '2025/2026', '2026-01-08 18:08:33', 'Genap', 'Kurikulum SMA Merdeka');
INSERT INTO `kelas` (`id`, `nama_kelas`, `tingkat`, `wali_kelas_id`, `tahun_ajaran`, `created_at`, `semester`, `jurusan`) VALUES ('9', 'XI Merdeka 4', 'XI', NULL, '2025/2026', '2026-01-08 18:08:33', 'Genap', 'Kurikulum SMA Merdeka');
INSERT INTO `kelas` (`id`, `nama_kelas`, `tingkat`, `wali_kelas_id`, `tahun_ajaran`, `created_at`, `semester`, `jurusan`) VALUES ('10', 'XI Merdeka 5', 'XI', NULL, '2025/2026', '2026-01-08 18:08:33', 'Genap', 'Kurikulum SMA Merdeka');
INSERT INTO `kelas` (`id`, `nama_kelas`, `tingkat`, `wali_kelas_id`, `tahun_ajaran`, `created_at`, `semester`, `jurusan`) VALUES ('11', 'XII Merdeka 1', 'XII', NULL, '2025/2026', '2026-01-08 18:08:33', 'Genap', 'Kurikulum SMA Merdeka');
INSERT INTO `kelas` (`id`, `nama_kelas`, `tingkat`, `wali_kelas_id`, `tahun_ajaran`, `created_at`, `semester`, `jurusan`) VALUES ('12', 'XII Merdeka 2', 'XII', NULL, '2025/2026', '2026-01-08 18:08:33', 'Genap', 'Kurikulum SMA Merdeka');
INSERT INTO `kelas` (`id`, `nama_kelas`, `tingkat`, `wali_kelas_id`, `tahun_ajaran`, `created_at`, `semester`, `jurusan`) VALUES ('13', 'XII Merdeka 3', 'XII', NULL, '2025/2026', '2026-01-08 18:08:33', 'Genap', 'Kurikulum SMA Merdeka');
INSERT INTO `kelas` (`id`, `nama_kelas`, `tingkat`, `wali_kelas_id`, `tahun_ajaran`, `created_at`, `semester`, `jurusan`) VALUES ('14', 'XII Merdeka 4', 'XII', NULL, '2025/2026', '2026-01-08 18:08:33', 'Genap', 'Kurikulum SMA Merdeka');

INSERT INTO `users` (`id`, `username`, `password`, `nama`, `email`, `level`, `is_active`, `created_at`, `updated_at`) VALUES (1, 'admin', '$2y$10$4uVX9awgioqgFPQwQKIjg.Uzcx9UXhqx/5ka2hD1tiqSw9csmeL4a', 'Administrator', 'admin@smanbenlutu.sch.id', 'admin', 1, NOW(), NOW()) ON DUPLICATE KEY UPDATE `password`='$2y$10$4uVX9awgioqgFPQwQKIjg.Uzcx9UXhqx/5ka2hD1tiqSw9csmeL4a';

SET FOREIGN_KEY_CHECKS = 1;
