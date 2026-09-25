@extends('layouts.app')

@section('title', 'Data Referensi Master')
@section('page_title', 'Data Referensi & Master')
@section('page_subtitle', 'Tabel referensi tahun ajaran, pekerjaan, penghasilan, dan kategori siswa')

@section('content')
<div class="row g-4">
  <!-- Tahun Ajaran -->
  <div class="col-md-6">
    <div class="card h-100">
      <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <div>
          <div class="fw-bold d-flex align-items-center gap-1"><x-heroicon-o-calendar-days class="heroicon-sm text-primary" /> Tahun Ajaran</div>
          <div class="text-muted small">Daftar referensi & status tahun ajaran aktif</div>
        </div>
        <a href="{{ route('settings.index') }}" class="btn btn-outline-secondary btn-sm py-0 px-2 d-inline-flex align-items-center gap-1" title="Pengaturan Sistem">
          <x-heroicon-o-adjustments-horizontal class="heroicon-sm" /> Pengaturan
        </a>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('master.tahun_ajaran.store') }}" class="d-flex gap-2 mb-3">
          @csrf
          <input type="text" name="tahun_ajaran" class="form-control form-control-sm" placeholder="Contoh: 2027/2028" required>
          <button type="submit" class="btn btn-primary btn-sm text-nowrap d-inline-flex align-items-center gap-1"><x-heroicon-o-plus class="heroicon-sm" /> Tambah</button>
        </form>
        <ul class="list-group list-group-flush small">
          @foreach($tahunList as $t)
            @php $isActive = ($t->tahun_ajaran === $activeTahunAjaran); @endphp
            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 {{ $isActive ? 'bg-success-subtle bg-opacity-25 rounded px-2' : '' }}">
              <div class="d-flex align-items-center gap-2">
                <span class="fw-semibold {{ $isActive ? 'text-success' : 'text-dark' }}">{{ $t->tahun_ajaran }}</span>
                @if($isActive)
                  <span class="badge bg-success text-white py-0.5 px-2 d-inline-flex align-items-center gap-1" style="font-size: 0.68rem;">
                    <x-heroicon-s-check-circle class="heroicon-sm" /> Aktif Digunakan
                  </span>
                @endif
              </div>
              <div class="d-flex align-items-center gap-1">
                @if(!$isActive)
                  <form method="POST" action="{{ route('master.tahun_ajaran.set_active', $t->id_tahun_ajaran) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-success py-0 px-2 d-inline-flex align-items-center gap-1" title="Jadikan Tahun Ajaran Aktif" data-confirm-title="Ganti Tahun Ajaran Aktif" data-confirm="Jadikan {{ $t->tahun_ajaran }} ({{ $t->semester }}) sebagai tahun ajaran aktif sistem?" data-confirm-icon="question" data-confirm-btn="Ya, Aktifkan" data-confirm-btn-color="#16a34a">
                      <x-heroicon-o-check-circle class="heroicon-sm" /> Jadikan Aktif
                    </button>
                  </form>
                  <form method="POST" action="{{ route('master.tahun_ajaran.destroy', $t->id_tahun_ajaran) }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm py-0 px-2 btn-delete-confirm" title="Hapus"><x-heroicon-o-trash class="heroicon-sm" /></button>
                  </form>
                @else
                  <span class="text-muted small fst-italic">Aktif di Sistem</span>
                @endif
              </div>
            </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>

  <!-- Kategori Siswa -->
  <div class="col-md-6">
    <div class="card h-100">
      <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <div class="fw-bold d-flex align-items-center gap-1"><x-heroicon-o-tag class="heroicon-sm text-primary" /> Kategori Siswa</div>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('master.kategori.store') }}" class="d-flex gap-2 mb-3">
          @csrf
          <input type="text" name="nama_kategori" class="form-control form-control-sm" placeholder="Contoh: Reguler / Yatim / Afirmasi" required>
          <button type="submit" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1"><x-heroicon-o-plus class="heroicon-sm" /> Tambah</button>
        </form>
        <ul class="list-group list-group-flush small">
          @foreach($kategoriList as $k)
            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
              <span class="fw-semibold">{{ $k->nama_kategori }}</span>
              <form method="POST" action="{{ route('master.kategori.destroy', $k->id_kategori) }}">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm py-0 px-2 btn-delete-confirm"><x-heroicon-o-trash class="heroicon-sm" /></button>
              </form>
            </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>

  <!-- Range Penghasilan -->
  <div class="col-md-6">
    <div class="card h-100">
      <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <div class="fw-bold d-flex align-items-center gap-1"><x-heroicon-o-banknotes class="heroicon-sm text-primary" /> Rentang Penghasilan Orang Tua</div>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('master.penghasilan.store') }}" class="d-flex gap-2 mb-3">
          @csrf
          <input type="text" name="range_penghasilan" class="form-control form-control-sm" placeholder="Contoh: Rp 1.000.000 - Rp 2.000.000" required>
          <button type="submit" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1"><x-heroicon-o-plus class="heroicon-sm" /> Tambah</button>
        </form>
        <ul class="list-group list-group-flush small">
          @foreach($penghasilanList as $p)
            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
              <span class="fw-semibold">{{ $p->range_penghasilan }}</span>
              <form method="POST" action="{{ route('master.penghasilan.destroy', $p->id_penghasilan) }}">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm py-0 px-2 btn-delete-confirm"><x-heroicon-o-trash class="heroicon-sm" /></button>
              </form>
            </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>

  <!-- Sumber Biaya -->
  <div class="col-md-6">
    <div class="card h-100">
      <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <div class="fw-bold d-flex align-items-center gap-1"><x-heroicon-o-wallet class="heroicon-sm text-primary" /> Sumber Biaya Pendidikan</div>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('master.sumber_biaya.store') }}" class="d-flex gap-2 mb-3">
          @csrf
          <input type="text" name="nama_sumber_biaya" class="form-control form-control-sm" placeholder="Contoh: Orang Tua / Beasiswa" required>
          <button type="submit" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1"><x-heroicon-o-plus class="heroicon-sm" /> Tambah</button>
        </form>
        <ul class="list-group list-group-flush small">
          @foreach($sumberBiayaList as $sb)
            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
              <span class="fw-semibold">{{ $sb->nama_sumber_biaya }}</span>
              <form method="POST" action="{{ route('master.sumber_biaya.destroy', $sb->id_sumber_biaya) }}">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm py-0 px-2 btn-delete-confirm"><x-heroicon-o-trash class="heroicon-sm" /></button>
              </form>
            </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
</div>
@endsection