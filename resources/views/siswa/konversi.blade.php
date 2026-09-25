@extends('layouts.app')

@section('title', 'Aturan Konversi Kategori IPP')
@section('page_title', 'Aturan & Kalkulasi Konversi IPP')
@section('page_subtitle', 'Penentuan kategori dan besaran iuran pengembangan pendidikan berdasarkan kriteria ekonomi dan kondisi keluarga')

@section('page_actions')
  <a href="{{ route('siswa.index') }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
    <x-heroicon-o-arrow-left class="heroicon-sm" /> Kembali ke Daftar Siswa
  </a>
@endsection

@section('content')
<div class="card mb-4">
  <div class="card-header bg-white">
    <div class="fw-bold d-flex align-items-center gap-1"><x-heroicon-o-information-circle class="heroicon-sm text-primary" /> Pedoman Konversi Biaya Siswa</div>
  </div>
  <div class="card-body">
    <div class="row g-4">
      <!-- Table 1: Occupations -->
      <div class="col-lg-7">
        <h6 class="fw-bold mb-1">Berdasarkan Pekerjaan & Nominal Estimasi</h6>
        <p class="text-muted small mb-3">Referensi pekerjaan dan rentang estimasi penghasilan orang tua.</p>

        <div class="table-responsive">
          <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th style="width: 40px;">No</th>
                <th>Profesi / Pekerjaan</th>
                <th>Estimasi Penghasilan</th>
                <th>Range Standar</th>
                <th class="text-center" style="width: 110px;">Konversi IPP</th>
              </tr>
            </thead>
            <tbody>
              @foreach($rulesOccupation as $idx => $r)
                <tr>
                  <td>{{ $idx + 1 }}</td>
                  <td class="fw-semibold">{{ $r['nama'] }}</td>
                  <td>{{ $r['income'] }}</td>
                  <td>{{ $r['range'] }}</td>
                  <td class="text-center">
                    <span class="badge {{ $r['percentage'] == '0% (Gratis)' ? 'bg-success' : 'bg-primary-subtle text-primary' }}">
                      {{ $r['percentage'] }}
                    </span>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

      <!-- Table 2: Ranges & Execution Button -->
      <div class="col-lg-5">
        <h6 class="fw-bold mb-1">Berdasarkan Range Penghasilan</h6>
        <p class="text-muted small mb-3">Formula utama kalkulasi sistem.</p>

        <div class="table-responsive mb-4">
          <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th>Range Penghasilan</th>
                <th class="text-center" style="width: 120px;">Persentase</th>
              </tr>
            </thead>
            <tbody>
              @foreach($rulesRange as $r)
                <tr>
                  <td class="small">{{ $r['range'] }}</td>
                  <td class="text-center">
                    <span class="badge {{ $r['percentage'] == '0% (Gratis)' ? 'bg-success' : 'bg-primary-subtle text-primary' }}">
                      {{ $r['percentage'] }}
                    </span>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div class="p-3 bg-light rounded border">
          <h6 class="fw-bold text-primary mb-2 d-flex align-items-center gap-1"><x-heroicon-o-arrow-path class="heroicon-sm" /> Jalankan Konversi Otomatis</h6>
          <p class="small text-muted mb-3">
            Sistem akan mengevaluasi seluruh data siswa aktif dan menetapkan kategori IPP secara otomatis berdasarkan kondisi keluarga dan penghasilan orang tua.
          </p>
          <form method="POST" action="{{ route('siswa.process_konversi') }}">
            @csrf
            <button type="submit" class="btn btn-primary w-100 py-2 d-inline-flex align-items-center justify-content-center gap-1 shadow-sm" data-confirm-title="Konversi IPP Otomatis" data-confirm="Jalankan proses evaluasi dan konversi kategori IPP untuk seluruh siswa aktif berdasarkan penghasilan orang tua?" data-confirm-icon="question" data-confirm-btn="Ya, Jalankan Konversi!">
              <x-heroicon-s-play class="heroicon-sm" /> Jalankan Konversi Massal Sekarang
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="card-footer bg-light text-muted small">
    <i>* Siswa dengan kategori khusus (Yatim, Piatu, SKTM, Panti Asuhan) otomatis memperoleh kategori 0% (Gratis).</i>
  </div>
</div>
@endsection