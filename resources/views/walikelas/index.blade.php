@extends('layouts.app')

@section('title', 'Data Wali Kelas')
@section('page_title', 'Penugasan Wali Kelas')
@section('page_subtitle', 'Penetapan Guru sebagai Wali Kelas per rombel dan tahun ajaran aktif')

@section('page_actions')
  <button type="button" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#modalTambahWali">
    <x-heroicon-o-user-plus class="heroicon-sm" /> Tambah Wali Kelas
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
            <th>Guru / GTK</th>
            <th>Kelas Binaan</th>
            <th>Tahun Ajaran</th>
            <th>Semester</th>
            <th class="text-end" style="width: 100px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($walikelasList as $idx => $w)
            <tr>
              <td>{{ $idx + 1 }}</td>
              <td>
                <div class="fw-bold">{{ $w->gtk->nama ?? 'Tidak Ditemukan' }}</div>
                <div class="text-muted small">NIP: {{ $w->gtk->nip ?: '-' }} | NUPTK: {{ $w->gtk->nuptk ?: '-' }}</div>
              </td>
              <td>
                <span class="badge bg-primary-subtle text-primary fs-6">{{ $w->kelas->nama_kelas ?? 'Belum Diatur' }}</span>
              </td>
              <td><span class="badge bg-light text-secondary border">{{ $w->tahun_ajaran }}</span></td>
              <td>{{ $w->semester }}</td>
              <td class="text-end text-nowrap">
                <button type="button" class="btn btn-outline-primary btn-sm me-1" data-bs-toggle="modal" data-bs-target="#modalEditWali{{ $w->id_walikelas }}" title="Edit Penugasan">
                  <x-heroicon-o-pencil-square class="heroicon-sm" />
                </button>
                <form method="POST" action="{{ route('walikelas.destroy', $w->id_walikelas) }}" class="d-inline">
                  @csrf
                  @method('DELETE')
                  <button type="button" class="btn btn-outline-danger btn-sm btn-delete-confirm" title="Hapus">
                    <x-heroicon-o-trash class="heroicon-sm" />
                  </button>
                </form>

                <!-- Modal Edit Wali Kelas -->
                <div class="modal fade text-start" id="modalEditWali{{ $w->id_walikelas }}" tabindex="-1">
                  <div class="modal-dialog">
                    <form method="POST" action="{{ route('walikelas.update', $w->id_walikelas) }}">
                      @csrf
                      @method('PUT')
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title fw-bold">Edit Penugasan Wali Kelas</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                          <div class="mb-3">
                            <label class="form-label small fw-semibold">Pilih Guru / GTK <span class="text-danger">*</span></label>
                            <select name="id_gtk" class="form-select" required>
                              @foreach($gtkList as $g)
                                <option value="{{ $g->id }}" {{ $w->id_gtk == $g->id ? 'selected' : '' }}>{{ $g->nama }} (NIP: {{ $g->nip ?: '-' }})</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="mb-3">
                            <label class="form-label small fw-semibold">Pilih Kelas Binaan <span class="text-danger">*</span></label>
                            <select name="id_kelas" class="form-select" required>
                              @foreach($kelasList as $k)
                                <option value="{{ $k->id }}" {{ $w->id_kelas == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }} ({{ $k->tingkat }})</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="mb-3">
                            <label class="form-label small fw-semibold">Tahun Ajaran <span class="text-danger">*</span></label>
                            <input type="text" name="tahun_ajaran" class="form-control" value="{{ $w->tahun_ajaran }}" required>
                          </div>
                          <div class="mb-3">
                            <label class="form-label small fw-semibold">Semester <span class="text-danger">*</span></label>
                            <select name="semester" class="form-select" required>
                              <option value="Ganjil" {{ $w->semester === 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                              <option value="Genap" {{ $w->semester === 'Genap' ? 'selected' : '' }}>Genap</option>
                            </select>
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
              <td colspan="6" class="text-center text-muted py-5">Belum ada data penugasan wali kelas</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if($walikelasList->hasPages())
    <div class="card-footer bg-white border-top d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 py-3">
      <div class="small text-muted text-center text-sm-start">
        Menampilkan <strong>{{ $walikelasList->firstItem() }}</strong> s/d <strong>{{ $walikelasList->lastItem() }}</strong> dari <strong>{{ $walikelasList->total() }}</strong> data
      </div>
      <div>{{ $walikelasList->links('pagination::bootstrap-5') }}</div>
    </div>
  @endif
</div>

<!-- Modal Tambah Wali Kelas -->
<div class="modal fade" id="modalTambahWali" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('walikelas.store') }}">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fw-bold">Penugasan Wali Kelas</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label small fw-semibold">Pilih Guru / GTK <span class="text-danger">*</span></label>
            <select name="id_gtk" class="form-select" required>
              <option value="">-- Pilih GTK --</option>
              @foreach($gtkList as $g)
                <option value="{{ $g->id }}">{{ $g->nama }} (NIP: {{ $g->nip ?: '-' }})</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Pilih Kelas Binaan <span class="text-danger">*</span></label>
            <select name="id_kelas" class="form-select" required>
              <option value="">-- Pilih Kelas --</option>
              @foreach($kelasList as $k)
                <option value="{{ $k->id }}">{{ $k->nama_kelas }} ({{ $k->tingkat }})</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Tahun Ajaran <span class="text-danger">*</span></label>
            <input type="text" name="tahun_ajaran" class="form-control" value="{{ $activeTa }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Semester <span class="text-danger">*</span></label>
            <select name="semester" class="form-select" required>
              <option value="Ganjil" {{ $activeSem == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
              <option value="Genap" {{ $activeSem == 'Genap' ? 'selected' : '' }}>Genap</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Penugasan</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection