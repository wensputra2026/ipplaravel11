@extends('layouts.app')

@section('title', 'Edit Siswa - ' . $siswa->nama_siswa)
@section('page_title', 'Perbarui Data Siswa')
@section('page_subtitle', 'Edit data identitas, orang tua, kondisi ekonomi, dan berkas siswa')

@section('page_actions')
  <a href="{{ route('siswa.show', $siswa->id) }}" class="btn btn-outline-info btn-sm d-inline-flex align-items-center gap-1">
    <x-heroicon-o-eye class="heroicon-sm" /> Lihat Profil
  </a>
  <a href="{{ route('siswa.index') }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
    <x-heroicon-o-arrow-left class="heroicon-sm" /> Kembali
  </a>
@endsection

@section('content')
<!-- Header Info Siswa -->
<div class="card mb-3 border-0 shadow-xs bg-light">
  <div class="card-body p-3">
    <div class="d-flex align-items-center gap-3">
      @if($siswa->foto_url)
        <img src="{{ $siswa->foto_url }}" alt="{{ $siswa->nama_siswa }}" class="rounded-circle object-fit-cover shadow-sm border border-2 border-white" style="width: 58px; height: 58px;">
      @else
        <div class="rounded-circle bg-white text-muted d-inline-flex align-items-center justify-content-center shadow-xs border" style="width: 58px; height: 58px; font-size: 1.6rem;">
          <x-heroicon-o-user class="heroicon-lg text-secondary" style="width: 32px; height: 32px;" />
        </div>
      @endif
      <div>
        <h5 class="mb-1 fw-bold text-dark">{{ $siswa->nama_siswa }}</h5>
        <div class="text-muted small">
          NIS: <span class="fw-semibold text-dark">{{ $siswa->nis ?: '-' }}</span> &bull; 
          NISN: <span class="fw-semibold text-dark">{{ $siswa->nisn ?: '-' }}</span> &bull; 
          Kelas: <span class="badge bg-primary-subtle text-primary border border-primary-subtle">{{ $siswa->kelas->nama_kelas ?? 'Tanpa Rombel' }}</span> &bull;
          Status: <span class="badge {{ $siswa->status == 'Aktif' || empty($siswa->status) ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-secondary-subtle text-secondary border' }}">{{ $siswa->status ?: 'Aktif' }}</span>
        </div>
      </div>
    </div>
  </div>
</div>

<form method="POST" action="{{ route('siswa.update', $siswa->id) }}" enctype="multipart/form-data">
  @csrf
  @method('PUT')
  <div class="card">
    <div class="card-header p-2 bg-white">
      <ul class="nav nav-tabs card-header-tabs" id="siswaEditTabs" role="tablist">
        <li class="nav-item">
          <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#e-identitas" type="button">1. Identitas Siswa</button>
        </li>
        <li class="nav-item">
          <button class="nav-link" data-bs-toggle="tab" data-bs-target="#e-domisili" type="button">2. Domisili</button>
        </li>
        <li class="nav-item">
          <button class="nav-link" data-bs-toggle="tab" data-bs-target="#e-ortu" type="button">3. Orang Tua & Wali</button>
        </li>
        <li class="nav-item">
          <button class="nav-link" data-bs-toggle="tab" data-bs-target="#e-rumah" type="button">4. Rumah & Digital</button>
        </li>
        <li class="nav-item">
          <button class="nav-link" data-bs-toggle="tab" data-bs-target="#e-transport" type="button">5. Transportasi</button>
        </li>
        <li class="nav-item">
          <button class="nav-link" data-bs-toggle="tab" data-bs-target="#e-biaya" type="button">6. Biaya & Dokumen</button>
        </li>
      </ul>
    </div>

    <div class="card-body">
      <div class="tab-content pt-2">
        <!-- Tab 1: Identitas -->
        <div class="tab-pane fade show active" id="e-identitas">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Nama Lengkap Siswa <span class="text-danger">*</span></label>
              <input type="text" name="nama_siswa" class="form-control" value="{{ old('nama_siswa', $siswa->nama_siswa) }}" required>
            </div>
            <div class="col-md-3">
              <label class="form-label small fw-semibold">NIS</label>
              <input type="text" name="nis" class="form-control" value="{{ old('nis', $siswa->nis) }}">
            </div>
            <div class="col-md-3">
              <label class="form-label small fw-semibold">NISN</label>
              <input type="text" name="nisn" class="form-control" value="{{ old('nisn', $siswa->nisn) }}">
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Rombel Kelas <span class="text-danger">*</span></label>
              <select name="kelas_id" class="form-select tom-select" required>
                <option value="">-- Pilih Kelas --</option>
                @foreach($kelasList as $k)
                  <option value="{{ $k->id }}" {{ old('kelas_id', $siswa->kelas_id) == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
              <select name="jk" class="form-select" required>
                <option value="L" {{ old('jk', $siswa->jk) == 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                <option value="P" {{ old('jk', $siswa->jk) == 'P' ? 'selected' : '' }}>Perempuan (P)</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Agama</label>
              <select name="agama" class="form-select tom-select">
                <option value="">-- Pilih Agama --</option>
                @foreach(['Kristen Protestan', 'Katolik', 'Islam', 'Hindu', 'Buddha', 'Konghucu'] as $ag)
                  <option value="{{ $ag }}" {{ old('agama', $siswa->agama) == $ag ? 'selected' : '' }}>{{ $ag }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Tempat Lahir</label>
              <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $siswa->tempat_lahir) }}">
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Tanggal Lahir</label>
              <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $siswa->tanggal_lahir) }}">
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Tahun Ajaran</label>
              <input type="text" name="tahun_ajaran" class="form-control" value="{{ old('tahun_ajaran', $siswa->tahun_ajaran) }}">
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Semester</label>
              <select name="semester" class="form-select">
                <option value="Ganjil" {{ old('semester', $siswa->semester) == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                <option value="Genap" {{ old('semester', $siswa->semester) == 'Genap' ? 'selected' : '' }}>Genap</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Status Siswa</label>
              <select name="status" class="form-select">
                <option value="Aktif" {{ old('status', $siswa->status) == 'Aktif' || empty($siswa->status) ? 'selected' : '' }}>Aktif</option>
                <option value="Lulus" {{ old('status', $siswa->status) == 'Lulus' ? 'selected' : '' }}>Lulus</option>
                <option value="Pindah" {{ old('status', $siswa->status) == 'Pindah' ? 'selected' : '' }}>Pindah</option>
                <option value="Keluar" {{ old('status', $siswa->status) == 'Keluar' ? 'selected' : '' }}>Keluar</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Tab 2: Domisili -->
        <div class="tab-pane fade" id="e-domisili">
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label small fw-semibold">Alamat Lengkap</label>
              <textarea name="alamat" class="form-control" rows="2">{{ old('alamat', $siswa->alamat) }}</textarea>
            </div>
            <div class="col-md-3">
              <label class="form-label small fw-semibold">RT</label>
              <input type="text" name="rt" class="form-control" value="{{ old('rt', $siswa->rt) }}">
            </div>
            <div class="col-md-3">
              <label class="form-label small fw-semibold">RW</label>
              <input type="text" name="rw" class="form-control" value="{{ old('rw', $siswa->rw) }}">
            </div>
            <div class="col-md-3">
              <label class="form-label small fw-semibold">Dusun</label>
              <input type="text" name="dusun" class="form-control" value="{{ old('dusun', $siswa->dusun) }}">
            </div>
            <div class="col-md-3">
              <label class="form-label small fw-semibold">Desa / Kelurahan</label>
              <input type="text" name="kelurahan" class="form-control" value="{{ old('kelurahan', $siswa->kelurahan) }}">
            </div>
          </div>
        </div>

        <!-- Tab 3: Orang Tua -->
        <div class="tab-pane fade" id="e-ortu">
          <h6 class="fw-bold text-primary mb-3">Data Ayah Kandung</h6>
          <div class="row g-3 mb-4">
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Nama Ayah</label>
              <input type="text" name="nama_ayah" class="form-control" value="{{ old('nama_ayah', $siswa->nama_ayah) }}">
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Pekerjaan Ayah</label>
              <select name="pekerjaan_ayah" class="form-select tom-select">
                <option value="">-- Pilih Pekerjaan --</option>
                @foreach($pekerjaanList as $p)
                  <option value="{{ $p }}" {{ old('pekerjaan_ayah', $siswa->pekerjaan_ayah) == $p ? 'selected' : '' }}>{{ $p }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Penghasilan Ayah</label>
              <select name="penghasilan_ayah" class="form-select tom-select">
                <option value="">-- Pilih Range Penghasilan --</option>
                @foreach($penghasilanList as $pen)
                  <option value="{{ $pen }}" {{ old('penghasilan_ayah', $siswa->penghasilan_ayah) == $pen ? 'selected' : '' }}>{{ $pen }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <h6 class="fw-bold text-primary mb-3">Data Ibu Kandung</h6>
          <div class="row g-3 mb-4">
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Nama Ibu</label>
              <input type="text" name="nama_ibu" class="form-control" value="{{ old('nama_ibu', $siswa->nama_ibu) }}">
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Pekerjaan Ibu</label>
              <select name="pekerjaan_ibu" class="form-select tom-select">
                <option value="">-- Pilih Pekerjaan --</option>
                @foreach($pekerjaanList as $p)
                  <option value="{{ $p }}" {{ old('pekerjaan_ibu', $siswa->pekerjaan_ibu) == $p ? 'selected' : '' }}>{{ $p }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Penghasilan Ibu</label>
              <select name="penghasilan_ibu" class="form-select tom-select">
                <option value="">-- Pilih Range Penghasilan --</option>
                @foreach($penghasilanList as $pen)
                  <option value="{{ $pen }}" {{ old('penghasilan_ibu', $siswa->penghasilan_ibu) == $pen ? 'selected' : '' }}>{{ $pen }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Jumlah Tanggungan Keluarga</label>
              <input type="number" name="jml_tanggungan_ortu" class="form-control" value="{{ old('jml_tanggungan_ortu', $siswa->jml_tanggungan_ortu) }}">
            </div>
          </div>
        </div>

        <!-- Tab 4: Rumah & Digital -->
        <div class="tab-pane fade" id="e-rumah">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Keadaan Rumah</label>
              <input type="text" name="keadaan_rumah" class="form-control" value="{{ old('keadaan_rumah', $siswa->keadaan_rumah) }}">
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Status Kepemilikan Rumah</label>
              <input type="text" name="status_kepemilikan_rumah" class="form-control" value="{{ old('status_kepemilikan_rumah', $siswa->status_kepemilikan_rumah) }}">
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Kondisi Kerentanan</label>
              <input type="text" name="kondisi_kerentanan" class="form-control" value="{{ old('kondisi_kerentanan', $siswa->kondisi_kerentanan) }}">
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Kepemilikan Smartphone / HP</label>
              <input type="text" name="kepemilikan_hp" class="form-control" value="{{ old('kepemilikan_hp', $siswa->kepemilikan_hp) }}">
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Akses Internet di Rumah</label>
              <input type="text" name="akses_internet" class="form-control" value="{{ old('akses_internet', $siswa->akses_internet) }}">
            </div>
          </div>
        </div>

        <!-- Tab 5: Transportasi -->
        <div class="tab-pane fade" id="e-transport">
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Jarak ke Sekolah (km)</label>
              <input type="text" name="jarak_sekolah" class="form-control" value="{{ old('jarak_sekolah', $siswa->jarak_sekolah) }}">
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Waktu Tempuh (menit)</label>
              <input type="text" name="waktu_tempuh" class="form-control" value="{{ old('waktu_tempuh', $siswa->waktu_tempuh) }}">
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Moda Transportasi</label>
              <input type="text" name="moda_transportasi" class="form-control" value="{{ old('moda_transportasi', $siswa->moda_transportasi) }}">
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Biaya Transportasi Harian</label>
              <input type="text" name="biaya_transportasi" class="form-control" value="{{ old('biaya_transportasi', $siswa->biaya_transportasi) }}">
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Kepemilikan Kendaraan Pribadi</label>
              <input type="text" name="status_kepemilikan_kendaraan" class="form-control" value="{{ old('status_kepemilikan_kendaraan', $siswa->status_kepemilikan_kendaraan) }}">
            </div>
          </div>
        </div>

        <!-- Tab 6: Biaya & Dokumen -->
        <div class="tab-pane fade" id="e-biaya">
          <div class="row g-3 mb-4">
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Nomor Kartu Keluarga (KK)</label>
              <input type="text" name="no_kk" class="form-control" value="{{ old('no_kk', $siswa->no_kk) }}">
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Kategori Siswa</label>
              <select name="kategori_siswa" class="form-select tom-select">
                <option value="">-- Pilih Kategori --</option>
                @foreach($kategoriList as $kat)
                  <option value="{{ $kat }}" {{ old('kategori_siswa', $siswa->kategori_siswa) == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Sumber Biaya Sekolah</label>
              <select name="sumber_biaya" class="form-select tom-select">
                <option value="">-- Pilih Sumber Biaya --</option>
                @foreach($sumberBiayaList as $sb)
                  <option value="{{ $sb }}" {{ old('sumber_biaya', $siswa->sumber_biaya) == $sb ? 'selected' : '' }}>{{ $sb }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Kategori IPP</label>
              <input type="text" name="kategori_ipp" class="form-control" value="{{ old('kategori_ipp', $siswa->kategori_ipp) }}">
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Nominal IPP (Rp)</label>
              <input type="number" name="nominal_ipp" class="form-control" value="{{ old('nominal_ipp', $siswa->nominal_ipp) }}">
            </div>
          </div>

          <h6 class="fw-bold text-primary mb-3 d-flex align-items-center gap-1"><x-heroicon-o-arrow-up-tray class="heroicon-sm" /> Perbarui Berkas & Dokumen (Kosongkan jika tidak diubah)</h6>
          <div class="row g-3">
            <div class="col-md-6" x-data="{
                previewUrl: '{{ $siswa->foto_url ?? '' }}',
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
              
              <!-- Tampilkan Foto Profil Siswa jika sudah ada -->
              <div x-show="previewUrl" class="d-flex align-items-center gap-3 mb-2 p-2 bg-light rounded-3 border">
                <img :src="previewUrl" alt="Foto Siswa" class="rounded-3 object-fit-cover shadow-xs border border-2 border-white" style="width: 80px; height: 80px;">
                <div class="overflow-hidden">
                  <div class="fw-semibold text-dark small text-truncate">{{ $siswa->foto ?? 'Foto Baru Dipilih' }}</div>
                  <div class="badge bg-success-subtle text-success border border-success-subtle mt-1 py-1 px-2 d-inline-flex align-items-center gap-1">
                    <x-heroicon-o-check-circle class="heroicon-sm" /> Foto Tersedia
                  </div>
                  @if($siswa->foto_url)
                    <div>
                      <a href="{{ $siswa->foto_url }}" target="_blank" class="small text-primary text-decoration-none mt-1 d-inline-flex align-items-center gap-1">
                        <x-heroicon-o-arrow-top-right-on-square class="heroicon-sm" /> Buka Foto Asli
                      </a>
                    </div>
                  @endif
                </div>
              </div>

              <input type="file" name="foto" class="form-control" accept="image/*" @change="newPreview">
              <div class="form-text small text-muted">
                @if($siswa->foto)
                  Pilih file gambar baru jika ingin mengganti foto profil siswa saat ini.
                @else
                  Unggah file foto profil siswa (JPG, JPEG, PNG, WEBP).
                @endif
              </div>
            </div>

            <div class="col-md-6">
              <label class="form-label small fw-semibold">Dokumen Kartu Keluarga (KK)</label>
              @if($siswa->dok_kk)
                @php $kkUrl = $siswa->getDokUrl('dok_kk'); @endphp
                <div class="d-flex align-items-center justify-content-between p-2 mb-2 bg-light rounded-3 border small">
                  <div class="d-flex align-items-center gap-2 overflow-hidden">
                    <x-heroicon-s-document-check class="heroicon text-success" />
                    <div class="text-truncate">
                      <div class="fw-semibold text-dark text-truncate">{{ $siswa->dok_kk }}</div>
                      <span class="badge bg-success-subtle text-success border border-success-subtle py-0.5 px-1.5" style="font-size: 0.68rem;">Berkas Tersimpan</span>
                    </div>
                  </div>
                  @if($kkUrl)
                    <a href="{{ $kkUrl }}" target="_blank" class="btn btn-sm btn-outline-primary py-0.5 px-2 text-nowrap d-inline-flex align-items-center gap-1">
                      <x-heroicon-o-eye class="heroicon-sm" /> Lihat Berkas
                    </a>
                  @endif
                </div>
              @endif
              <input type="file" name="dok_kk" class="form-control">
              <div class="form-text small text-muted">Format: PDF atau scan gambar (JPG, PNG).</div>
            </div>

            <div class="col-md-6">
              <label class="form-label small fw-semibold">Surat Keterangan Tidak Mampu (SKTM)</label>
              @if($siswa->dok_sktm)
                @php $sktmUrl = $siswa->getDokUrl('dok_sktm'); @endphp
                <div class="d-flex align-items-center justify-content-between p-2 mb-2 bg-light rounded-3 border small">
                  <div class="d-flex align-items-center gap-2 overflow-hidden">
                    <x-heroicon-s-document-check class="heroicon text-success" />
                    <div class="text-truncate">
                      <div class="fw-semibold text-dark text-truncate">{{ $siswa->dok_sktm }}</div>
                      <span class="badge bg-success-subtle text-success border border-success-subtle py-0.5 px-1.5" style="font-size: 0.68rem;">Berkas Tersimpan</span>
                    </div>
                  </div>
                  @if($sktmUrl)
                    <a href="{{ $sktmUrl }}" target="_blank" class="btn btn-sm btn-outline-primary py-0.5 px-2 text-nowrap d-inline-flex align-items-center gap-1">
                      <x-heroicon-o-eye class="heroicon-sm" /> Lihat Berkas
                    </a>
                  @endif
                </div>
              @endif
              <input type="file" name="dok_sktm" class="form-control">
              <div class="form-text small text-muted">Format: PDF atau scan gambar (JPG, PNG).</div>
            </div>

            <div class="col-md-6">
              <label class="form-label small fw-semibold">Slip Gaji / Bukti Penghasilan</label>
              @if($siswa->dok_slip_gaji)
                @php $slipUrl = $siswa->getDokUrl('dok_slip_gaji'); @endphp
                <div class="d-flex align-items-center justify-content-between p-2 mb-2 bg-light rounded-3 border small">
                  <div class="d-flex align-items-center gap-2 overflow-hidden">
                    <x-heroicon-s-document-check class="heroicon text-success" />
                    <div class="text-truncate">
                      <div class="fw-semibold text-dark text-truncate">{{ $siswa->dok_slip_gaji }}</div>
                      <span class="badge bg-success-subtle text-success border border-success-subtle py-0.5 px-1.5" style="font-size: 0.68rem;">Berkas Tersimpan</span>
                    </div>
                  </div>
                  @if($slipUrl)
                    <a href="{{ $slipUrl }}" target="_blank" class="btn btn-sm btn-outline-primary py-0.5 px-2 text-nowrap d-inline-flex align-items-center gap-1">
                      <x-heroicon-o-eye class="heroicon-sm" /> Lihat Berkas
                    </a>
                  @endif
                </div>
              @endif
              <input type="file" name="dok_slip_gaji" class="form-control">
              <div class="form-text small text-muted">Format: PDF atau scan gambar (JPG, PNG).</div>
            </div>

            <div class="col-md-6">
              <label class="form-label small fw-semibold">Kartu Bansos (KIP/PKH/KKS)</label>
              @if($siswa->dok_bansos)
                @php $bansosUrl = $siswa->getDokUrl('dok_bansos'); @endphp
                <div class="d-flex align-items-center justify-content-between p-2 mb-2 bg-light rounded-3 border small">
                  <div class="d-flex align-items-center gap-2 overflow-hidden">
                    <x-heroicon-s-document-check class="heroicon text-success" />
                    <div class="text-truncate">
                      <div class="fw-semibold text-dark text-truncate">{{ $siswa->dok_bansos }}</div>
                      <span class="badge bg-success-subtle text-success border border-success-subtle py-0.5 px-1.5" style="font-size: 0.68rem;">Berkas Tersimpan</span>
                    </div>
                  </div>
                  @if($bansosUrl)
                    <a href="{{ $bansosUrl }}" target="_blank" class="btn btn-sm btn-outline-primary py-0.5 px-2 text-nowrap d-inline-flex align-items-center gap-1">
                      <x-heroicon-o-eye class="heroicon-sm" /> Lihat Berkas
                    </a>
                  @endif
                </div>
              @endif
              <input type="file" name="dok_bansos" class="form-control">
              <div class="form-text small text-muted">Format: PDF atau scan gambar (JPG, PNG).</div>
            </div>

            <div class="col-md-6">
              <label class="form-label small fw-semibold">Keterangan Bansos Tambahan</label>
              <input type="text" name="ket_bansos" class="form-control" value="{{ old('ket_bansos', $siswa->ket_bansos) }}" placeholder="Contoh: Penerima KIP Tahap 1">
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card-footer bg-light d-flex justify-content-between">
      <a href="{{ route('siswa.index') }}" class="btn btn-outline-secondary">Batal</a>
      <button type="submit" class="btn btn-primary px-4 d-inline-flex align-items-center gap-1">
        <x-heroicon-o-check class="heroicon-sm" /> Perbarui Data Siswa
      </button>
    </div>
  </div>
</form>
@endsection