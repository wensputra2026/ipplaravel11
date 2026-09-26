@extends('layouts.app')

@section('title', 'Tambah Siswa Baru')
@section('page_title', 'Tambah Siswa Baru')
@section('page_subtitle', 'Formulir terpadu data pribadi, keluarga, ekonomi, dan berkas siswa')

@section('page_actions')
  <a href="{{ route('siswa.template') }}" class="btn btn-outline-success btn-sm d-inline-flex align-items-center gap-1 shadow-xs" title="Download Template Excel">
    <x-heroicon-o-arrow-down-tray class="heroicon-sm" /> Template Excel
  </a>
  <button type="button" class="btn btn-success btn-sm d-inline-flex align-items-center gap-1 shadow-xs" data-bs-toggle="modal" data-bs-target="#importExcelModal">
    <x-heroicon-o-arrow-up-tray class="heroicon-sm" /> Import Excel
  </button>
  <a href="{{ route('siswa.index') }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
    <x-heroicon-o-arrow-left class="heroicon-sm" /> Kembali
  </a>
@endsection

@section('content')
<form method="POST" action="{{ route('siswa.store') }}" enctype="multipart/form-data">
  @csrf
  <div class="card">
    <div class="card-header p-2 bg-white">
      <ul class="nav nav-tabs card-header-tabs" id="siswaFormTabs" role="tablist">
        <li class="nav-item">
          <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#f-identitas" type="button">1. Identitas Siswa</button>
        </li>
        <li class="nav-item">
          <button class="nav-link" data-bs-toggle="tab" data-bs-target="#f-domisili" type="button">2. Domisili</button>
        </li>
        <li class="nav-item">
          <button class="nav-link" data-bs-toggle="tab" data-bs-target="#f-ortu" type="button">3. Orang Tua & Wali</button>
        </li>
        <li class="nav-item">
          <button class="nav-link" data-bs-toggle="tab" data-bs-target="#f-rumah" type="button">4. Rumah & Digital</button>
        </li>
        <li class="nav-item">
          <button class="nav-link" data-bs-toggle="tab" data-bs-target="#f-transport" type="button">5. Transportasi</button>
        </li>
        <li class="nav-item">
          <button class="nav-link" data-bs-toggle="tab" data-bs-target="#f-biaya" type="button">6. Biaya & Dokumen</button>
        </li>
      </ul>
    </div>

    <div class="card-body">
      <div class="tab-content pt-2">
        <!-- Tab 1: Identitas -->
        <div class="tab-pane fade show active" id="f-identitas">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Nama Lengkap Siswa <span class="text-danger">*</span></label>
              <input type="text" name="nama_siswa" class="form-control" value="{{ old('nama_siswa') }}" required>
            </div>
            <div class="col-md-3">
              <label class="form-label small fw-semibold">NIS</label>
              <input type="text" name="nis" class="form-control" value="{{ old('nis') }}">
            </div>
            <div class="col-md-3">
              <label class="form-label small fw-semibold">NISN</label>
              <input type="text" name="nisn" class="form-control" value="{{ old('nisn') }}">
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Rombel Kelas <span class="text-danger">*</span></label>
              <select name="kelas_id" class="form-select tom-select" required>
                <option value="">-- Pilih Kelas --</option>
                @foreach($kelasList as $k)
                  <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
              <select name="jk" class="form-select" required>
                <option value="L" {{ old('jk') == 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                <option value="P" {{ old('jk') == 'P' ? 'selected' : '' }}>Perempuan (P)</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Agama</label>
              <select name="agama" class="form-select tom-select">
                <option value="">-- Pilih Agama --</option>
                @foreach(['Kristen Protestan', 'Katolik', 'Islam', 'Hindu', 'Buddha', 'Konghucu'] as $ag)
                  <option value="{{ $ag }}" {{ old('agama') == $ag ? 'selected' : '' }}>{{ $ag }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Tempat Lahir</label>
              <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir') }}">
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Tanggal Lahir</label>
              <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir') }}">
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Tahun Ajaran</label>
              <input type="text" name="tahun_ajaran" class="form-control" value="{{ old('tahun_ajaran', $activeTa) }}">
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Semester</label>
              <select name="semester" class="form-select">
                <option value="Ganjil" {{ old('semester', $activeSem) == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                <option value="Genap" {{ old('semester', $activeSem) == 'Genap' ? 'selected' : '' }}>Genap</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Status Siswa</label>
              <select name="status" class="form-select">
                <option value="Aktif" selected>Aktif</option>
                <option value="Lulus">Lulus</option>
                <option value="Pindah">Pindah</option>
                <option value="Keluar">Keluar</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Tab 2: Domisili -->
        <div class="tab-pane fade" id="f-domisili">
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label small fw-semibold">Alamat Lengkap (Jalan / Dusun)</label>
              <textarea name="alamat" class="form-control" rows="2">{{ old('alamat') }}</textarea>
            </div>
            <div class="col-md-3">
              <label class="form-label small fw-semibold">RT</label>
              <input type="text" name="rt" class="form-control" value="{{ old('rt') }}">
            </div>
            <div class="col-md-3">
              <label class="form-label small fw-semibold">RW</label>
              <input type="text" name="rw" class="form-control" value="{{ old('rw') }}">
            </div>
            <div class="col-md-3">
              <label class="form-label small fw-semibold">Dusun</label>
              <input type="text" name="dusun" class="form-control" value="{{ old('dusun') }}">
            </div>
            <div class="col-md-3">
              <label class="form-label small fw-semibold">Desa / Kelurahan</label>
              <input type="text" name="kelurahan" class="form-control" value="{{ old('kelurahan') }}">
            </div>
          </div>
        </div>

        <!-- Tab 3: Orang Tua -->
        <div class="tab-pane fade" id="f-ortu">
          <h6 class="fw-bold text-primary mb-3">Data Ayah Kandung</h6>
          <div class="row g-3 mb-4">
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Nama Ayah</label>
              <input type="text" name="nama_ayah" class="form-control" value="{{ old('nama_ayah') }}">
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Pekerjaan Ayah</label>
              <select name="pekerjaan_ayah" class="form-select tom-select">
                <option value="">-- Pilih Pekerjaan --</option>
                @foreach($pekerjaanList as $p)
                  <option value="{{ $p }}" {{ old('pekerjaan_ayah') == $p ? 'selected' : '' }}>{{ $p }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Penghasilan Ayah</label>
              <select name="penghasilan_ayah" class="form-select tom-select">
                <option value="">-- Pilih Range Penghasilan --</option>
                @foreach($penghasilanList as $pen)
                  <option value="{{ $pen }}" {{ old('penghasilan_ayah') == $pen ? 'selected' : '' }}>{{ $pen }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <h6 class="fw-bold text-primary mb-3">Data Ibu Kandung</h6>
          <div class="row g-3 mb-4">
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Nama Ibu</label>
              <input type="text" name="nama_ibu" class="form-control" value="{{ old('nama_ibu') }}">
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Pekerjaan Ibu</label>
              <select name="pekerjaan_ibu" class="form-select tom-select">
                <option value="">-- Pilih Pekerjaan --</option>
                @foreach($pekerjaanList as $p)
                  <option value="{{ $p }}" {{ old('pekerjaan_ibu') == $p ? 'selected' : '' }}>{{ $p }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Penghasilan Ibu</label>
              <select name="penghasilan_ibu" class="form-select tom-select">
                <option value="">-- Pilih Range Penghasilan --</option>
                @foreach($penghasilanList as $pen)
                  <option value="{{ $pen }}" {{ old('penghasilan_ibu') == $pen ? 'selected' : '' }}>{{ $pen }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Jumlah Tanggungan Keluarga</label>
              <input type="number" name="jml_tanggungan_ortu" class="form-control" value="{{ old('jml_tanggungan_ortu', 0) }}">
            </div>
          </div>
        </div>

        <!-- Tab 4: Rumah & Digital -->
        <div class="tab-pane fade" id="f-rumah">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Keadaan Rumah</label>
              <input type="text" name="keadaan_rumah" class="form-control" value="{{ old('keadaan_rumah') }}" placeholder="Permanen / Semi Permanen / Papan">
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Status Kepemilikan Rumah</label>
              <input type="text" name="status_kepemilikan_rumah" class="form-control" value="{{ old('status_kepemilikan_rumah') }}" placeholder="Milik Sendiri / Sewa / Menumpang">
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Kondisi Kerentanan</label>
              <input type="text" name="kondisi_kerentanan" class="form-control" value="{{ old('kondisi_kerentanan') }}" placeholder="Yatim / Piatu / Sakit Menahun / dsb">
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Kepemilikan Smartphone / HP</label>
              <input type="text" name="kepemilikan_hp" class="form-control" value="{{ old('kepemilikan_hp') }}">
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Akses Internet di Rumah</label>
              <input type="text" name="akses_internet" class="form-control" value="{{ old('akses_internet') }}">
            </div>
          </div>
        </div>

        <!-- Tab 5: Transportasi -->
        <div class="tab-pane fade" id="f-transport">
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Jarak ke Sekolah (km)</label>
              <input type="text" name="jarak_sekolah" class="form-control" value="{{ old('jarak_sekolah') }}">
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Waktu Tempuh (menit)</label>
              <input type="text" name="waktu_tempuh" class="form-control" value="{{ old('waktu_tempuh') }}">
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Moda Transportasi</label>
              <input type="text" name="moda_transportasi" class="form-control" value="{{ old('moda_transportasi') }}" placeholder="Jalan Kaki / Motor / Angkutan">
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Biaya Transportasi Harian</label>
              <input type="text" name="biaya_transportasi" class="form-control" value="{{ old('biaya_transportasi') }}">
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Kepemilikan Kendaraan Pribadi</label>
              <input type="text" name="status_kepemilikan_kendaraan" class="form-control" value="{{ old('status_kepemilikan_kendaraan') }}">
            </div>
          </div>
        </div>

        <!-- Tab 6: Biaya & Dokumen -->
        <div class="tab-pane fade" id="f-biaya">
          <div class="row g-3 mb-4">
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Nomor Kartu Keluarga (KK)</label>
              <input type="text" name="no_kk" class="form-control" value="{{ old('no_kk') }}">
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Kategori Siswa</label>
              <select name="kategori_siswa" class="form-select tom-select">
                <option value="">-- Pilih Kategori --</option>
                @foreach($kategoriList as $kat)
                  <option value="{{ $kat }}" {{ old('kategori_siswa') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Sumber Biaya Sekolah</label>
              <select name="sumber_biaya" class="form-select tom-select">
                <option value="">-- Pilih Sumber Biaya --</option>
                @foreach($sumberBiayaList as $sb)
                  <option value="{{ $sb }}" {{ old('sumber_biaya') == $sb ? 'selected' : '' }}>{{ $sb }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Kategori IPP</label>
              <input type="text" name="kategori_ipp" class="form-control" value="{{ old('kategori_ipp') }}" placeholder="Contoh: 100%, 75%, 50%, 25%, 0% (Gratis)">
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Nominal IPP (Rp)</label>
              <input type="number" name="nominal_ipp" class="form-control" value="{{ old('nominal_ipp') }}">
            </div>
          </div>

          <h6 class="fw-bold text-primary mb-3 d-flex align-items-center gap-1"><x-heroicon-o-arrow-up-tray class="heroicon-sm" /> Unggah Berkas & Dokumen</h6>
          <div class="row g-3">
            <div class="col-md-6" x-data="{
                previewUrl: '',
                newPreview(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const r = new FileReader();
                        r.onload = e => this.previewUrl = e.target.result;
                        r.readAsDataURL(file);
                    }
                }
            }">
              <label class="form-label small fw-semibold">Foto Profil Siswa</label>
              <div x-show="previewUrl" class="d-flex align-items-center gap-3 mb-2 p-2 bg-light rounded-3 border">
                <img :src="previewUrl" alt="Foto Siswa" class="rounded-3 object-fit-cover shadow-xs border border-2 border-white" style="width: 80px; height: 80px;">
                <div>
                  <div class="fw-semibold text-dark small">Pratinjau Foto Baru</div>
                  <span class="badge bg-primary-subtle text-primary border border-primary-subtle mt-1 py-0.5 px-2">Siap Diunggah</span>
                </div>
              </div>
              <input type="file" name="foto" class="form-control" accept="image/*" @change="newPreview">
              <div class="form-text small text-muted">Format: JPG, JPEG, PNG, WEBP (otomatis dikompres).</div>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Dokumen Kartu Keluarga (PDF/JPG)</label>
              <input type="file" name="dok_kk" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Surat Keterangan Tidak Mampu (SKTM)</label>
              <input type="file" name="dok_sktm" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Slip Gaji / Bukti Penghasilan</label>
              <input type="file" name="dok_slip_gaji" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Kartu Bansos (KIP/PKH/KKS)</label>
              <input type="file" name="dok_bansos" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Keterangan Bansos Tambahan</label>
              <input type="text" name="ket_bansos" class="form-control" value="{{ old('ket_bansos') }}" placeholder="Contoh: KIP Aktif No. 12345">
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card-footer bg-light d-flex justify-content-between">
      <a href="{{ route('siswa.index') }}" class="btn btn-outline-secondary">Batal</a>
      <button type="submit" class="btn btn-primary px-4 d-inline-flex align-items-center gap-1">
        <x-heroicon-o-check class="heroicon-sm" /> Simpan Data Siswa
      </button>
    </div>
  </div>
</form>

@include('siswa.partials.import-modal')
@endsection