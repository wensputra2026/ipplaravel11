@extends('layouts.app')

@section('title', 'Kenaikan Kelas Siswa')
@section('page_title', 'Proses Kenaikan Kelas Siswa')
@section('page_subtitle', 'Pengelolaan kenaikan jenjang kelas rombel X ke XI, dan XI ke XII secara massal')

@section('content')
<div class="row g-4">
  <!-- Selection Card -->
  <div class="col-12">
    <div class="card">
      <div class="card-header bg-white">
        <div class="fw-bold d-inline-flex align-items-center"><x-heroicon-o-funnel class="heroicon-sm text-primary me-2" /> Langkah 1: Pilih Kelas Asal</div>
      </div>
      <div class="card-body">
        <form method="GET" action="{{ route('kenaikankelas.index') }}" class="row g-3 align-items-end">
          <div class="col-md-5">
            <label class="form-label small fw-semibold">Pilih Kelas Asal Siswa</label>
            <select name="source_kelas_id" class="form-select" onchange="this.form.submit()">
              <option value="">-- Pilih Kelas Asal --</option>
              @foreach($kelasList as $k)
                <option value="{{ $k->id }}" {{ $sourceKelasId == $k->id ? 'selected' : '' }}>
                  {{ $k->nama_kelas }} (Tingkat {{ $k->tingkat }})
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-7">
            @if($sourceKelas)
              <div class="alert alert-info py-2 px-3 mb-0 small d-inline-flex align-items-center">
                <x-heroicon-o-information-circle class="heroicon-sm me-1" /> Kelas Asal: <b>{{ $sourceKelas->nama_kelas }}</b> (Tingkat {{ $sourceKelas->tingkat }}). Ditemukan <b>{{ $siswaList->count() }}</b> siswa aktif.
              </div>
            @endif
          </div>
        </form>
      </div>
    </div>
  </div>

  @if($sourceKelas && $siswaList->count() > 0)
    <!-- Step 2: Target & Action Form -->
    <div class="col-12">
      <form method="POST" action="{{ route('kenaikankelas.process') }}">
        @csrf
        <input type="hidden" name="source_kelas_id" value="{{ $sourceKelas->id }}">

        <div class="card mb-4">
          <div class="card-header bg-white">
            <div class="fw-bold d-inline-flex align-items-center gap-1.5"><x-heroicon-o-arrow-right-circle class="heroicon text-primary me-1" /> Langkah 2: Tentukan Rombel & Periode Tujuan</div>
          </div>
          <div class="card-body">
            <div class="row g-3">
              <div class="col-md-4">
                <label class="form-label small fw-semibold">Pilih Kelas Tujuan <span class="text-danger">*</span></label>
                <select name="target_kelas_id" class="form-select" required>
                  <option value="">-- Pilih Rombel Tujuan --</option>
                  @foreach($kelasList as $k)
                    @if($k->id != $sourceKelas->id)
                      <option value="{{ $k->id }}">{{ $k->nama_kelas }} (Tingkat {{ $k->tingkat }})</option>
                    @endif
                  @endforeach
                </select>
              </div>
              <div class="col-md-4">
                <label class="form-label small fw-semibold">Tahun Ajaran Baru <span class="text-danger">*</span></label>
                <input type="text" name="target_tahun_ajaran" class="form-control" value="{{ $activeTa }}" required>
              </div>
              <div class="col-md-4">
                <label class="form-label small fw-semibold">Semester Baru <span class="text-danger">*</span></label>
                <select name="target_semester" class="form-select" required>
                  <option value="Ganjil" {{ $activeSem == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                  <option value="Genap" {{ $activeSem == 'Genap' ? 'selected' : '' }}>Genap</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <div class="fw-bold d-inline-flex align-items-center"><x-heroicon-o-user-group class="heroicon-sm text-primary me-2" /> Langkah 3: Status Siswa (Daftar Siswa {{ $sourceKelas->nama_kelas }})</div>
            <div class="btn-group btn-group-sm">
              <button type="button" class="btn btn-outline-success" onclick="$('.action-naik').prop('checked', true)">Semua Naik</button>
              <button type="button" class="btn btn-outline-secondary" onclick="$('.action-tinggal').prop('checked', true)">Semua Tinggal</button>
            </div>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead>
                  <tr>
                    <th style="width: 50px;">No</th>
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th>JK</th>
                    <th>Kategori IPP</th>
                    <th class="text-center" style="width: 250px;">Aksi Kenaikan</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($siswaList as $idx => $s)
                    <tr>
                      <td>{{ $idx + 1 }}</td>
                      <td>{{ $s->nis ?: '-' }}</td>
                      <td class="fw-bold">{{ $s->nama_siswa }}</td>
                      <td><span class="badge bg-secondary-subtle text-secondary">{{ $s->jk }}</span></td>
                      <td><span class="badge bg-success-subtle text-success">{{ $s->kategori_ipp ?: '-' }}</span></td>
                      <td class="text-center">
                        <div class="d-inline-flex gap-3">
                          <label class="form-check-label text-success fw-semibold small">
                            <input type="radio" name="siswa_action[{{ $s->id }}]" value="naik" class="form-check-input action-naik" checked> Naik
                          </label>
                          <label class="form-check-label text-warning fw-semibold small">
                            <input type="radio" name="siswa_action[{{ $s->id }}]" value="tinggal" class="form-check-input action-tinggal"> Tinggal
                          </label>
                          <label class="form-check-label text-secondary small">
                            <input type="radio" name="siswa_action[{{ $s->id }}]" value="skip" class="form-check-input"> Lewati
                          </label>
                        </div>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer bg-light text-end">
            <button type="submit" class="btn btn-primary px-4 py-2 d-inline-flex align-items-center gap-1 shadow-sm" data-confirm-title="Proses Kenaikan Kelas" data-confirm="Apakah Anda yakin ingin memproses kenaikan kelas untuk seluruh siswa yang dipilih?" data-confirm-icon="question" data-confirm-btn="Ya, Proses Kenaikan!">
              <x-heroicon-o-check-circle class="heroicon-sm me-1" /> Proses Kenaikan Kelas Sekarang
            </button>
          </div>
        </div>
      </form>
    </div>
  @endif
</div>
@endsection