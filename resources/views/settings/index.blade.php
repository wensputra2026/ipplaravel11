@extends('layouts.app')

@section('title', 'Pengaturan Sistem')
@section('page_title', 'Pengaturan Sistem & Identitas')
@section('page_subtitle', 'Konfigurasi nama aplikasi, identitas sekolah, logo resmi, dan periode akademik aktif')

@section('content')
<form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
  @csrf
  <div class="row g-4">
    <!-- Kolom Kiri: Identitas Sekolah & Aplikasi -->
    <div class="col-lg-7">
      <div class="card h-100">
        <div class="card-header bg-white py-3">
          <div class="fw-bold text-dark d-flex align-items-center gap-1"><x-heroicon-o-building-office class="heroicon-sm text-primary" /> Identitas Sekolah & Aplikasi</div>
          <div class="text-muted small">Informasi umum yang tampil pada kop surat, formulir, dan header sistem</div>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Nama Aplikasi <span class="text-danger">*</span></label>
              <input type="text" name="app_name" class="form-control" value="{{ old('app_name', $appName) }}" placeholder="Contoh: E-IPP" required>
              <div class="form-text small text-muted">Nama singkatan sistem aplikasi</div>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Nama Sekolah <span class="text-danger">*</span></label>
              <input type="text" name="school_name" class="form-control" value="{{ old('school_name', $schoolName) }}" placeholder="Contoh: SMAN Benlutu" required>
              <div class="form-text small text-muted">Nama resmi instansi satuan pendidikan</div>
            </div>

            <div class="col-12" x-data="{
                logoPreview: '{{ $schoolLogo && file_exists(public_path('assets/dist/img/' . $schoolLogo)) ? asset('assets/dist/img/' . $schoolLogo) : '' }}',
                updatePreview(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = e => this.logoPreview = e.target.result;
                        reader.readAsDataURL(file);
                    }
                }
            }">
              <label class="form-label small fw-semibold">Logo Sekolah</label>
              <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3 border">
                <template x-if="logoPreview">
                  <img :src="logoPreview" alt="Logo Preview" class="rounded border p-1 bg-white object-fit-contain shadow-xs flex-shrink-0" style="width: 72px; height: 72px;">
                </template>
                <template x-if="!logoPreview">
                  <div class="rounded border bg-white text-muted d-flex align-items-center justify-content-center flex-shrink-0" style="width: 72px; height: 72px;">
                    <x-heroicon-o-photo class="heroicon-lg text-secondary" style="width: 32px; height: 32px;" />
                  </div>
                </template>
                <div class="flex-grow-1">
                  <input type="file" name="school_logo" class="form-control form-control-sm" accept="image/*" @change="updatePreview">
                  <div class="text-muted small mt-1 d-flex align-items-center gap-1">
                    <x-heroicon-o-information-circle class="heroicon-sm text-secondary" /> Disarankan format PNG/JPG transparan. Gambar akan otomatis dikompresi agar tajam dan ringan.
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Kolom Kanan: Periode Akademik Aktif -->
    <div class="col-lg-5">
      <div class="card h-100">
        <div class="card-header bg-white py-3">
          <div class="fw-bold text-dark d-flex align-items-center gap-1"><x-heroicon-o-calendar-days class="heroicon-sm text-primary" /> Periode Akademik Aktif</div>
          <div class="text-muted small">Tahun ajaran dan semester yang sedang berjalan saat ini</div>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-12">
              <div class="d-flex justify-content-between align-items-center mb-1">
                <label class="form-label small fw-semibold mb-0">Tahun Ajaran Aktif <span class="text-danger">*</span></label>
                <a href="{{ route('master.index') }}" class="small text-decoration-none d-inline-flex align-items-center gap-1">
                  <x-heroicon-o-cog-6-tooth class="heroicon-sm" /> Kelola Master Data
                </a>
              </div>
              <select name="active_tahun_ajaran" class="form-select" required>
                @foreach($tahunList as $ta)
                  <option value="{{ $ta }}" {{ $activeTa == $ta ? 'selected' : '' }}>{{ $ta }}</option>
                @endforeach
              </select>
              <div class="form-text small text-muted">Menentukan data siswa, kelas, dan tarif IPP yang sedang aktif.</div>
            </div>

            <div class="col-12">
              <label class="form-label small fw-semibold">Semester Aktif <span class="text-danger">*</span></label>
              <select name="active_semester" class="form-select" required>
                <option value="Ganjil" {{ $activeSem === 'Ganjil' ? 'selected' : '' }}>Semester Ganjil</option>
                <option value="Genap" {{ $activeSem === 'Genap' ? 'selected' : '' }}>Semester Genap</option>
              </select>
              <div class="form-text small text-muted">Periode semester operasional sekolah saat ini.</div>
            </div>

            <div class="col-12">
              <div class="p-3 bg-primary-subtle rounded-3 border border-primary-subtle text-primary-emphasis small">
                <div class="fw-semibold mb-1 d-flex align-items-center gap-1"><x-heroicon-s-information-circle class="heroicon-sm" /> Informasi Sinkronisasi</div>
                Pengubahan tahun ajaran dan semester aktif otomatis tersinkronisasi ke seluruh sistem (dashboard, rombel kelas, dan filter kesiswaan).
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tombol Simpan Footer Full Width -->
    <div class="col-12">
      <div class="card bg-light border-0 shadow-xs">
        <div class="card-body p-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
          <span class="text-muted small">Pastikan konfigurasi identitas dan periode akademik sudah sesuai sebelum menyimpan.</span>
          <button type="submit" class="btn btn-primary px-4 py-2 d-inline-flex align-items-center gap-1">
            <x-heroicon-o-check class="heroicon-sm" /> Simpan Pengaturan
          </button>
        </div>
      </div>
    </div>
  </div>
</form>
@endsection