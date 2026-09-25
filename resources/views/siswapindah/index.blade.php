@extends('layouts.app')

@section('title', 'Pindah Kelas Siswa')
@section('page_title', 'Pindah Kelas Siswa')
@section('page_subtitle', 'Mutasi dan pemindahan rombel siswa antar kelas secara cepat')

@section('content')
<div class="row g-4">
  <div class="col-12">
    <div class="card">
      <div class="card-header bg-white">
        <div class="fw-bold d-inline-flex align-items-center"><x-heroicon-o-funnel class="heroicon-sm text-primary me-2" /> Pilih Kelas Asal Siswa</div>
      </div>
      <div class="card-body">
        <form method="GET" action="{{ route('siswapindah.index') }}" class="row g-3 align-items-end">
          <div class="col-md-5">
            <select name="source_kelas_id" class="form-select" onchange="this.form.submit()">
              <option value="">-- Pilih Kelas Asal --</option>
              @foreach($kelasList as $k)
                <option value="{{ $k->id }}" {{ $sourceKelasId == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }} ({{ $k->tingkat }})</option>
              @endforeach
            </select>
          </div>
        </form>
      </div>
    </div>
  </div>

  @if($sourceKelasId && $siswaList->count() > 0)
    <div class="col-12">
      <form method="POST" action="{{ route('siswapindah.process') }}">
        @csrf
        <input type="hidden" name="source_kelas_id" value="{{ $sourceKelasId }}">

        <div class="card">
          <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
              <label class="form-label small fw-semibold mb-0">Pindahkan ke Kelas:</label>
              <select name="target_kelas_id" class="form-select form-select-sm" style="min-width: 200px;" required>
                <option value="">-- Pilih Kelas Tujuan --</option>
                @foreach($kelasList as $k)
                  @if($k->id != $sourceKelasId)
                    <option value="{{ $k->id }}">{{ $k->nama_kelas }} ({{ $k->tingkat }})</option>
                  @endif
                @endforeach
              </select>
            </div>
            <div>
              <button type="submit" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1 shadow-xs" data-confirm-title="Konfirmasi Pindah Kelas" data-confirm="Pindahkan seluruh siswa yang dipilih ke kelas tujuan?" data-confirm-icon="question" data-confirm-btn="Ya, Pindahkan!">
                <x-heroicon-o-arrows-right-left class="heroicon-sm me-1" /> Jalankan Pemindahan
              </button>
            </div>
          </div>

          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead>
                  <tr>
                    <th style="width: 40px;" class="text-center">
                      <input type="checkbox" class="form-check-input" onclick="$('.check-siswa').prop('checked', this.checked)">
                    </th>
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th>JK</th>
                    <th>Kategori IPP</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($siswaList as $s)
                    <tr>
                      <td class="text-center">
                        <input type="checkbox" name="siswa_ids[]" value="{{ $s->id }}" class="form-check-input check-siswa">
                      </td>
                      <td>{{ $s->nis ?: '-' }}</td>
                      <td class="fw-bold">{{ $s->nama_siswa }}</td>
                      <td><span class="badge bg-secondary-subtle text-secondary">{{ $s->jk }}</span></td>
                      <td><span class="badge bg-success-subtle text-success">{{ $s->kategori_ipp ?: '-' }}</span></td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </form>
    </div>
  @elseif($sourceKelasId)
    <div class="col-12">
      <div class="card text-center p-5 text-muted">
        <x-heroicon-o-inbox class="heroicon-lg mx-auto mb-2 text-muted" />
        Tidak ada siswa aktif di kelas ini.
      </div>
    </div>
  @endif
</div>
@endsection