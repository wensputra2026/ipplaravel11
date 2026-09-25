@extends('layouts.app')

@section('title', 'Validasi & Progres Data')
@section('page_title', 'Validasi & Kelengkapan Data Siswa')
@section('page_subtitle', $isWali
    ? 'Pantau kepatuhan pengisian profil dan berkas dokumen siswa di kelas Anda'
    : 'Pantau kepatuhan pengisian profil, berkas dokumen, dan kelengkapan data siswa seluruh kelas')

@section('content')
<!-- Overview Metric Cards -->
<div class="row g-2.5 mb-2.5">
  <div class="col-12 col-sm-6 col-lg-4">
    <div class="card border-0 shadow-sm rounded-3 h-100 p-2.5">
      <div class="d-flex align-items-center gap-2.5">
        <div class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px;">
          <x-heroicon-o-users class="heroicon" />
        </div>
        <div>
          <div class="text-secondary small fw-medium" style="font-size: 0.75rem;">Total Siswa Dipantau</div>
          <div class="h5 fw-bold mb-0 text-dark">{{ number_format($totalSiswa) }} <span class="small fw-normal text-muted" style="font-size: 0.75rem;">Siswa</span></div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-lg-4">
    <div class="card border-0 shadow-sm rounded-3 h-100 p-2.5">
      <div class="d-flex align-items-center gap-2.5">
        <div class="rounded-3 bg-success-subtle text-success d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px;">
          <x-heroicon-o-check-circle class="heroicon" />
        </div>
        <div class="flex-grow-1">
          <div class="d-flex justify-content-between align-items-center">
            <span class="text-secondary small fw-medium" style="font-size: 0.75rem;">Profil Lengkap</span>
            <span class="badge bg-success-subtle text-success fw-semibold" style="font-size: 0.68rem;">{{ $persenLengkap }}%</span>
          </div>
          <div class="h5 fw-bold mb-1 text-dark">{{ number_format($lengkapCount) }} <span class="small fw-normal text-muted" style="font-size: 0.75rem;">Siswa</span></div>
          <div class="progress" style="height: 4px;">
            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $persenLengkap }}%;" aria-valuenow="{{ $persenLengkap }}" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-12 col-lg-4">
    <div class="card border-0 shadow-sm rounded-3 h-100 p-2.5">
      <div class="d-flex align-items-center gap-2.5">
        <div class="rounded-3 bg-danger-subtle text-danger d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px;">
          <x-heroicon-o-exclamation-triangle class="heroicon" />
        </div>
        <div>
          <div class="text-secondary small fw-medium" style="font-size: 0.75rem;">Perlu Dilengkapi</div>
          <div class="h5 fw-bold mb-0 text-danger">{{ number_format($totalIncompleteCount) }} <span class="small fw-normal text-muted" style="font-size: 0.75rem;">Siswa</span></div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Main Data Card -->
<div class="card border-0 shadow-sm rounded-3">
  <!-- Card Header with Filters -->
  <div class="card-header bg-white py-2 px-3 border-bottom">
    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-2">
      <div>
        <h6 class="card-title mb-0 fw-bold text-dark d-flex align-items-center gap-1.5" style="font-size: 0.88rem;">
          <x-heroicon-o-clipboard-document-check class="heroicon-sm text-primary" /> Daftar Siswa Profil Belum Lengkap
        </h6>
      </div>

      <!-- Filter Controls Form -->
      <form method="GET" action="{{ route('notifications.index') }}" class="d-flex flex-wrap align-items-center gap-1.5 mb-0">
        @if(Auth::user()->isAdmin())
          <select name="kelas_id" class="form-select form-select-sm py-1" onchange="this.form.submit()" style="min-width: 130px; font-size: 0.78rem;">
            <option value="">-- Semua Kelas --</option>
            @foreach($kelasList as $k)
              <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
            @endforeach
          </select>
        @endif

        <select name="missing" class="form-select form-select-sm py-1" onchange="this.form.submit()" style="min-width: 150px; font-size: 0.78rem;">
          <option value="">-- Semua Kekurangan --</option>
          <option value="foto" {{ request('missing') === 'foto' ? 'selected' : '' }}>Foto Belum Diunggah</option>
          <option value="nisn" {{ request('missing') === 'nisn' ? 'selected' : '' }}>NISN Kosong</option>
          <option value="no_kk" {{ request('missing') === 'no_kk' ? 'selected' : '' }}>No KK Kosong</option>
          <option value="nama_ayah" {{ request('missing') === 'nama_ayah' ? 'selected' : '' }}>Nama Ayah Kosong</option>
          <option value="nama_ibu" {{ request('missing') === 'nama_ibu' ? 'selected' : '' }}>Nama Ibu Kosong</option>
          <option value="kategori_ipp" {{ request('missing') === 'kategori_ipp' ? 'selected' : '' }}>Kategori IPP Kosong</option>
        </select>

        <div class="input-group input-group-sm" style="min-width: 160px;">
          <input type="text" name="search" class="form-control py-1" style="font-size: 0.78rem;" placeholder="Cari nama / NIS..." value="{{ request('search') }}">
          <button class="btn btn-outline-secondary py-1 d-inline-flex align-items-center" type="submit"><x-heroicon-o-magnifying-glass class="heroicon-sm" /></button>
        </div>

        @if(request('kelas_id') || request('missing') || request('search'))
          <a href="{{ route('notifications.index') }}" class="btn btn-outline-danger btn-sm py-1 px-2 d-inline-flex align-items-center gap-1" style="font-size: 0.78rem;" title="Reset Filter">
            <x-heroicon-o-arrow-path class="heroicon-sm" /> Reset
          </a>
        @endif
      </form>
    </div>
  </div>

  <!-- Table Body -->
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th class="text-center" style="width: 45px;">No</th>
            <th>Siswa</th>
            <th>Kelas</th>
            <th>Field / Berkas yang Belum Lengkap</th>
            <th class="text-end" style="width: 110px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($incompleteStudents as $idx => $s)
            @php
              $missing = [];
              if (empty($s->foto))         $missing[] = ['field' => 'Foto Profil', 'type' => 'foto', 'color' => 'danger'];
              if (empty($s->nisn))         $missing[] = ['field' => 'NISN', 'type' => 'nisn', 'color' => 'warning'];
              if (empty($s->no_kk))        $missing[] = ['field' => 'No KK', 'type' => 'no_kk', 'color' => 'danger'];
              if (empty($s->nama_ayah))    $missing[] = ['field' => 'Nama Ayah', 'type' => 'ayah', 'color' => 'secondary'];
              if (empty($s->nama_ibu))     $missing[] = ['field' => 'Nama Ibu', 'type' => 'ibu', 'color' => 'secondary'];
              if (empty($s->kategori_ipp)) $missing[] = ['field' => 'Kategori IPP', 'type' => 'ipp', 'color' => 'info'];
            @endphp
            <tr>
              <td class="text-center text-muted small">{{ $incompleteStudents->firstItem() + $idx }}</td>
              <td>
                <div class="d-flex align-items-center gap-2">
                  @if($s->foto_url)
                    <img src="{{ $s->foto_url }}" alt="{{ $s->nama_siswa }}" class="rounded-circle object-fit-cover shadow-xs border" style="width: 28px; height: 28px;">
                  @else
                    <div class="rounded-circle bg-light text-secondary d-flex align-items-center justify-content-center fw-semibold shadow-xs" style="width: 28px; height: 28px; font-size: 0.75rem;">
                      {{ strtoupper(substr($s->nama_siswa ?? 'S', 0, 1)) }}
                    </div>
                  @endif
                  <div class="lh-sm">
                    <a href="{{ route('siswa.show', $s->id) }}" class="fw-bold text-dark text-decoration-none hover-primary">
                      {{ $s->nama_siswa }}
                    </a>
                    <div class="text-muted" style="font-size: 0.72rem;">
                      NIS: {{ $s->nis ?: '-' }} • NISN: {{ $s->nisn ?: '-' }}
                    </div>
                  </div>
                </div>
              </td>
              <td>
                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-0.5 rounded-pill" style="font-size: 0.7rem;">
                  {{ $s->kelas->nama_kelas ?? 'Belum ada kelas' }}
                </span>
              </td>
              <td>
                <div class="d-flex flex-wrap gap-1">
                  @foreach($missing as $m)
                    <span class="badge bg-{{ $m['color'] }}-subtle text-{{ $m['color'] }} border border-{{ $m['color'] }}-subtle py-0.5 px-1.5 rounded-pill d-inline-flex align-items-center gap-1" style="font-size: 0.68rem;">
                      @if($m['type'] === 'foto')
                        <x-heroicon-o-camera style="width: 12px; height: 12px;" />
                      @elseif($m['type'] === 'nisn')
                        <x-heroicon-o-identification style="width: 12px; height: 12px;" />
                      @elseif($m['type'] === 'no_kk')
                        <x-heroicon-o-document-text style="width: 12px; height: 12px;" />
                      @elseif($m['type'] === 'ayah' || $m['type'] === 'ibu')
                        <x-heroicon-o-user style="width: 12px; height: 12px;" />
                      @elseif($m['type'] === 'ipp')
                        <x-heroicon-o-banknotes style="width: 12px; height: 12px;" />
                      @endif
                      {{ $m['field'] }}
                    </span>
                  @endforeach
                </div>
              </td>
              <td class="text-end">
                <a href="{{ route('siswa.edit', $s->id) }}" class="btn btn-primary btn-sm py-1 px-2.5 rounded-pill shadow-xs d-inline-flex align-items-center gap-1" style="font-size: 0.75rem;">
                  <x-heroicon-o-pencil-square class="heroicon-sm" /> Lengkapi
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center text-muted py-4">
                <div class="py-2">
                  <x-heroicon-o-check-circle class="heroicon-lg text-success mx-auto" style="width: 36px; height: 36px;" />
                  <h6 class="fw-bold mt-2 text-dark">Data Siswa Lengkap!</h6>
                  <p class="text-muted small mb-0">
                    Tidak ditemukan siswa dengan data atau berkas yang kurang pada filter ini.
                  </p>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- Pagination Footer -->
  @if($incompleteStudents->hasPages())
    <div class="card-footer bg-white border-top py-2 px-3 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
      <div class="small text-muted" style="font-size: 0.8rem;">
        Menampilkan <strong>{{ $incompleteStudents->firstItem() }}</strong> s/d <strong>{{ $incompleteStudents->lastItem() }}</strong> dari <strong>{{ $incompleteStudents->total() }}</strong> siswa belum lengkap
      </div>
      <div>
        {{ $incompleteStudents->links('pagination::bootstrap-5') }}
      </div>
    </div>
  @endif
</div>
@endsection