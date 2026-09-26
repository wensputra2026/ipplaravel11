@extends('layouts.app')

@section('title', 'Data Rombel Kelas')
@section('page_title', 'Data Rombel Kelas')
@section('page_subtitle', 'Manajemen rombel kelas dan jumlah siswa terdaftar — Periode: TA ' . $selectedTa . ' (' . $selectedSem . ')')

@section('page_actions')
  <form method="GET" action="{{ route('kelas.index') }}" class="d-flex align-items-center gap-2">
    <div class="input-group input-group-sm">
      <span class="input-group-text bg-white border-end-0 text-muted">
        <x-heroicon-o-calendar class="heroicon-sm text-primary" />
      </span>
      <select name="ta" class="form-select form-select-sm border-start-0 ps-1" onchange="this.form.submit()" title="Pilih Tahun Ajaran">
        @foreach($availableTaList as $ta)
          <option value="{{ $ta }}" {{ $selectedTa == $ta ? 'selected' : '' }}>
            TA {{ $ta }} {{ $ta == $activeTa ? '(Aktif)' : '' }}
          </option>
        @endforeach
      </select>
      <select name="semester" class="form-select form-select-sm" onchange="this.form.submit()" title="Pilih Semester">
        <option value="Ganjil" {{ $selectedSem == 'Ganjil' ? 'selected' : '' }}>Ganjil {{ ($selectedTa == $activeTa && $activeSem == 'Ganjil') ? '(Aktif)' : '' }}</option>
        <option value="Genap" {{ $selectedSem == 'Genap' ? 'selected' : '' }}>Genap {{ ($selectedTa == $activeTa && $activeSem == 'Genap') ? '(Aktif)' : '' }}</option>
      </select>
    </div>
    @if($selectedTa != $activeTa || $selectedSem != $activeSem)
      <a href="{{ route('kelas.index') }}" class="btn btn-outline-secondary btn-sm" title="Kembali ke Periode Berjalan">
        Reset
      </a>
    @endif
    <button type="button" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1 shadow-xs" data-bs-toggle="modal" data-bs-target="#modalTambahKelas">
      <x-heroicon-o-plus class="heroicon-sm" /> Tambah Kelas
    </button>
  </form>
@endsection

@section('content')
@if($selectedTa != $activeTa || $selectedSem != $activeSem)
  <div class="alert alert-info py-2 px-3 mb-3 d-flex align-items-center justify-content-between rounded-3 border-0 shadow-xs">
    <div class="small d-flex align-items-center gap-1.5">
      <x-heroicon-o-information-circle class="heroicon-sm text-info" />
      Menampilkan rombel kelas dan jumlah siswa untuk periode <strong>Tahun Ajaran {{ $selectedTa }} - Semester {{ $selectedSem }}</strong>. (Periode Berjalan: {{ $activeTa }} {{ $activeSem }})
    </div>
    <a href="{{ route('kelas.index') }}" class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size: 0.75rem;">Kembali ke Periode Berjalan</a>
  </div>
@endif

<div class="card">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr>
            <th style="width: 50px;">No</th>
            <th>Nama Kelas</th>
            <th>Tingkat</th>
            <th>Jurusan / Peminatan</th>
            <th>Tahun Ajaran</th>
            <th class="text-center" style="width: 150px;">Jumlah Siswa ({{ $selectedSem }})</th>
            <th class="text-end" style="width: 130px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($kelas as $idx => $k)
            <tr>
              <td>{{ $kelas->firstItem() ? ($kelas->firstItem() + $idx) : ($idx + 1) }}</td>
              <td class="fw-bold">
                <a href="{{ route('siswa.index', ['kelas_id' => $k->id, 'tahun_ajaran' => $selectedTa, 'semester' => $selectedSem]) }}" class="text-dark text-decoration-none">
                  {{ $k->nama_kelas }}
                </a>
              </td>
              <td><span class="badge bg-primary-subtle text-primary">{{ $k->tingkat }}</span></td>
              <td>{{ $k->jurusan ?: '-' }}</td>
              <td><span class="badge bg-light text-secondary border">{{ $k->tahun_ajaran }}</span></td>
              <td class="text-center">
                <a href="{{ route('siswa.index', ['kelas_id' => $k->id, 'tahun_ajaran' => $selectedTa, 'semester' => $selectedSem]) }}" class="badge bg-success-subtle text-success text-decoration-none px-2 py-1 d-inline-flex align-items-center gap-1" title="Lihat siswa kelas ini pada TA {{ $selectedTa }} ({{ $selectedSem }})">
                  <x-heroicon-o-users class="heroicon-sm" /> {{ $k->siswa_count }} Siswa
                </a>
              </td>
              <td class="text-end">
                <div class="btn-group btn-group-sm">
                  <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEditKelas{{ $k->id }}" title="Edit">
                    <x-heroicon-o-pencil-square class="heroicon-sm" />
                  </button>
                  <form method="POST" action="{{ route('kelas.destroy', $k->id) }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-outline-danger btn-delete-confirm" title="Hapus">
                      <x-heroicon-o-trash class="heroicon-sm" />
                    </button>
                  </form>
                </div>

                <!-- Modal Edit Kelas -->
                <div class="modal fade text-start" id="modalEditKelas{{ $k->id }}" tabindex="-1">
                  <div class="modal-dialog">
                    <form method="POST" action="{{ route('kelas.update', $k->id) }}">
                      @csrf
                      @method('PUT')
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title fw-bold">Edit Kelas - {{ $k->nama_kelas }}</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                          <div class="mb-3">
                            <label class="form-label small fw-semibold">Nama Kelas <span class="text-danger">*</span></label>
                            <input type="text" name="nama_kelas" class="form-control" value="{{ $k->nama_kelas }}" required>
                          </div>
                          <div class="mb-3">
                            <label class="form-label small fw-semibold">Tingkat <span class="text-danger">*</span></label>
                            <select name="tingkat" class="form-select" required>
                              @foreach(['X', 'XI', 'XII'] as $t)
                                <option value="{{ $t }}" {{ $k->tingkat == $t ? 'selected' : '' }}>Kelas {{ $t }}</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="mb-3">
                            <label class="form-label small fw-semibold">Jurusan / Peminatan</label>
                            <input type="text" name="jurusan" class="form-control" value="{{ $k->jurusan }}" placeholder="IPA / IPS / Umum">
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center text-muted py-5">Belum ada data rombel kelas</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if($kelas->hasPages())
    <div class="card-footer bg-white border-top d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 py-3">
      <div class="small text-muted text-center text-sm-start">
        Menampilkan <strong>{{ $kelas->firstItem() }}</strong> s/d <strong>{{ $kelas->lastItem() }}</strong> dari <strong>{{ $kelas->total() }}</strong> data
      </div>
      <div>{{ $kelas->links('pagination::bootstrap-5') }}</div>
    </div>
  @endif
</div>

<!-- Modal Tambah Kelas -->
<div class="modal fade" id="modalTambahKelas" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('kelas.store') }}">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fw-bold">Tambah Rombel Kelas</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label small fw-semibold">Nama Kelas <span class="text-danger">*</span></label>
            <input type="text" name="nama_kelas" class="form-control" placeholder="Contoh: X-1, XI-MIPA-1" required>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Tingkat <span class="text-danger">*</span></label>
            <select name="tingkat" class="form-select" required>
              <option value="X">Kelas X</option>
              <option value="XI">Kelas XI</option>
              <option value="XII">Kelas XII</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Jurusan / Peminatan</label>
            <input type="text" name="jurusan" class="form-control" placeholder="Contoh: IPA / IPS / Umum">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Tambah Kelas</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection