@extends('layouts.app')

@section('title', 'Kelulusan & Data Alumni')
@section('page_title', 'Kelulusan Siswa & Data Alumni')
@section('page_subtitle', 'Proses kelulusan kelas XII dan penelusuran riwayat alumni sekolah')

@section('content')
<div class="card mb-4">
  <div class="card-header bg-white p-2">
    <ul class="nav nav-tabs card-header-tabs" role="tablist">
      <li class="nav-item">
        <a class="nav-link {{ request('tab', 'alumni') === 'alumni' ? 'active' : '' }}" href="{{ route('kelulusan.index', ['tab' => 'alumni']) }}">
          <x-heroicon-o-user-group class="heroicon-sm me-1" /> Data Alumni
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ request('tab') === 'proses' ? 'active' : '' }}" href="{{ route('kelulusan.index', ['tab' => 'proses']) }}">
          <x-heroicon-o-academic-cap class="heroicon-sm me-1" /> Proses Kelulusan Kelas XII
        </a>
      </li>
    </ul>
  </div>

  <div class="card-body">
    @if(request('tab', 'alumni') === 'alumni')
      <!-- Tab Alumni -->
      <form method="GET" action="{{ route('kelulusan.index') }}" class="row g-2 align-items-center mb-4">
        <input type="hidden" name="tab" value="alumni">
        <div class="col-md-3">
          <select name="filter_tahun_lulus" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="">-- Semua Tahun Lulus --</option>
            @foreach($tahunList as $t)
              <option value="{{ $t }}" {{ request('filter_tahun_lulus') == $t ? 'selected' : '' }}>Lulusan {{ $t }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-4">
          <div class="input-group input-group-sm">
            <input type="text" name="search" class="form-control" placeholder="Cari nama, NIS, atau no ijazah..." value="{{ request('search') }}">
            <button class="btn btn-primary d-inline-flex align-items-center gap-1" type="submit"><x-heroicon-o-magnifying-glass class="heroicon-sm" /> Cari</button>
          </div>
        </div>
      </form>

      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead>
            <tr>
              <th style="width: 50px;">No</th>
              <th>NIS</th>
              <th>Nama Siswa</th>
              <th>JK</th>
              <th>Tahun Lulus</th>
              <th>Tanggal Lulus</th>
              <th>No Ijazah</th>
              <th class="text-end" style="width: 100px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($alumniList as $idx => $alm)
              <tr>
                <td>{{ $alumniList->firstItem() + $idx }}</td>
                <td>{{ $alm->nis ?: '-' }}</td>
                <td class="fw-bold">{{ $alm->nama_siswa }}</td>
                <td><span class="badge bg-secondary-subtle text-secondary">{{ $alm->jk }}</span></td>
                <td><span class="badge bg-success-subtle text-success">{{ $alm->tahun_lulus ?: '-' }}</span></td>
                <td>{{ $alm->tanggal_lulus ? (\Carbon\Carbon::canBeCreatedFromFormat($alm->tanggal_lulus, 'Y-m-d') || strtotime($alm->tanggal_lulus) ? \Carbon\Carbon::parse($alm->tanggal_lulus)->translatedFormat('d F Y') : $alm->tanggal_lulus) : '-' }}</td>
                <td>{{ $alm->no_ijazah ?: '-' }}</td>
                <td class="text-end">
                  <form method="POST" action="{{ route('kelulusan.cancel', $alm->id) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-warning btn-sm d-inline-flex align-items-center gap-1" data-confirm-title="Batalkan Kelulusan" data-confirm="Batalkan status kelulusan siswa ini dan kembalikan ke daftar siswa aktif?" data-confirm-icon="warning" data-confirm-btn="Ya, Batalkan!" title="Batalkan Kelulusan">
                      <x-heroicon-o-arrow-path class="heroicon-sm" /> Batal
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="text-center text-muted py-5">Belum ada data alumni tercatat</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if($alumniList->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3">
          <div class="small text-muted">Menampilkan {{ $alumniList->firstItem() }} s/d {{ $alumniList->lastItem() }} dari {{ $alumniList->total() }} alumni</div>
          <div>{{ $alumniList->links('pagination::bootstrap-5') }}</div>
        </div>
      @endif

    @else
      <!-- Tab Proses Kelulusan -->
      <form method="GET" action="{{ route('kelulusan.index') }}" class="row g-3 mb-4">
        <input type="hidden" name="tab" value="proses">
        <div class="col-md-5">
          <label class="form-label small fw-semibold">Pilih Kelas XII Calon Lulusan</label>
          <select name="kelas_id" class="form-select" onchange="this.form.submit()">
            <option value="">-- Pilih Kelas XII --</option>
            @foreach($kelasXiiList as $k)
              <option value="{{ $k->id }}" {{ $selectedKelasId == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
            @endforeach
          </select>
        </div>
      </form>

      @if($selectedKelasId && $siswaCalon->count() > 0)
        <form method="POST" action="{{ route('kelulusan.process') }}">
          @csrf
          <div class="row g-3 mb-3 p-3 bg-light rounded border">
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Tahun Kelulusan <span class="text-danger">*</span></label>
              <input type="text" name="tahun_lulus" class="form-control" value="{{ $activeTa }}" required>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Tanggal Kelulusan (SK Kelulusan) <span class="text-danger">*</span></label>
              <input type="date" name="tanggal_lulus" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>
          </div>

          <div class="table-responsive mb-3">
            <table class="table table-hover align-middle mb-0">
              <thead>
                <tr>
                  <th style="width: 40px;" class="text-center">
                    <input type="checkbox" class="form-check-input" onclick="$('.check-calon').prop('checked', this.checked)" checked>
                  </th>
                  <th>NIS</th>
                  <th>Nama Siswa</th>
                  <th>JK</th>
                  <th>Kategori IPP</th>
                </tr>
              </thead>
              <tbody>
                @foreach($siswaCalon as $s)
                  <tr>
                    <td class="text-center">
                      <input type="checkbox" name="siswa_ids[]" value="{{ $s->id }}" class="form-check-input check-calon" checked>
                    </td>
                    <td>{{ $s->nis ?: '-' }}</td>
                    <td class="fw-bold">{{ $s->nama_siswa }}</td>
                    <td>{{ $s->jk }}</td>
                    <td><span class="badge bg-success-subtle text-success">{{ $s->kategori_ipp ?: '-' }}</span></td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <div class="text-end">
            <button type="submit" class="btn btn-primary px-4 py-2 d-inline-flex align-items-center gap-1 shadow-sm" data-confirm-title="Tetapkan Kelulusan Siswa" data-confirm="Tetapkan status LULUS dan arsipkan ke daftar alumni untuk seluruh siswa yang dipilih?" data-confirm-icon="question" data-confirm-btn="Ya, Tetapkan Lulus!">
              <x-heroicon-o-academic-cap class="heroicon-sm me-1" /> Proses Kelulusan Siswa Terpilih
            </button>
          </div>
        </form>
      @elseif($selectedKelasId)
        <div class="text-center text-muted py-5">Tidak ada siswa aktif di kelas ini</div>
      @endif
    @endif
  </div>
</div>
@endsection