@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', $isWali
    ? 'Data ringkasan kelas Anda: ' . (Auth::user()->kelas->nama_kelas ?? 'Kelas Saya') . ' — Periode: TA ' . $selectedTa . ' (' . $selectedSem . ')'
    : 'Ringkasan data siswa dan statistik akademik — Periode Berjalan: TA ' . $selectedTa . ' (' . $selectedSem . ')'
)

@section('page_actions')
  <form method="GET" action="{{ route('dashboard') }}" class="d-flex align-items-center gap-2">
    <div class="input-group input-group-sm">
      <span class="input-group-text bg-white border-end-0 text-muted">
        <x-heroicon-o-calendar class="heroicon-sm text-primary" />
      </span>
      <select name="ta" class="form-select form-select-sm border-start-0 ps-1" onchange="this.form.submit()" title="Pilih Tahun Ajaran">
        @foreach($availableTaList as $ta)
          <option value="{{ $ta }}" {{ $selectedTa == $ta ? 'selected' : '' }}>
            TA {{ $ta }} {{ $ta == $activeTa ? '(Aktif)' : '' }}
          </option>
        @endforeach
      </select>
      <select name="semester" class="form-select form-select-sm" onchange="this.form.submit()" title="Pilih Semester">
        @foreach($availableSemList as $sem)
          <option value="{{ $sem }}" {{ $selectedSem == $sem ? 'selected' : '' }}>
            {{ $sem }} {{ ($selectedTa == $activeTa && $sem == $activeSem) ? '(Aktif)' : '' }}
          </option>
        @endforeach
      </select>
    </div>
    @if($selectedTa != $activeTa || $selectedSem != $activeSem)
      <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm" title="Kembali ke Periode Berjalan">
        Reset
      </a>
    @endif
    <a href="{{ route('siswa.index') }}" class="btn btn-primary btn-sm px-3 shadow-sm d-inline-flex align-items-center gap-1">
      <x-heroicon-o-users class="heroicon-sm" /> Data Siswa
    </a>
  </form>
@endsection

@section('content')
@if($selectedTa != $activeTa || $selectedSem != $activeSem)
  <div class="alert alert-info py-2 px-3 mb-3 d-flex align-items-center justify-content-between rounded-3 border-0 shadow-xs">
    <div class="small d-flex align-items-center gap-1.5">
      <x-heroicon-o-information-circle class="heroicon-sm text-info" />
      Menampilkan data arsip periode <strong>Tahun Ajaran {{ $selectedTa }} - Semester {{ $selectedSem }}</strong>. (Periode Berjalan: {{ $activeTa }} {{ $activeSem }})
    </div>
    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size: 0.75rem;">Kembali ke Periode Berjalan</a>
  </div>
@endif

@if($totalSiswa === 0)
  <div class="alert alert-warning py-3 px-4 mb-4 rounded-3 border-0 shadow-sm d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
    <div class="d-flex align-items-center gap-3">
      <div class="p-2 rounded-circle bg-warning-subtle text-warning-emphasis">
        <x-heroicon-o-exclamation-triangle style="width: 24px; height: 24px;" />
      </div>
      <div>
        <div class="fw-bold text-dark">Belum ada data siswa aktif untuk Tahun Ajaran {{ $selectedTa }} (Semester {{ $selectedSem }})</div>
        <div class="text-muted small">Data dashboard difilter ketat per tahun ajaran dan semester agar data antar periode tidak bercampur.</div>
      </div>
    </div>
    <div class="d-flex gap-2">
      <a href="{{ route('siswa.index') }}" class="btn btn-success btn-sm d-inline-flex align-items-center gap-1 shadow-xs">
        <x-heroicon-o-arrow-up-tray class="heroicon-sm" /> Impor Data Siswa
      </a>
      @if(!$isWali)
        <a href="{{ route('settings.index') }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
          <x-heroicon-o-cog-6-tooth class="heroicon-sm" /> Pengaturan Periode
        </a>
      @endif
    </div>
  </div>
@endif
<!-- Top 4 Metric Info-Boxes -->
<div class="row g-3 mb-4">
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm rounded-3 h-100 p-3">
      <div class="d-flex align-items-center">
        <div class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3" style="width: 52px; height: 52px;">
          <x-heroicon-o-academic-cap style="width:28px;height:28px;" />
        </div>
        <div>
          <div class="text-secondary small fw-medium">Total Siswa</div>
          <div class="fs-4 fw-bold text-dark">{{ number_format($totalSiswa, 0, ',', '.') }}</div>
          <div class="text-success small d-flex align-items-center gap-1" style="font-size: 0.75rem;"><x-heroicon-o-check-circle style="width:14px;height:14px;" />Siswa aktif</div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm rounded-3 h-100 p-3">
      <div class="d-flex align-items-center">
        <div class="rounded-3 bg-warning-subtle text-warning-emphasis d-flex align-items-center justify-content-center me-3" style="width: 52px; height: 52px;">
          <x-heroicon-o-briefcase style="width:28px;height:28px;" />
        </div>
        <div>
          <div class="text-secondary small fw-medium">Total GTK</div>
          <div class="fs-4 fw-bold text-dark">{{ number_format($totalGtk, 0, ',', '.') }}</div>
          <div class="text-secondary small" style="font-size: 0.75rem;">Guru & Tenaga Kependidikan</div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm rounded-3 h-100 p-3">
      <div class="d-flex align-items-center">
        <div class="rounded-3 bg-info-subtle text-info d-flex align-items-center justify-content-center me-3" style="width: 52px; height: 52px;">
          <x-heroicon-o-rectangle-stack style="width:28px;height:28px;" />
        </div>
        <div>
          <div class="text-secondary small fw-medium">{{ $isWali ? 'Kelas Saya' : 'Total Kelas' }}</div>
          <div class="fs-4 fw-bold text-dark">{{ number_format($totalKelas, 0, ',', '.') }}</div>
          <div class="text-secondary small" style="font-size: 0.75rem;">{{ $isWali ? 'Kelas yang diampu' : 'Rombongan belajar aktif' }}</div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm rounded-3 h-100 p-3">
      <div class="d-flex align-items-center">
        <div class="rounded-3 bg-secondary-subtle text-secondary-emphasis d-flex align-items-center justify-content-center me-3" style="width: 52px; height: 52px;">
          <x-heroicon-o-user-group style="width:28px;height:28px;" />
        </div>
        <div>
          <div class="text-secondary small fw-medium">Pengguna Sistem</div>
          <div class="fs-4 fw-bold text-dark">{{ number_format($totalUsers, 0, ',', '.') }}</div>
          <div class="text-secondary small" style="font-size: 0.75rem;">Akun admin & wali kelas</div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- 4 Doughnut/Pie Charts in 1 Row -->
<div class="row g-3 mb-4">
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm rounded-3 h-100">
      <div class="card-header bg-transparent border-0 pt-3 pb-0">
        <h6 class="card-title fw-bold mb-0 text-dark d-flex align-items-center gap-1"><x-heroicon-o-user-circle class="heroicon-sm text-primary" /> Jenis Kelamin</h6>
      </div>
      <div class="card-body d-flex flex-column justify-content-center align-items-center" style="min-height: 240px;">
        <canvas id="chartJK" style="max-height: 200px; width: 100%;"></canvas>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm rounded-3 h-100">
      <div class="card-header bg-transparent border-0 pt-3 pb-0">
        <h6 class="card-title fw-bold mb-0 text-dark d-flex align-items-center gap-1"><x-heroicon-o-heart class="heroicon-sm text-danger" /> Agama</h6>
      </div>
      <div class="card-body d-flex flex-column justify-content-center align-items-center" style="min-height: 240px;">
        <canvas id="chartAgama" style="max-height: 200px; width: 100%;"></canvas>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm rounded-3 h-100">
      <div class="card-header bg-transparent border-0 pt-3 pb-0">
        <h6 class="card-title fw-bold mb-0 text-dark d-flex align-items-center gap-1"><x-heroicon-o-tag class="heroicon-sm text-warning" /> Kategori Siswa</h6>
      </div>
      <div class="card-body d-flex flex-column justify-content-center align-items-center" style="min-height: 240px;">
        <canvas id="chartKategori" style="max-height: 200px; width: 100%;"></canvas>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm rounded-3 h-100">
      <div class="card-header bg-transparent border-0 pt-3 pb-0">
        <h6 class="card-title fw-bold mb-0 text-dark d-flex align-items-center gap-1"><x-heroicon-o-banknotes class="heroicon-sm text-success" /> Kategori IPP</h6>
      </div>
      <div class="card-body d-flex flex-column justify-content-center align-items-center" style="min-height: 240px;">
        <canvas id="chartIPP" style="max-height: 200px; width: 100%;"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- Kategorisasi Khusus Section (5 Horizontal Cards) -->
<div class="card border-0 shadow-sm rounded-3 mb-4">
  <div class="card-header bg-transparent border-bottom py-3">
    <div class="d-flex align-items-center justify-content-between">
      <h6 class="card-title fw-bold mb-0 text-dark">
        <x-heroicon-s-star class="heroicon text-warning" /> Kategorisasi Khusus
      </h6>
      <span class="badge bg-light text-secondary border">Afirmasi & Kebutuhan Khusus</span>
    </div>
  </div>
  <div class="card-body p-3">
    @php
      $kd = $stats['kategori_detail'] ?? [
        'panti_asuhan' => 0,
        'korban_bencana' => 0,
        'terlantar' => 0,
        'ortu_abk' => 0,
        'ortu_sakit_menahun' => 0
      ];
    @endphp
    <div class="row g-2 text-center">
      <div class="col-6 col-md">
        <div class="p-3 rounded-3 bg-danger-subtle border border-danger-subtle">
          <div class="text-secondary small fw-semibold">a. Anak Panti Asuhan</div>
          <div class="fs-3 fw-bold text-danger my-1">{{ (int)($kd['panti_asuhan'] ?? 0) }}</div>
          <div class="badge bg-danger-subtle text-danger" style="font-size: 0.72rem;">Kategori Afirmasi A</div>
        </div>
      </div>
      <div class="col-6 col-md">
        <div class="p-3 rounded-3 bg-warning-subtle border border-warning-subtle">
          <div class="text-secondary small fw-semibold">b. Anak Korban Bencana</div>
          <div class="fs-3 fw-bold text-warning-emphasis my-1">{{ (int)($kd['korban_bencana'] ?? 0) }}</div>
          <div class="badge bg-warning-subtle text-warning-emphasis" style="font-size: 0.72rem;">Kategori Afirmasi B</div>
        </div>
      </div>
      <div class="col-6 col-md">
        <div class="p-3 rounded-3 bg-info-subtle border border-info-subtle">
          <div class="text-secondary small fw-semibold">c. Anak Terlantar</div>
          <div class="fs-3 fw-bold text-info my-1">{{ (int)($kd['terlantar'] ?? 0) }}</div>
          <div class="badge bg-info-subtle text-info" style="font-size: 0.72rem;">Kategori Afirmasi C</div>
        </div>
      </div>
      <div class="col-6 col-md">
        <div class="p-3 rounded-3 bg-primary-subtle border border-primary-subtle">
          <div class="text-secondary small fw-semibold">d. Anak Ortu ABK</div>
          <div class="fs-3 fw-bold text-primary my-1">{{ (int)($kd['ortu_abk'] ?? 0) }}</div>
          <div class="badge bg-primary-subtle text-primary" style="font-size: 0.72rem;">Berkebutuhan Khusus</div>
        </div>
      </div>
      <div class="col-12 col-md">
        <div class="p-3 rounded-3 bg-success-subtle border border-success-subtle">
          <div class="text-secondary small fw-semibold">e. Anak Ortu Sakit</div>
          <div class="fs-3 fw-bold text-success my-1">{{ (int)($kd['ortu_sakit_menahun'] ?? 0) }}</div>
          <div class="badge bg-success-subtle text-success" style="font-size: 0.72rem;">Sakit Menahun</div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- 2 Bar Charts (Pekerjaan & Penghasilan Orang Tua) -->
<div class="row g-3 mb-4">
  <div class="col-lg-6">
    <div class="card border-0 shadow-sm rounded-3 h-100">
      <div class="card-header bg-transparent border-bottom py-3">
        <h6 class="card-title fw-bold mb-0 text-dark">
          <x-heroicon-o-briefcase class="heroicon-sm text-primary me-1" /> Pekerjaan Orang Tua
        </h6>
      </div>
      <div class="card-body" style="min-height: 280px;">
        <canvas id="chartPekerjaan" style="height: 260px; width: 100%;"></canvas>
      </div>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="card border-0 shadow-sm rounded-3 h-100">
      <div class="card-header bg-transparent border-bottom py-3">
        <h6 class="card-title fw-bold mb-0 text-dark">
          <x-heroicon-o-banknotes class="heroicon-sm text-success me-1" /> Penghasilan Orang Tua
        </h6>
      </div>
      <div class="card-body" style="min-height: 280px;">
        <canvas id="chartPenghasilan" style="height: 260px; width: 100%;"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- Daftar Rincian Tanggungan Per Keluarga -->
<div class="card border-0 shadow-sm rounded-3 mb-4" x-data="{ searchTanggungan: '' }">
  <div class="card-header bg-transparent border-bottom py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div>
      <h6 class="card-title fw-bold mb-0 text-dark">
        <x-heroicon-o-user-group class="heroicon-sm text-primary me-1" /> Daftar Rincian Tanggungan Per Keluarga
      </h6>
      <div class="text-secondary small mt-0.5">Keluarga dengan &ge; 2 anak yang bersekolah di SMAN Benlutu</div>
    </div>
    <div class="d-flex align-items-center gap-2">
      <div class="input-group input-group-sm" style="width: 250px;">
        <span class="input-group-text bg-white border-end-0"><x-heroicon-o-magnifying-glass class="heroicon-sm text-muted" /></span>
        <input type="text" x-model="searchTanggungan" class="form-control border-start-0" placeholder="Cari nama keluarga / siswa...">
      </div>
      <span class="badge bg-light text-secondary border">Total: {{ count($tanggunganList ?? []) }}</span>
    </div>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive" style="max-height: 380px;">
      <table class="table table-hover align-middle mb-0">
        <thead class="sticky-top bg-light">
          <tr>
            <th class="text-center" style="width: 5%">No</th>
            <th>Kepala Keluarga (Ayah / Wali / Ibu)</th>
            <th class="text-center" style="width: 15%">Jml Tanggungan</th>
            <th>Siswa (Anak yang Bersekolah di SMAN Benlutu)</th>
          </tr>
        </thead>
        <tbody>
          @forelse($tanggunganList ?? [] as $index => $row)
            <tr x-show="!searchTanggungan || $el.innerText.toLowerCase().includes(searchTanggungan.toLowerCase())">
              <td class="text-center text-muted small">{{ $index + 1 }}</td>
              <td class="fw-semibold text-dark">{{ $row->kepala_keluarga }}</td>
              <td class="text-center">
                <span class="badge {{ $row->jml_tanggungan >= 2 ? 'bg-warning-subtle text-warning-emphasis border border-warning-subtle' : 'bg-secondary-subtle text-secondary' }} px-2 py-1">
                  {{ $row->jml_tanggungan }} Anak
                </span>
              </td>
              <td>
                @php
                  $anakArray = explode(', ', $row->anak_sekolah);
                @endphp
                <div class="d-flex flex-wrap gap-1">
                  @foreach($anakArray as $anak)
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle py-1 px-2 fw-normal">
                      <x-heroicon-o-user style="width:12px;height:12px;flex-shrink:0;" /> {{ $anak }}
                    </span>
                  @endforeach
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="text-center text-muted py-4">Tidak ada data tanggungan keluarga ditemukan.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Daftar Kelas (Periode Aktif) -->
<div class="card border-0 shadow-sm rounded-3 mb-4" x-data="{ searchKelas: '' }">
  <div class="card-header bg-transparent border-bottom py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div>
      <h6 class="card-title fw-bold mb-0 text-dark">
        <x-heroicon-o-rectangle-stack class="heroicon-sm text-primary me-1" />
        {{ $isWali ? 'Data Kelas Saya' : 'Daftar Kelas (Periode Aktif)' }}
      </h6>
      <div class="text-secondary small mt-0.5">
        @if($isWali)
          Kelas {{ Auth::user()->kelas->nama_kelas ?? '-' }} &bull; TA {{ $activeTa }} ({{ $activeSem }})
        @else
          Rombongan belajar tahun ajaran {{ $activeTa }}
        @endif
      </div>
    </div>
    <div class="d-flex align-items-center gap-2">
      @if(!$isWali)
      <div class="input-group input-group-sm" style="width: 220px;">
        <span class="input-group-text bg-white border-end-0"><x-heroicon-o-magnifying-glass class="heroicon-sm text-muted" /></span>
        <input type="text" x-model="searchKelas" class="form-control border-start-0" placeholder="Cari nama kelas / wali...">
      </div>
      @endif
      @if(Auth::user()->isAdmin())
        <a href="{{ route('kelas.index') }}" class="btn btn-outline-secondary btn-sm py-1 px-3">
          Kelola Kelas <x-heroicon-o-arrow-right class="heroicon-sm ms-1" />
        </a>
      @endif
    </div>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="bg-light">
          <tr>
            <th style="width: 60px;">ID</th>
            <th>Nama Kelas</th>
            <th>Tingkat</th>
            <th>Jurusan</th>
            <th class="text-center">Jumlah Siswa</th>
            <th>Wali Kelas</th>
          </tr>
        </thead>
        <tbody>
          @forelse($rekapKelas as $kls)
            <tr x-show="!searchKelas || $el.innerText.toLowerCase().includes(searchKelas.toLowerCase())">
              <td class="text-muted small">#{{ $kls->id }}</td>
              <td class="fw-semibold text-dark">{{ $kls->nama_kelas }}</td>
              <td><span class="badge bg-secondary-subtle text-secondary">{{ $kls->tingkat }}</span></td>
              <td class="text-secondary small">{{ $kls->jurusan ?: '-' }}</td>
              <td class="text-center">
                <span class="badge bg-primary-subtle text-primary px-2.5 py-1 fw-bold">{{ $kls->siswa_count }}</span>
              </td>
              <td class="text-dark fw-medium">
                {{ $waliByKelas[$kls->id] ?? 'Belum Ditentukan' }}
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center text-muted py-4">Tidak ada data kelas pada periode ini</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Recent Activity (Aktivitas Sistem) -->
<div class="card border-0 shadow-sm rounded-3" x-data="{ searchActivity: '' }">
  <div class="card-header bg-transparent border-bottom py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div>
      <h6 class="card-title fw-bold mb-0 text-dark">
        <x-heroicon-o-clock class="heroicon-sm text-primary me-1" /> Recent Activity (Aktivitas Sistem)
      </h6>
      <div class="text-secondary small mt-0.5">Catatan log aktivitas pengguna terkini</div>
    </div>
    <div class="d-flex align-items-center gap-2">
      <div class="input-group input-group-sm" style="width: 220px;">
        <span class="input-group-text bg-white border-end-0"><x-heroicon-o-magnifying-glass class="heroicon-sm text-muted" /></span>
        <input type="text" x-model="searchActivity" class="form-control border-start-0" placeholder="Filter log...">
      </div>
      @if(Auth::user()->isAdmin())
        <a href="{{ route('activity.index') }}" class="btn btn-outline-secondary btn-sm py-1 px-3">
          Semua Log <x-heroicon-o-arrow-right class="heroicon-sm ms-1" />
        </a>
      @endif
    </div>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="bg-light">
          <tr>
            <th style="width: 170px;">Waktu</th>
            <th>Pengguna</th>
            <th>Modul</th>
            <th>Aktivitas</th>
            <th>Keterangan</th>
          </tr>
        </thead>
        <tbody>
          @forelse($recentActivities as $act)
            @php
              $badgeClass = match(strtolower($act->activity_type)) {
                'login' => 'bg-success-subtle text-success',
                'logout' => 'bg-info-subtle text-info',
                'create', 'tambah' => 'bg-primary-subtle text-primary',
                'update', 'edit' => 'bg-warning-subtle text-warning-emphasis',
                'delete', 'hapus' => 'bg-danger-subtle text-danger',
                default => 'bg-secondary-subtle text-secondary'
              };
            @endphp
            <tr x-show="!searchActivity || $el.innerText.toLowerCase().includes(searchActivity.toLowerCase())">
              <td style="white-space: nowrap;">
                <div class="fw-medium text-dark small">{{ $act->created_at ? \Carbon\Carbon::parse($act->created_at)->diffForHumans() : '-' }}</div>
                <div class="text-muted" style="font-size: 0.72rem;">{{ $act->created_at ? \Carbon\Carbon::parse($act->created_at)->translatedFormat('d M Y, H:i') : '' }}</div>
              </td>
              <td class="fw-semibold text-dark">{{ $act->username }}</td>
              <td><span class="badge bg-light text-secondary border">{{ strtoupper($act->module) }}</span></td>
              <td><span class="badge {{ $badgeClass }}">{{ ucfirst($act->activity_type) }}</span></td>
              <td class="text-truncate text-secondary" style="max-width: 320px;">{{ $act->description }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center text-muted py-4">Belum ada catatan aktivitas sistem</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/plugins/chart.js/Chart.min.js') }}"></script>
<script>
  $(function () {
    const stats = @json($stats ?? []);
    if (!stats || $.isEmptyObject(stats)) return;

    // Helper chart rendering
    function renderDoughnut(canvasId, labels, data, colors) {
      const ctx = document.getElementById(canvasId);
      if (!ctx) return;
      new Chart(ctx.getContext('2d'), {
        type: 'doughnut',
        data: {
          labels: labels.length ? labels : ['Tidak Ada Data'],
          datasets: [{
            data: data.length ? data : [1],
            backgroundColor: data.length ? colors : ['#e2e8f0']
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          legend: {
            position: 'bottom',
            labels: { boxWidth: 12, fontSize: 11 }
          }
        }
      });
    }

    function renderMultiBar(canvasId, datasets) {
      const ctx = document.getElementById(canvasId);
      if (!ctx) return;

      const allLabels = new Set();
      datasets.forEach(d => {
        Object.keys(d.data_obj).forEach(l => {
          if (l) allLabels.add(l);
        });
      });
      const labels = Array.from(allLabels).slice(0, 8);

      const chartDatasets = datasets.map(d => ({
        label: d.label,
        backgroundColor: d.color,
        data: labels.map(l => d.data_obj[l] || 0)
      }));

      new Chart(ctx.getContext('2d'), {
        type: 'bar',
        data: {
          labels: labels,
          datasets: chartDatasets
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            yAxes: [{
              ticks: { beginAtZero: true, stepSize: 1 }
            }]
          }
        }
      });
    }

    // 1. Jenis Kelamin
    if (stats.jk) {
      const jkLabels = Object.keys(stats.jk).map(k => k === 'L' ? 'Laki-laki' : (k === 'P' ? 'Perempuan' : k));
      renderDoughnut('chartJK', jkLabels, Object.values(stats.jk), ['#0d6efd', '#d63384']);
    }

    // 2. Agama
    if (stats.agama) {
      renderDoughnut('chartAgama', Object.keys(stats.agama), Object.values(stats.agama), [
        '#198754', '#ffc107', '#0dcaf0', '#dc3545', '#6f42c1', '#adb5bd'
      ]);
    }

    // 3. Kategori Siswa
    if (stats.kategori) {
      renderDoughnut('chartKategori', Object.keys(stats.kategori), Object.values(stats.kategori), [
        '#ffc107', '#0dcaf0', '#0d6efd', '#adb5bd', '#6610f2'
      ]);
    }

    // 4. Kategori IPP
    if (stats.kategori_ipp) {
      renderDoughnut('chartIPP', Object.keys(stats.kategori_ipp), Object.values(stats.kategori_ipp), [
        '#0d6efd', '#198754', '#ffc107', '#0dcaf0', '#dc3545', '#6c757d'
      ]);
    }

    // 5. Pekerjaan Orang Tua
    if (stats.pekerjaan_ayah || stats.pekerjaan_ibu) {
      renderMultiBar('chartPekerjaan', [
        { label: 'Ayah', data_obj: stats.pekerjaan_ayah || {}, color: '#0d6efd' },
        { label: 'Ibu', data_obj: stats.pekerjaan_ibu || {}, color: '#d63384' }
      ]);
    }

    // 6. Penghasilan Orang Tua
    if (stats.penghasilan_ayah || stats.penghasilan_ibu) {
      renderMultiBar('chartPenghasilan', [
        { label: 'Ayah', data_obj: stats.penghasilan_ayah || {}, color: '#198754' },
        { label: 'Ibu', data_obj: stats.penghasilan_ibu || {}, color: '#ffc107' }
      ]);
    }
  });
</script>
@endpush