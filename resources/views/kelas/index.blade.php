@extends('layouts.app')

@section('title', 'Data Rombel Kelas')
@section('page_title', 'Data Rombel Kelas')
@section('page_subtitle', 'Manajemen daftar kelas, tingkat, jurusan, dan jumlah siswa terdaftar')

@section('page_actions')
  <button type="button" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#modalTambahKelas">
    <x-heroicon-o-plus class="heroicon-sm" /> Tambah Kelas
  </button>
@endsection

@section('content')
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
            <th class="text-center" style="width: 140px;">Jumlah Siswa</th>
            <th class="text-end" style="width: 130px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($kelas as $idx => $k)
            <tr>
              <td>{{ $idx + 1 }}</td>
              <td class="fw-bold">
                <a href="{{ route('siswa.index', ['kelas_id' => $k->id]) }}" class="text-dark text-decoration-none">
                  {{ $k->nama_kelas }}
                </a>
              </td>
              <td><span class="badge bg-primary-subtle text-primary">{{ $k->tingkat }}</span></td>
              <td>{{ $k->jurusan ?: '-' }}</td>
              <td><span class="badge bg-light text-secondary border">{{ $k->tahun_ajaran }}</span></td>
              <td class="text-center">
                <a href="{{ route('siswa.index', ['kelas_id' => $k->id]) }}" class="badge bg-success-subtle text-success text-decoration-none px-2 py-1 d-inline-flex align-items-center gap-1">
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