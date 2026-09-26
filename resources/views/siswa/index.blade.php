@extends('layouts.app')

@section('title', 'Data Siswa')
@section('page_title', 'Data Siswa')
@section('page_subtitle', 'Manajemen profil, data keluarga, ekonomi, dan kelengkapan dokumen siswa')

@section('page_actions')
  <a href="{{ route('siswa.create') }}" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1 shadow-xs">
    <x-heroicon-o-plus class="heroicon-sm" /> Tambah Siswa
  </a>
  <a href="{{ route('siswa.template') }}" class="btn btn-outline-success btn-sm d-inline-flex align-items-center gap-1 shadow-xs" title="Download Template Excel">
    <x-heroicon-o-arrow-down-tray class="heroicon-sm" /> Template
  </a>
  <button type="button" class="btn btn-success btn-sm d-inline-flex align-items-center gap-1 shadow-xs" data-bs-toggle="modal" data-bs-target="#importExcelModal" title="Import Data Siswa dari Excel">
    <x-heroicon-o-arrow-up-tray class="heroicon-sm" /> Import
  </button>
  <a href="{{ route('siswa.export', request()->all()) }}" class="btn btn-outline-success btn-sm d-inline-flex align-items-center gap-1 shadow-xs" title="Export Data ke Excel">
    <x-heroicon-o-table-cells class="heroicon-sm" /> Export
  </a>
  <a href="{{ route('siswa.konversi') }}" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-1 shadow-xs">
    <x-heroicon-o-calculator class="heroicon-sm" /> Konversi IPP
  </a>
@endsection

@section('content')
@if(session('import_result'))
  @php $imp = session('import_result'); @endphp
  <div class="card border-0 shadow-sm rounded-3 mb-4 overflow-hidden border-top border-4 {{ ($imp['failed'] ?? 0) > 0 ? 'border-danger' : 'border-success' }}">
    <div class="card-body p-4">
      <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-3 pb-3 border-bottom">
        <div>
          <h5 class="fw-bold mb-1 text-dark d-flex align-items-center gap-2">
            @if(($imp['failed'] ?? 0) > 0)
              <x-heroicon-o-exclamation-circle class="heroicon text-danger" /> Laporan Hasil Impor File Excel
            @else
              <x-heroicon-o-check-circle class="heroicon text-success" /> Impor File Excel Selesai
            @endif
          </h5>
          <div class="text-muted small">
            Total <strong>{{ $imp['total_rows'] ?? 0 }}</strong> baris data diproses dari file Excel.
          </div>
        </div>
        <div class="d-flex flex-wrap gap-2">
          <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 fs-7 fw-semibold">
            ✓ Baru: {{ $imp['imported'] ?? 0 }}
          </span>
          <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 fs-7 fw-semibold">
            ↻ Diperbarui: {{ $imp['updated'] ?? 0 }}
          </span>
          <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-2 fs-7 fw-semibold">
            ⊘ Dilewati: {{ $imp['skipped'] ?? 0 }}
          </span>
          @if(($imp['failed'] ?? 0) > 0)
            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 fs-7 fw-semibold">
              ✕ Gagal: {{ $imp['failed'] ?? 0 }}
            </span>
          @endif
        </div>
      </div>

      {{-- Rincian Kesalahan Input --}}
      @if(!empty($imp['errors']))
        <div class="mb-3">
          <div class="fw-bold text-danger mb-2 small d-flex align-items-center gap-1.5">
            <x-heroicon-o-x-circle class="heroicon-sm text-danger" />
            Daftar Kesalahan Input pada File Excel ({{ count($imp['errors']) }} baris perlu diperbaiki):
          </div>
          <div class="p-3 bg-danger-subtle rounded-3 border border-danger-subtle" style="max-height: 260px; overflow-y: auto;">
            <ul class="mb-0 ps-3 font-monospace small text-danger-emphasis">
              @foreach($imp['errors'] as $err)
                <li class="mb-1">{{ $err }}</li>
              @endforeach
            </ul>
          </div>
          <div class="form-text small text-muted mt-1">
            * Baris dengan kesalahan di atas tidak dimasukkan ke sistem untuk mencegah data cacat atau ganda. Silakan perbaiki data pada baris tersebut di file Excel Anda lalu unggah ulang.
          </div>
        </div>
      @endif

      {{-- Rincian Duplikat / Skipped --}}
      @if(!empty($imp['skipped_info']))
        <div>
          <button class="btn btn-sm btn-link text-decoration-none p-0 text-secondary small d-flex align-items-center gap-1" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSkipped">
            <x-heroicon-o-chevron-down class="heroicon-sm" /> Lihat {{ count($imp['skipped_info']) }} catatan data yang dilewati (duplikat)
          </button>
          <div class="collapse mt-2" id="collapseSkipped">
            <div class="p-3 bg-light rounded-3 border small font-monospace text-muted" style="max-height: 200px; overflow-y: auto;">
              <ul class="mb-0 ps-3">
                @foreach($imp['skipped_info'] as $skip)
                  <li class="mb-1">{{ $skip }}</li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      @endif
    </div>
  </div>
@endif

<div class="card">
  <div class="card-header bg-white">
    @if(request()->filled('tahun_ajaran'))
      <div class="alert alert-info py-2 px-3 mb-2 small d-flex justify-content-between align-items-center border-0 bg-info-subtle text-info-emphasis">
        <div class="d-inline-flex align-items-center gap-1">
          <x-heroicon-o-calendar class="heroicon-sm" /> 
          <span>Memfilter data siswa pada periode: <strong>TA {{ request('tahun_ajaran') }} {{ request('semester') ? '('.request('semester').')' : '' }}</strong></span>
        </div>
        <a href="{{ route('siswa.index') }}" class="btn btn-outline-info btn-sm py-0 px-2 text-decoration-none bg-white">Hapus Filter Periode</a>
      </div>
    @endif
    <form method="GET" action="{{ route('siswa.index') }}" class="row g-2 align-items-center w-100">
      @if(request()->filled('tahun_ajaran'))
        <input type="hidden" name="tahun_ajaran" value="{{ request('tahun_ajaran') }}">
      @endif
      @if(request()->filled('semester'))
        <input type="hidden" name="semester" value="{{ request('semester') }}">
      @endif
      <div class="col-md-3">
        <select name="kelas_id" class="form-select form-select-sm" onchange="this.form.submit()">
          <option value="">-- Semua Kelas --</option>
          @foreach($kelasList as $k)
            <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
              {{ $k->nama_kelas }} ({{ $k->tingkat }})
            </option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
          <option value="Aktif" {{ request('status', 'Aktif') == 'Aktif' ? 'selected' : '' }}>Siswa Aktif</option>
          <option value="Lulus" {{ request('status') == 'Lulus' ? 'selected' : '' }}>Siswa Lulus / Alumni</option>
          <option value="Pindah" {{ request('status') == 'Pindah' ? 'selected' : '' }}>Siswa Pindah</option>
          <option value="Semua" {{ request('status') == 'Semua' ? 'selected' : '' }}>Semua Status</option>
        </select>
      </div>
      <div class="col-md-4">
        <div class="input-group input-group-sm">
          <input type="text" name="search" class="form-control" placeholder="Cari nama, NIS, atau NISN..." value="{{ request('search') }}">
          <button class="btn btn-primary d-inline-flex align-items-center gap-1" type="submit">
            <x-heroicon-o-magnifying-glass class="heroicon-sm" /> Cari
          </button>
        </div>
      </div>
      <div class="col-md-2 text-md-end">
        @if(request()->anyFilled(['kelas_id', 'status', 'search', 'tahun_ajaran', 'semester']))
          <a href="{{ route('siswa.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
        @endif
      </div>
    </form>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr>
            <th style="width: 50px;">No</th>
            <th>Foto</th>
            <th>NIS / NISN</th>
            <th>Nama Siswa</th>
            <th>JK</th>
            <th>Kelas</th>
            <th>Kategori IPP</th>
            <th>Status</th>
            <th class="text-end" style="width: 140px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($siswa as $idx => $s)
            <tr>
              <td class="text-muted small">{{ $siswa->firstItem() + $idx }}</td>
              <td>
                @if($s->foto_url)
                  <img src="{{ $s->foto_url }}" alt="Foto" width="28" height="28" class="rounded-circle object-fit-cover shadow-xs border border-1 border-light">
                @else
                  <div class="rounded-circle bg-light text-muted d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                    <x-heroicon-o-user class="heroicon-sm text-secondary" style="width: 14px !important; height: 14px !important;" />
                  </div>
                @endif
              </td>
              <td class="lh-sm">
                <span class="fw-semibold text-dark">{{ $s->nis ?: '-' }}</span>
                <div class="text-muted" style="font-size: 0.72rem;">NISN: {{ $s->nisn ?: '-' }}</div>
              </td>
              <td class="lh-sm">
                <a href="{{ route('siswa.show', $s->id) }}" class="fw-bold text-decoration-none text-dark hover-primary">
                  {{ $s->nama_siswa }}
                </a>
                <div class="text-muted" style="font-size: 0.72rem;">{{ $s->tempat_lahir ? $s->tempat_lahir . ', ' : '' }}{{ $s->tanggal_lahir ? (\Carbon\Carbon::canBeCreatedFromFormat($s->tanggal_lahir, 'Y-m-d') || strtotime($s->tanggal_lahir) ? \Carbon\Carbon::parse($s->tanggal_lahir)->translatedFormat('d F Y') : $s->tanggal_lahir) : '-' }}</div>
              </td>
              <td>
                <span class="badge {{ $s->jk == 'L' ? 'bg-primary-subtle text-primary' : 'bg-danger-subtle text-danger' }}" style="font-size: 0.7rem; padding: 2px 6px;">
                  {{ $s->jk }}
                </span>
              </td>
              <td>
                <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.7rem; padding: 2px 6px;">{{ $s->getKelasNamaForPeriod(request('tahun_ajaran'), request('semester')) }}</span>
              </td>
              <td class="lh-sm">
                @if($s->kategori_ipp)
                  <span class="badge bg-success-subtle text-success" style="font-size: 0.7rem; padding: 2px 6px;">{{ $s->kategori_ipp }}</span>
                  @if($s->nominal_ipp)
                    <div class="text-muted" style="font-size: 0.72rem;">Rp {{ number_format($s->nominal_ipp) }}</div>
                  @endif
                @else
                  <span class="badge bg-light text-muted border" style="font-size: 0.68rem; padding: 1.5px 5px;">Belum dihitung</span>
                @endif
              </td>
              <td>
                <span class="badge {{ $s->status == 'Aktif' || empty($s->status) ? 'bg-success' : ($s->status == 'Lulus' ? 'bg-info' : 'bg-warning') }}" style="font-size: 0.7rem; padding: 2px 6px;">
                  {{ $s->status ?: 'Aktif' }}
                </span>
              </td>
              <td class="text-end">
                <div class="btn-group btn-group-sm">
                  <a href="{{ route('siswa.show', $s->id) }}" class="btn btn-outline-info py-1 px-1.5" title="Detail Siswa">
                    <x-heroicon-o-eye class="heroicon-sm" />
                  </a>
                  <a href="{{ route('siswa.edit', $s->id) }}" class="btn btn-outline-primary py-1 px-1.5" title="Edit Siswa">
                    <x-heroicon-o-pencil-square class="heroicon-sm" />
                  </a>
                  <form method="POST" action="{{ route('siswa.destroy', $s->id) }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-outline-danger py-1 px-1.5 btn-delete-confirm" title="Hapus Siswa">
                      <x-heroicon-o-trash class="heroicon-sm" />
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9" class="text-center text-muted py-5">
                <x-heroicon-o-inbox class="heroicon-lg d-block mx-auto mb-2 text-muted" style="width: 2.5rem; height: 2.5rem;" />
                Tidak ada data siswa ditemukan
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if($siswa->hasPages())
    <div class="card-footer bg-white border-top d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 py-3">
      <div class="small text-muted text-center text-sm-start">
        Menampilkan <strong>{{ $siswa->firstItem() }}</strong> s/d <strong>{{ $siswa->lastItem() }}</strong> dari <strong>{{ $siswa->total() }}</strong> data
      </div>
      <div>
        {{ $siswa->links('pagination::bootstrap-5') }}
      </div>
    </div>
  @endif
</div>

@include('siswa.partials.import-modal')
@endsection