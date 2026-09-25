@extends('layouts.app')

@section('title', 'Detail Siswa - ' . $siswa->nama_siswa)
@section('page_title', 'Profil Lengkap Siswa')
@section('page_subtitle', 'Data terpadu 6 tab: identitas, keluarga, domisili, transportasi, dan dokumen pendukung')

@section('page_actions')
  <a href="{{ route('siswa.edit', $siswa->id) }}" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1">
    <x-heroicon-o-pencil-square class="heroicon-sm" /> Edit Siswa
  </a>
  <a href="{{ route('siswa.index') }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
    <x-heroicon-o-arrow-left class="heroicon-sm" /> Kembali
  </a>
@endsection

@section('content')
<div class="row g-4">
  <!-- Left Side: Profile Card -->
  <div class="col-lg-4">
    <div class="card text-center p-4 mb-4">
      <div class="mb-3">
        @if($siswa->foto_url)
          <img src="{{ $siswa->foto_url }}" alt="Foto" width="110" height="110" class="rounded-circle object-fit-cover shadow-sm border border-3 border-primary-subtle">
        @else
          <div class="rounded-circle bg-light text-muted d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 110px; height: 110px;">
            <x-heroicon-o-user class="heroicon-lg text-secondary" style="width: 50px; height: 50px;" />
          </div>
        @endif
      </div>
      <h4 class="fw-bold mb-1">{{ $siswa->nama_siswa }}</h4>
      <div class="text-muted small mb-2">NIS: {{ $siswa->nis ?: '-' }} | NISN: {{ $siswa->nisn ?: '-' }}</div>
      <div>
        <span class="badge bg-primary px-3 py-2 rounded-pill">{{ $siswa->kelas->nama_kelas ?? 'Tanpa Rombel' }}</span>
        <span class="badge {{ $siswa->status == 'Aktif' || empty($siswa->status) ? 'bg-success' : 'bg-secondary' }} px-3 py-2 rounded-pill">
          {{ $siswa->status ?: 'Aktif' }}
        </span>
      </div>

      <hr class="my-4">

      <div class="text-start">
        <div class="d-flex justify-content-between mb-2">
          <span class="text-muted small">Kategori IPP</span>
          <span class="fw-bold text-success">{{ $siswa->kategori_ipp ?: '-' }}</span>
        </div>
        <div class="d-flex justify-content-between mb-2">
          <span class="text-muted small">Nominal IPP</span>
          <span class="fw-bold">Rp {{ number_format($siswa->nominal_ipp ?: 0) }}</span>
        </div>
        <div class="d-flex justify-content-between mb-2">
          <span class="text-muted small">Tahun Ajaran</span>
          <span class="fw-semibold">{{ $siswa->tahun_ajaran }} ({{ $siswa->semester }})</span>
        </div>
        <div class="d-flex justify-content-between">
          <span class="text-muted small">Kategori Siswa</span>
          <span class="fw-semibold">{{ $siswa->kategori_siswa ?: '-' }}</span>
        </div>
      </div>
    </div>

    <!-- Uploaded Documents Card -->
    <div class="card">
      <div class="card-header bg-white">
        <div class="fw-bold d-flex align-items-center gap-1"><x-heroicon-o-document-text class="heroicon-sm text-primary" /> Dokumen Pendukung</div>
      </div>
      <div class="card-body p-0">
        <ul class="list-group list-group-flush small">
          <li class="list-group-item d-flex justify-content-between align-items-center py-2">
            <span>Kartu Keluarga (KK)</span>
            @php $kkUrl = $siswa->getDokUrl('dok_kk'); @endphp
            @if($kkUrl)
              <a href="{{ $kkUrl }}" target="_blank" class="btn btn-sm btn-outline-primary py-0 px-2">Lihat File</a>
            @else
              <span class="badge bg-light text-muted border">Belum ada</span>
            @endif
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center py-2">
            <span>SKTM</span>
            @php $sktmUrl = $siswa->getDokUrl('dok_sktm'); @endphp
            @if($sktmUrl)
              <a href="{{ $sktmUrl }}" target="_blank" class="btn btn-sm btn-outline-primary py-0 px-2">Lihat File</a>
            @else
              <span class="badge bg-light text-muted border">Belum ada</span>
            @endif
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center py-2">
            <span>Slip Gaji / Ket Penghasilan</span>
            @php $slipUrl = $siswa->getDokUrl('dok_slip_gaji'); @endphp
            @if($slipUrl)
              <a href="{{ $slipUrl }}" target="_blank" class="btn btn-sm btn-outline-primary py-0 px-2">Lihat File</a>
            @else
              <span class="badge bg-light text-muted border">Belum ada</span>
            @endif
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center py-2">
            <span>Bukti Kartu Bansos</span>
            @php $bansosUrl = $siswa->getDokUrl('dok_bansos'); @endphp
            @if($bansosUrl)
              <a href="{{ $bansosUrl }}" target="_blank" class="btn btn-sm btn-outline-primary py-0 px-2">Lihat File</a>
            @else
              <span class="badge bg-light text-muted border">Belum ada</span>
            @endif
          </li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Right Side: 5 Tabs of Details -->
  <div class="col-lg-8">
    <div class="card">
      <div class="card-header p-2 bg-white">
        <ul class="nav nav-tabs card-header-tabs" id="siswaTabs" role="tablist">
          <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-identitas" type="button">1. Identitas</button>
          </li>
          <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-domisili" type="button">2. Domisili</button>
          </li>
          <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-ortu" type="button">3. Orang Tua</button>
          </li>
          <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-rumah" type="button">4. Rumah & Digital</button>
          </li>
          <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-transport" type="button">5. Transportasi</button>
          </li>
        </ul>
      </div>

      <div class="card-body">
        <div class="tab-content">
          <!-- Tab 1 -->
          <div class="tab-pane fade show active" id="tab-identitas">
            <div class="row g-3">
              <div class="col-sm-6">
                <label class="text-muted small">Nama Lengkap</label>
                <div class="fw-semibold">{{ $siswa->nama_siswa }}</div>
              </div>
              <div class="col-sm-6">
                <label class="text-muted small">Jenis Kelamin</label>
                <div class="fw-semibold">{{ $siswa->jk == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
              </div>
              <div class="col-sm-6">
                <label class="text-muted small">Tempat, Tanggal Lahir</label>
                <div class="fw-semibold">{{ $siswa->tempat_lahir ? $siswa->tempat_lahir . ', ' : '' }}{{ $siswa->tanggal_lahir ? (\Carbon\Carbon::canBeCreatedFromFormat($siswa->tanggal_lahir, 'Y-m-d') || strtotime($siswa->tanggal_lahir) ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->translatedFormat('d F Y') : $siswa->tanggal_lahir) : '-' }}</div>
              </div>
              <div class="col-sm-6">
                <label class="text-muted small">Agama</label>
                <div class="fw-semibold">{{ $siswa->agama ?: '-' }}</div>
              </div>
              <div class="col-sm-6">
                <label class="text-muted small">Nomor KK</label>
                <div class="fw-semibold">{{ $siswa->no_kk ?: '-' }}</div>
              </div>
              <div class="col-sm-6">
                <label class="text-muted small">Sumber Biaya</label>
                <div class="fw-semibold">{{ $siswa->sumber_biaya ?: '-' }}</div>
              </div>
            </div>
          </div>

          <!-- Tab 2 -->
          <div class="tab-pane fade" id="tab-domisili">
            <div class="row g-3">
              <div class="col-12">
                <label class="text-muted small">Alamat Lengkap</label>
                <div class="fw-semibold">{{ $siswa->alamat ?: '-' }}</div>
              </div>
              <div class="col-sm-4">
                <label class="text-muted small">RT / RW</label>
                <div class="fw-semibold">{{ $siswa->rt ?: '-' }} / {{ $siswa->rw ?: '-' }}</div>
              </div>
              <div class="col-sm-4">
                <label class="text-muted small">Dusun</label>
                <div class="fw-semibold">{{ $siswa->dusun ?: '-' }}</div>
              </div>
              <div class="col-sm-4">
                <label class="text-muted small">Kelurahan / Desa</label>
                <div class="fw-semibold">{{ $siswa->kelurahan ?: '-' }}</div>
              </div>
            </div>
          </div>

          <!-- Tab 3 -->
          <div class="tab-pane fade" id="tab-ortu">
            <h6 class="fw-bold text-primary mb-3">Data Ayah</h6>
            <div class="row g-3 mb-4">
              <div class="col-sm-4">
                <label class="text-muted small">Nama Ayah</label>
                <div class="fw-semibold">{{ $siswa->nama_ayah ?: '-' }}</div>
              </div>
              <div class="col-sm-4">
                <label class="text-muted small">Pekerjaan Ayah</label>
                <div class="fw-semibold">{{ $siswa->pekerjaan_ayah ?: '-' }}</div>
              </div>
              <div class="col-sm-4">
                <label class="text-muted small">Penghasilan Ayah</label>
                <div class="fw-semibold">{{ $siswa->penghasilan_ayah ?: '-' }}</div>
              </div>
            </div>

            <h6 class="fw-bold text-primary mb-3">Data Ibu</h6>
            <div class="row g-3 mb-4">
              <div class="col-sm-4">
                <label class="text-muted small">Nama Ibu</label>
                <div class="fw-semibold">{{ $siswa->nama_ibu ?: '-' }}</div>
              </div>
              <div class="col-sm-4">
                <label class="text-muted small">Pekerjaan Ibu</label>
                <div class="fw-semibold">{{ $siswa->pekerjaan_ibu ?: '-' }}</div>
              </div>
              <div class="col-sm-4">
                <label class="text-muted small">Penghasilan Ibu</label>
                <div class="fw-semibold">{{ $siswa->penghasilan_ibu ?: '-' }}</div>
              </div>
            </div>

            <div class="d-flex justify-content-between border-top pt-3">
              <span class="text-muted small">Jumlah Tanggungan Orang Tua</span>
              <span class="fw-bold">{{ $siswa->jml_tanggungan_ortu ?: '-' }} orang</span>
            </div>
          </div>

          <!-- Tab 4 -->
          <div class="tab-pane fade" id="tab-rumah">
            <div class="row g-3">
              <div class="col-sm-6">
                <label class="text-muted small">Keadaan Rumah</label>
                <div class="fw-semibold">{{ $siswa->keadaan_rumah ?: '-' }}</div>
              </div>
              <div class="col-sm-6">
                <label class="text-muted small">Kondisi Kerentanan</label>
                <div class="fw-semibold">{{ $siswa->kondisi_kerentanan ?: '-' }}</div>
              </div>
              <div class="col-sm-6">
                <label class="text-muted small">Kepemilikan HP</label>
                <div class="fw-semibold">{{ $siswa->kepemilikan_hp ?: '-' }}</div>
              </div>
              <div class="col-sm-6">
                <label class="text-muted small">Akses Internet</label>
                <div class="fw-semibold">{{ $siswa->akses_internet ?: '-' }}</div>
              </div>
            </div>
          </div>

          <!-- Tab 5 -->
          <div class="tab-pane fade" id="tab-transport">
            <div class="row g-3">
              <div class="col-sm-6">
                <label class="text-muted small">Jarak ke Sekolah</label>
                <div class="fw-semibold">{{ $siswa->jarak_sekolah ?: '-' }}</div>
              </div>
              <div class="col-sm-6">
                <label class="text-muted small">Waktu Tempuh</label>
                <div class="fw-semibold">{{ $siswa->waktu_tempuh ?: '-' }}</div>
              </div>
              <div class="col-sm-6">
                <label class="text-muted small">Moda Transportasi</label>
                <div class="fw-semibold">{{ $siswa->moda_transportasi ?: '-' }}</div>
              </div>
              <div class="col-sm-6">
                <label class="text-muted small">Biaya Transportasi</label>
                <div class="fw-semibold">{{ $siswa->biaya_transportasi ?: '-' }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection