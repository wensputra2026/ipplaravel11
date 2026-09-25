# E-IPP SMAN Benlutu &mdash; Sistem Transparansi & Tata Kelola Iuran Pendidikan

[![Laravel Version](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpinedotjs&logoColor=white)](https://alpinejs.dev)
[![Heroicons](https://img.shields.io/badge/Heroicons-v2-0ea5e9?style=for-the-badge)](https://heroicons.com)

**E-IPP SMAN Benlutu** adalah platform aplikasi web berbasis Laravel 11 yang dirancang khusus untuk memfasilitasi pendataan profil peserta didik, identifikasi kondisi sosial ekonomi orang tua/wali, pemetaan kategori afirmasi subsidi pendidikan, serta transparansi publik atas pengelolaan Iuran Pengembangan Pendidikan (IPP).

---

## Daftar Isi

1. [Fitur Utama Aplikasi](#fitur-utama-aplikasi)
2. [Spesifikasi Teknis & Arsitektur](#spesifikasi-teknis--arsitektur)
3. [7 Pilar Keamanan Web & OWASP Top 10 Hardening](#7-pilar-keamanan-web--owasp-top-10-hardening)
4. [Komponen Khusus: Tom Select AJAX Reusable Blade](#komponen-khusus-tom-select-ajax-reusable-blade)
5. [Prasyarat Sistem](#prasyarat-sistem)
6. [Panduan Instalasi & Deployment](#panduan-instalasi--deployment)
7. [Struktur Direktori Kunci](#struktur-direktori-kunci)
8. [Hak Akses & Pengguna Bawaan](#hak-akses--pengguna-bawaan)
9. [Standar Bahasa & Format Tanggal](#standar-bahasa--format-tanggal)
10. [Lisensi & Hak Cipta](#lisensi--hak-cipta)

---

## Fitur Utama Aplikasi

### 1. Portal Publik Transparansi Modern & Elegan
- **Dashboard Publik Interaktif**: Ringkasan peserta didik aktif, proporsi gender laki-laki dan perempuan, serta jumlah rombongan belajar (rombel).
- **Filter Fleksibel Real-time**: Penyaringan data berdasarkan Tahun Ajaran aktif dan Semester (Ganjil/Genap).
- **Prioritas Afirmasi Khusus (Kategori A s.d. E)**:
  - Afirmasi A: Anak Panti Asuhan
  - Afirmasi B: Anak Korban Bencana
  - Afirmasi C: Anak Terlantar
  - Afirmasi D: Orang Tua Berkebutuhan Khusus
  - Afirmasi E: Orang Tua Sakit Menahun
- **Kelengkapan Profil per Rombel**: Indikator persentase kesiapan data siswa di masing-masing kelas.
- **Karakteristik Sosial Ekonomi**: Sebaran pekerjaan dan rentang penghasilan ayah serta ibu peserta didik.
- **Beban Tanggungan & Direktori Keluarga**: Visualisasi perbandingan tanggungan per siswa dan per kepala keluarga dilengkapi fitur pencarian instan (*live search*).

### 2. Modul Kesiswaan & Akademik Terpadu
- **Pencatatan 6 Dimensi Profil Siswa**:
  1. Identitas Siswa (NIS, NISN, Nama, Tempat/Tanggal Lahir, Gender, Agama, Status)
  2. Domisili (Alamat, RT/RW, Dusun, Desa/Kelurahan)
  3. Orang Tua & Wali (Nama, Pekerjaan, Penghasilan, Kontak, Jumlah Tanggungan)
  4. Rumah & Digital (Keadaan Rumah, Status Kepemilikan, Kerentanan, HP, Internet)
  5. Transportasi (Alat Transportasi, Jarak Tempuh, Biaya Harian, Kendaraan)
  6. Biaya & Dokumen (No KK, Kategori Siswa, Sumber Biaya, Foto Profil, Dokumen SKTM/Bansos)
- **Kalkulasi & Konversi Massal IPP**: Sistem penentuan subsidi otomatis (0% s.d. 100%) berbasis kondisi ekonomi dan dokumen bansos/SKTM.
- **Pindah Rombel & Kenaikan Kelas**: Fitur pemindahan rombongan belajar dan pemrosesan kenaikan kelas berjenjang secara batch.
- **Kelulusan & Arsip Alumni**: Pelacakan status alumni dengan opsi pembatalan kelulusan jika terjadi koreksi data.
- **Validasi & Monitoring Berkas**: Notifikasi progres berkas siswa yang belum lengkap bagi Administrator dan Wali Kelas.

### 3. Komponen Dropdown Pintar (Tom Select AJAX)
- Menggantikan dropdown statis peramban dengan pencarian dinamis berbasis remote endpoint JSON (`/siswa/search-ajax`).
- Dukungan *single-select* dan *multi-select tags*.
- Auto-initialization global untuk elemen dengan class `.tom-select` dan `.select2`.
- Sinkronisasi otomatis saat membuka tab Bootstrap 5 (`shown.bs.tab`).

---

## Spesifikasi Teknis & Arsitektur

| Komponen | Spesifikasi / Pustaka |
| :--- | :--- |
| **Framework** | Laravel 11.56.x (Full-Stack MVC) |
| **Bahasa Pemrograman** | PHP 8.2.x atau PHP 8.3.x |
| **Database Engine** | MySQL 8.0+ / MariaDB 10.4+ |
| **CSS Framework** | Bootstrap 5.3.3 + Tailwind CSS Utilities |
| **Tipografi** | Google Fonts (*Plus Jakarta Sans* & *Outfit*) |
| **Ikon Antarmuka** | Blade Heroicons v2 (`blade-ui-kit/blade-heroicons`) |
| **JavaScript Reactivity** | Alpine.js v3.14.8 + Vanilla JS (No jQuery dependency for custom components) |
| **Dropdown Component** | Tom Select v2.3.1 (Bootstrap 5 theme) |
| **Alert & Dialog** | SweetAlert2 v11 |

---

## 7 Pilar Keamanan Web & OWASP Top 10 Hardening

Aplikasi telah diperketat dengan standar keamanan enterprise:

1. **Autentikasi & Otorisasi**:
   - Algoritma hashing kata sandi `argon2id` / `bcrypt` (12 rounds).
   - Role-Based Access Control (RBAC) via middleware `CheckRole` dan Laravel Policies.
   - Session invalidation dan token regeneration saat logout.
2. **Rate Limiting (Mitigasi Brute-Force & DoS)**:
   - Endpoint Login dibatasi maksimal **5 percobaan per menit per IP** (`throttle:login`).
   - Endpoint AJAX Search dibatasi maksimal **60 request per menit** (`throttle:search_ajax`).
   - Respon otomatis HTTP 429 (*Too Many Requests*) dengan header `Retry-After`.
3. **Validasi & Sanitasi Input (Anti-SQLi & Anti-XSS)**:
   - Validasi ketat via Form Request.
   - Proteksi Mass Assignment via atribut `$fillable` pada setiap Eloquent Model.
   - Validasi file upload dengan verifikasi MIME type asli / magic bytes file binary.
4. **Proteksi CSRF & SameSite Cookie**:
   - Middleware `VerifyCsrfToken` aktif untuk semua request mutasi (`POST`, `PUT`, `DELETE`).
   - Pengiriman token via meta tag dan header `X-CSRF-TOKEN`.
   - Konfigurasi session cookie `SameSite=Lax`, `HttpOnly=true`, dan `Secure=true` pada HTTPS.
5. **CORS Hardening**:
   - Larangan wildcard (`*`) pada `allowed_origins` di lingkungan produksi.
   - Whitelist domain frontend terdaftar melalui environment variable `CORS_ALLOWED_ORIGINS`.
6. **HTTPS & Security Headers Middleware**:
   - Penerapan `App\Http\Middleware\SecurityHeaders`:
     - `Strict-Transport-Security: max-age=31536000; includeSubDomains; preload` (HSTS)
     - `X-Frame-Options: SAMEORIGIN` (Anti-Clickjacking)
     - `X-Content-Type-Options: nosniff` (Anti-MIME sniffing)
     - `Referrer-Policy: strict-origin-when-cross-origin`
     - `Permissions-Policy: camera=(), microphone=(), geolocation=()`
     - `X-XSS-Protection: 1; mode=block`
7. **Environment Variables & Secret Hygiene**:
   - File `.env` terisolasi dan diabaikan dari Git (`.gitignore`).
   - Mode `APP_DEBUG=false` wajib pada lingkungan produksi.
   - Rekomendasi permission file: `chmod 600 .env`.

---

## Komponen Khusus: Tom Select AJAX Reusable Blade

Komponen ini tersedia di `resources/views/components/tom-select-ajax.blade.php`.

### Props yang Diterima:
- `name` *(string, required)*: Nama field input formulir.
- `endpoint` *(string, required)*: URL endpoint pencarian remote JSON.
- `placeholder` *(string, optional, default: 'Cari data...')*: Teks placeholder.
- `multiple` *(bool, optional, default: false)*: Mengaktifkan mode multi-select tags.
- `value` *(string/int/array, optional)*: Nilai ID awal untuk mode edit.
- `selectedText` *(string/array, optional)*: Label teks awal untuk mode edit.
- `debounce` *(int, optional, default: 300)*: Jeda waktu ketik sebelum request dikirim (milidetik).

### Contoh Penggunaan di Blade:
```blade
{{-- 1. Mode Create (Pencarian Single) --}}
<x-tom-select-ajax 
    name="siswa_id" 
    :endpoint="route('siswa.search_ajax')" 
    placeholder="Ketik nama atau NIS siswa..." 
/>

{{-- 2. Mode Edit (Pre-filled Data Terpilih) --}}
<x-tom-select-ajax 
    name="siswa_id" 
    :endpoint="route('siswa.search_ajax')" 
    :value="$pembayaran->siswa_id" 
    :selected-text="$pembayaran->siswa->nama_siswa . ' - NIS: ' . $pembayaran->siswa->nis" 
/>

{{-- 3. Mode Multi-Select Tags --}}
<x-tom-select-ajax 
    name="siswa_ids" 
    :endpoint="route('siswa.search_ajax')" 
    :multiple="true" 
    placeholder="Pilih beberapa siswa..." 
/>
```

---

## Prasyarat Sistem

Sebelum memasang aplikasi, pastikan server Anda memenuhi persyaratan berikut:
- **PHP** minimal versi **8.2.0** dengan ekstensi aktif:
  - `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`, `gd` / `imagick`
- **Composer** versi 2.x
- **MySQL** versi 8.0+ atau **MariaDB** versi 10.4+
- **Git**
- Peramban web modern (Google Chrome, Firefox, Microsoft Edge, Safari)

---

## Panduan Instalasi & Deployment

### 1. Kloning Repositori
```bash
git clone https://github.com/wensputra2026/ipplaravel11.git
cd ipplaravel11
```

### 2. Salin Berkas Konfigurasi Lingkungan
```bash
cp .env.example .env
```

### 3. Pasang Dependensi Composer
```bash
composer install --no-dev --optimize-autoloader
```
*(Untuk lingkungan pengembangan lokal, cukup jalankan `composer install`)*

### 4. Buat Application Key
```bash
php artisan key:generate
```

### 5. Konfigurasi Database
Buka berkas `.env` lalu sesuaikan kredensial basis data Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ipplaravel11
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Jalankan Migrasi & Seeder Basis Data
```bash
php artisan migrate --seed
```

### 7. Buat Symlink Storage Berkas
```bash
php artisan storage:link
```

### 8. Jalankan Server Pengembangan
Jika menggunakan **Laragon**, akses melalui peramban di `http://ipplaravel11.test` atau jalankan artisan dev server:
```bash
php artisan serve
```
Akses aplikasi melalui peramban di `http://127.0.0.1:8000`.

---

## Struktur Direktori Kunci

```
ipplaravel11/
├── app/
│   ├── Http/
│   │   ├── Controllers/         # Controller utama (Frontend, Siswa, Auth, Kelas, dll.)
│   │   └── Middleware/          # CheckRole, SecurityHeaders, dll.
│   ├── Models/                  # Model Eloquent (Siswa, Kelas, User, AppSetting, dll.)
│   └── Providers/
│       └── AppServiceProvider.php  # Konfigurasi RateLimiter, Carbon Locale 'id'
├── bootstrap/
│   └── app.php                  # Registrasi middleware global & alias Laravel 11
├── config/                      # Konfigurasi framework (app, auth, cors, session, dll.)
├── database/
│   ├── migrations/              # Skema tabel basis data
│   └── seeders/                 # Data awal pengguna, kelas, referensi
├── resources/
│   └── views/
│       ├── components/
│       │   └── tom-select-ajax.blade.php  # Reusable Tom Select AJAX Component
│       ├── frontend/
│       │   └── index.blade.php            # Halaman depan / portal publik modern
│       ├── layouts/
│       │   ├── app.blade.php              # Layout induk dashboard internal
│       │   └── partials/
│       │       └── navbar.blade.php       # Navigasi & drawer mobile (Heroicons)
│       └── siswa/                         # View manajemen siswa (index, create, edit, show, konversi)
└── routes/
    └── web.php                  # Pendaftaran seluruh rute web & rate limiter
```

---

## Hak Akses & Pengguna Bawaan

| Role / Level | Cakupan Akses |
| :--- | :--- |
| **Administrator** | Memiliki akses menyeluruh: manajemen siswa, master data rombel, penugasan wali kelas, GTK, konfigurasi sistem, kelulusan, dan activity logs. |
| **Wali Kelas** | Akses kesiswaan dibatasi hanya pada siswa yang berada di dalam rombongan belajar binaannya. |

> **Catatan Keamanan**: Segera ubah kata sandi default pengguna Administrator melalui menu **Profil Akun** setelah aplikasi berhasil di-deploy ke server produksi.

---

## Standar Bahasa & Format Tanggal

Seluruh antarmuka, pesan validasi, dan penanggalan telah distandardisasi dalam **Bahasa Indonesia**:
- Locale Carbon diatur ke `'id'` di `AppServiceProvider.php`.
- Contoh pemformatan tanggal pada tampilan Blade:
  ```blade
  {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}
  {{-- Output: 25 September 2026 --}}
  ```

---

## Lisensi & Hak Cipta

Aplikasi ini dikembangkan dan dipelihara untuk lingkungan **SMAN Benlutu**.
Hak cipta dilindungi oleh undang-undang &copy; 2026 SMAN Benlutu & Pengembang.
Dilindungi di bawah lisensi [MIT License](LICENSE).
