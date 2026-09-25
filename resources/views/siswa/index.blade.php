@extends('layouts.app')

@section('title', 'Data Siswa')
@section('page_title', 'Data Siswa')
@section('page_subtitle', 'Manajemen profil, data keluarga, ekonomi, dan kelengkapan dokumen siswa')

@section('page_actions')
  <a href="{{ route('siswa.create') }}" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1">
    <x-heroicon-o-plus class="heroicon-sm" /> Tambah Siswa
  </a>
  <a href="{{ route('siswa.konversi') }}" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-1">
    <x-heroicon-o-calculator class="heroicon-sm" /> Konversi IPP
  </a>
@endsection

@section('content')
<div class="card">
  <div class="card-header bg-white">
    <form method="GET" action="{{ route('siswa.index') }}" class="row g-2 align-items-center w-100">
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
        @if(request()->anyFilled(['kelas_id', 'status', 'search']))
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
                <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.7rem; padding: 2px 6px;">{{ $s->kelas->nama_kelas ?? 'Belum ada' }}</span>
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
@endsection