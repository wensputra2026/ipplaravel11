@extends('layouts.app')

@section('title', 'Profil Akun')
@section('page_title', 'Profil Akun')
@section('page_subtitle', 'Kelola informasi identitas, foto profil, dan keamanan akun Anda')

@section('content')
<div class="container-fluid px-0" x-data="{
    photoPreview: '{{ $user->photo_url ?? '' }}',
    hasPhoto: {{ $user->photo ? 'true' : 'false' }},
    removePhoto: false,
    fileName: '',
    fileSize: '',
    previewFile(event) {
        const file = event.target.files[0];
        if (file) {
            this.fileName = file.name;
            const sizeInKb = (file.size / 1024).toFixed(1);
            this.fileSize = sizeInKb > 1024 ? (sizeInKb / 1024).toFixed(2) + ' MB' : sizeInKb + ' KB';
            const reader = new FileReader();
            reader.onload = (e) => {
                this.photoPreview = e.target.result;
                this.removePhoto = false;
            };
            reader.readAsDataURL(file);
        }
    }
}">

  <!-- Profile Hero Banner -->
  <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="position-relative bg-gradient p-4 p-md-5 text-white" style="background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 50%, #3b82f6 100%);">
      <div class="d-flex flex-column flex-md-row align-items-center gap-4">
        <!-- Avatar Section with Quick Upload Trigger -->
        <div class="position-relative">
          <template x-if="photoPreview && !removePhoto">
            <img :src="photoPreview" alt="{{ $user->nama }}" class="rounded-circle object-fit-cover shadow-lg border border-4 border-white" style="width: 120px; height: 120px;">
          </template>
          <template x-if="!photoPreview || removePhoto">
            <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center fw-bold shadow-lg border border-4 border-white" style="width: 120px; height: 120px; font-size: 2.8rem;">
              {{ strtoupper(substr($user->nama ?? $user->username ?? 'U', 0, 1)) }}
            </div>
          </template>
          <label for="fotoInputHero" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-2 shadow d-flex align-items-center justify-content-center cursor-pointer border border-2 border-white hover-scale" style="width: 38px; height: 38px; cursor: pointer;" title="Ganti Foto">
            <x-heroicon-o-camera class="heroicon-sm text-white" />
          </label>
        </div>

        <!-- User Info Header -->
        <div class="text-center text-md-start">
          <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-start gap-2 mb-2">
            <h2 class="h3 fw-bold mb-0 text-white">{{ $user->nama ?? $user->username }}</h2>
            <span class="badge bg-white text-primary fw-semibold px-2.5 py-1 rounded-pill d-inline-flex align-items-center gap-1">
              <x-heroicon-o-shield-check class="heroicon-sm" /> {{ $user->role_name }}
            </span>
            @if($user->is_active)
              <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill d-inline-flex align-items-center gap-1">
                <span class="bg-success rounded-circle" style="width: 6px; height: 6px;"></span> Akun Aktif
              </span>
            @endif
          </div>
          <p class="text-white-50 mb-2 small d-flex flex-wrap align-items-center justify-content-center justify-content-md-start gap-2">
            <span class="d-inline-flex align-items-center gap-1"><x-heroicon-o-identification class="heroicon-sm" /> Username: <strong class="text-white">{{ $user->username }}</strong></span>
            @if($user->email)
              <span class="mx-1">•</span>
              <span class="d-inline-flex align-items-center gap-1"><x-heroicon-o-envelope class="heroicon-sm" /> {{ $user->email }}</span>
            @endif
            @if($user->kelas)
              <span class="mx-1">•</span>
              <span class="d-inline-flex align-items-center gap-1"><x-heroicon-o-building-library class="heroicon-sm" /> Wali Kelas: <strong class="text-white">{{ $user->kelas->nama_kelas }}</strong></span>
            @endif
          </p>
          <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-start gap-3 small text-white-50">
            <span class="d-inline-flex align-items-center gap-1"><x-heroicon-o-clock class="heroicon-sm" /> Terakhir Masuk: {{ $user->last_login ? \Carbon\Carbon::parse($user->last_login)->translatedFormat('d M Y, H:i') : 'Belum tercatat' }}</span>
            <span class="d-inline-flex align-items-center gap-1"><x-heroicon-o-calendar class="heroicon-sm" /> Terdaftar Sejak: {{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->translatedFormat('d M Y') : '-' }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Full Width Main Content Grid -->
  <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="profileForm">
    @csrf
    @method('PUT')

    <div class="row g-4">
      <!-- Left Column: Photo Management & Quick Account Overview -->
      <div class="col-xl-4 col-lg-5">
        <!-- Photo Management Card -->
        <div class="card border-0 shadow-sm rounded-3 mb-4">
          <div class="card-header bg-white py-3 border-bottom">
            <h6 class="card-title mb-0 fw-bold text-dark d-flex align-items-center gap-2">
              <x-heroicon-o-photo class="heroicon text-primary" /> Foto Profil Pengguna
            </h6>
          </div>
          <div class="card-body p-4 text-center">
            <!-- Large Avatar Box -->
            <div class="position-relative d-inline-block mb-3">
              <template x-if="photoPreview && !removePhoto">
                <img :src="photoPreview" alt="{{ $user->nama }}" class="rounded-circle object-fit-cover shadow-sm border border-3 border-primary-subtle" style="width: 140px; height: 140px;">
              </template>
              <template x-if="!photoPreview || removePhoto">
                <div class="rounded-circle bg-light text-secondary d-flex align-items-center justify-content-center fw-bold shadow-sm border border-3 border-secondary-subtle" style="width: 140px; height: 140px; font-size: 3.5rem;">
                  {{ strtoupper(substr($user->nama ?? $user->username ?? 'U', 0, 1)) }}
                </div>
              </template>
            </div>

            <!-- Selected File Info Badge -->
            <div x-show="fileName && !removePhoto" class="mb-3">
              <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill small d-inline-flex align-items-center gap-1">
                <x-heroicon-o-photo class="heroicon-sm" /> <span x-text="fileName"></span> (<span x-text="fileSize"></span>)
              </span>
            </div>

            <!-- Upload File Input -->
            <div class="mb-3">
              <input type="file" name="foto" id="fotoInputHero" class="d-none" accept="image/jpeg,image/png,image/webp,image/jpg" @change="previewFile">
              <label for="fotoInputHero" class="btn btn-outline-primary w-100 py-2 rounded-3 fw-semibold d-inline-flex align-items-center justify-content-center gap-1.5">
                <x-heroicon-o-arrow-up-tray class="heroicon-sm" /> Pilih Foto Baru
              </label>
              <div class="form-text small text-muted text-start mt-2 d-flex align-items-center gap-1">
                <x-heroicon-o-information-circle class="heroicon-sm text-primary" style="flex-shrink:0" />
                Format: <strong>JPG, JPEG, PNG, WEBP</strong> (Maks. 5MB).
              </div>
            </div>

            <!-- Automatic Compression Notice -->
            <div class="alert alert-info border-0 rounded-3 p-2.5 text-start mb-3" style="background-color: #eff6ff; font-size: 0.8rem; color: #1e40af;">
              <div class="d-flex align-items-start gap-2">
                <x-heroicon-s-bolt class="heroicon-sm text-warning mt-0.5" style="flex-shrink:0" />
                <div>
                  <strong>Kompresi Otomatis Aktif:</strong> Berkas foto akan otomatis dioptimasi dan dikompresi ke resolusi ideal tanpa mengurangi kejernihan dan ketajaman gambar, sehingga aplikasi tetap sangat cepat dan hemat penyimpanan.
                </div>
              </div>
            </div>

            <!-- Remove Photo Option -->
            @if($user->photo)
              <div class="form-check text-start pt-2 border-top">
                <input class="form-check-input" type="checkbox" name="remove_photo" id="removePhotoCheck" value="1" x-model="removePhoto">
                <label class="form-check-label small text-danger fw-semibold d-inline-flex align-items-center gap-1" for="removePhotoCheck">
                  <x-heroicon-o-trash class="heroicon-sm" /> Hapus foto profil saat ini
                </label>
              </div>
            @endif
          </div>
        </div>

        <!-- Account Meta Card -->
        <div class="card border-0 shadow-sm rounded-3">
          <div class="card-header bg-white py-3 border-bottom">
            <h6 class="card-title mb-0 fw-bold text-dark d-flex align-items-center gap-2">
              <x-heroicon-o-information-circle class="heroicon text-primary" /> Detail Akun
            </h6>
          </div>
          <div class="card-body p-0">
            <ul class="list-group list-group-flush small">
              <li class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                <span class="text-muted d-inline-flex align-items-center gap-1.5"><x-heroicon-o-user class="heroicon-sm" /> Username</span>
                <span class="fw-semibold text-dark">{{ $user->username }}</span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                <span class="text-muted d-inline-flex align-items-center gap-1.5"><x-heroicon-o-shield-check class="heroicon-sm" /> Hak Akses (Role)</span>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">{{ $user->role_name }}</span>
              </li>
              @if($user->isWali())
                <li class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                  <span class="text-muted d-inline-flex align-items-center gap-1.5"><x-heroicon-o-building-library class="heroicon-sm" /> Kelas Binaan</span>
                  <span class="fw-semibold text-primary">{{ $user->kelas->nama_kelas ?? ($user->effectiveKelas()->nama_kelas ?? 'Belum Ditentukan') }}</span>
                </li>
              @endif
              @if($user->gtk)
                <li class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                  <span class="text-muted d-inline-flex align-items-center gap-1.5"><x-heroicon-o-identification class="heroicon-sm" /> Profil GTK</span>
                  <span class="fw-semibold text-dark">{{ $user->gtk->nama }}</span>
                </li>
              @endif
              <li class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                <span class="text-muted d-inline-flex align-items-center gap-1.5"><x-heroicon-o-check-circle class="heroicon-sm" /> Status Akun</span>
                <span class="badge bg-success-subtle text-success border border-success-subtle">Aktif</span>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Right Column: Personal Data & Security Form -->
      <div class="col-xl-8 col-lg-7">
        <!-- Biodata Card -->
        <div class="card border-0 shadow-sm rounded-3 mb-4">
          <div class="card-header bg-white py-3 border-bottom">
            <h6 class="card-title mb-0 fw-bold text-dark d-flex align-items-center gap-2">
              <x-heroicon-o-identification class="heroicon text-primary" /> Data Pribadi Pengguna
            </h6>
          </div>
          <div class="card-body p-4">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label small fw-semibold">Username Sistem</label>
                <div class="input-group">
                  <span class="input-group-text bg-light text-muted"><x-heroicon-o-at-symbol class="heroicon-sm" /></span>
                  <input type="text" class="form-control bg-light" value="{{ $user->username }}" readonly disabled>
                </div>
                <div class="form-text small text-muted">Username login bersifat permanen dan tidak dapat diubah.</div>
              </div>

              <div class="col-md-6">
                <label class="form-label small fw-semibold">Role / Hak Akses</label>
                <div class="input-group">
                  <span class="input-group-text bg-light text-muted"><x-heroicon-o-lock-closed class="heroicon-sm" /></span>
                  <input type="text" class="form-control bg-light" value="{{ $user->role_name }}" readonly disabled>
                </div>
                <div class="form-text small text-muted">Hak akses ditentukan oleh Administrator sistem.</div>
              </div>

              <div class="col-md-6">
                <label class="form-label small fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text bg-white text-secondary"><x-heroicon-o-user class="heroicon-sm" /></span>
                  <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $user->nama) }}" placeholder="Masukkan nama lengkap Anda" required>
                </div>
                @error('nama')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6">
                <label class="form-label small fw-semibold">Alamat Email</label>
                <div class="input-group">
                  <span class="input-group-text bg-white text-secondary"><x-heroicon-o-envelope class="heroicon-sm" /></span>
                  <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" placeholder="nama@email.com">
                </div>
                @error('email')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>
        </div>

        <!-- Security / Password Card -->
        <div class="card border-0 shadow-sm rounded-3 mb-4">
          <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
            <h6 class="card-title mb-0 fw-bold text-dark d-flex align-items-center gap-2">
              <x-heroicon-o-key class="heroicon text-primary" /> Keamanan Akun & Kata Sandi
            </h6>
            <span class="badge bg-light text-muted border small">Opsional</span>
          </div>
          <div class="card-body p-4">
            <p class="text-muted small mb-3">
              Kosongkan kolom di bawah jika Anda tidak ingin mengubah kata sandi akun saat ini.
            </p>

            <div class="row g-3">
              <div class="col-md-12">
                <label class="form-label small fw-semibold">Kata Sandi Saat Ini</label>
                <div class="input-group" x-data="{ showCurrent: false }">
                  <span class="input-group-text bg-white text-secondary"><x-heroicon-o-lock-closed class="heroicon-sm" /></span>
                  <input :type="showCurrent ? 'text' : 'password'" name="current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="Wajib diisi jika ingin mengganti kata sandi">
                  <button type="button" class="btn btn-outline-secondary d-flex align-items-center" @click="showCurrent = !showCurrent" tabindex="-1" title="Lihat/Sembunyikan Sandi">
                    <x-heroicon-o-eye x-show="!showCurrent" class="heroicon-sm" />
                    <x-heroicon-o-eye-slash x-show="showCurrent" class="heroicon-sm" />
                  </button>
                </div>
                @error('current_password')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6">
                <label class="form-label small fw-semibold">Kata Sandi Baru</label>
                <div class="input-group" x-data="{ showNew: false }">
                  <span class="input-group-text bg-white text-secondary"><x-heroicon-o-shield-check class="heroicon-sm" /></span>
                  <input :type="showNew ? 'text' : 'password'" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 4 karakter">
                  <button type="button" class="btn btn-outline-secondary d-flex align-items-center" @click="showNew = !showNew" tabindex="-1" title="Lihat/Sembunyikan Sandi">
                    <x-heroicon-o-eye x-show="!showNew" class="heroicon-sm" />
                    <x-heroicon-o-eye-slash x-show="showNew" class="heroicon-sm" />
                  </button>
                </div>
                @error('password')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6">
                <label class="form-label small fw-semibold">Konfirmasi Kata Sandi Baru</label>
                <div class="input-group" x-data="{ showConfirm: false }">
                  <span class="input-group-text bg-white text-secondary"><x-heroicon-o-shield-check class="heroicon-sm" /></span>
                  <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" class="form-control" placeholder="Ulangi kata sandi baru">
                  <button type="button" class="btn btn-outline-secondary d-flex align-items-center" @click="showConfirm = !showConfirm" tabindex="-1" title="Lihat/Sembunyikan Sandi">
                    <x-heroicon-o-eye x-show="!showConfirm" class="heroicon-sm" />
                    <x-heroicon-o-eye-slash x-show="showConfirm" class="heroicon-sm" />
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer bg-white border-top py-3 text-end">
            <button type="submit" class="btn btn-primary px-4 py-2 rounded-3 fw-semibold shadow-sm d-inline-flex align-items-center gap-1.5">
              <x-heroicon-o-check-circle class="heroicon-sm me-1" /> Simpan Perubahan Profil
            </button>
          </div>
        </div>

        <!-- /Security / Password Card -->
      </div>
    </div>
  </form>
</div>
@endsection