@extends('layouts.app')

@section('title', 'Data Referensi Master')
@section('page_title', 'Data Referensi & Master')
@section('page_subtitle', 'Tabel referensi tahun ajaran, pekerjaan, penghasilan, kategori siswa, dan sumber biaya')

@section('content')
<div class="row g-4">
  <!-- Tahun Ajaran -->
  <div class="col-md-6">
    <div class="card h-100 shadow-xs border">
      <div class="card-header bg-white d-flex justify-content-between align-items-center py-2.5">
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
                <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2" title="Edit" onclick="openEditMaster('{{ route('master.tahun_ajaran.update', $t->id_tahun_ajaran) }}', 'tahun_ajaran', '{{ addslashes($t->tahun_ajaran) }}', 'Edit Tahun Ajaran', 'Tahun Ajaran')">
                  <x-heroicon-o-pencil class="heroicon-sm" />
                </button>
                @if(!$isActive)
                  <form method="POST" action="{{ route('master.tahun_ajaran.set_active', $t->id_tahun_ajaran) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-success py-0 px-2 d-inline-flex align-items-center gap-1" title="Jadikan Tahun Ajaran Aktif" data-confirm-title="Ganti Tahun Ajaran Aktif" data-confirm="Jadikan {{ $t->tahun_ajaran }} sebagai tahun ajaran aktif sistem?" data-confirm-icon="question" data-confirm-btn="Ya, Aktifkan" data-confirm-btn-color="#16a34a">
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
    <div class="card h-100 shadow-xs border">
      <div class="card-header bg-white d-flex justify-content-between align-items-center py-2.5">
        <div>
          <div class="fw-bold d-flex align-items-center gap-1"><x-heroicon-o-tag class="heroicon-sm text-primary" /> Kategori Siswa</div>
          <div class="text-muted small">Contoh: Reguler / Yatim / Afirmasi / Panti Asuhan</div>
        </div>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('master.kategori.store') }}" class="d-flex gap-2 mb-3">
          @csrf
          <input type="text" name="nama_kategori" class="form-control form-control-sm" placeholder="Contoh: Reguler / Yatim / Afirmasi" required>
          <button type="submit" class="btn btn-primary btn-sm text-nowrap d-inline-flex align-items-center gap-1"><x-heroicon-o-plus class="heroicon-sm" /> Tambah</button>
        </form>
        <ul class="list-group list-group-flush small">
          @foreach($kategoriList as $k)
            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
              <span class="fw-semibold text-dark">{{ $k->nama_kategori }}</span>
              <div class="d-flex align-items-center gap-1">
                <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2" title="Edit" onclick="openEditMaster('{{ route('master.kategori.update', $k->id_kategori) }}', 'nama_kategori', '{{ addslashes($k->nama_kategori) }}', 'Edit Kategori Siswa', 'Nama Kategori Siswa')">
                  <x-heroicon-o-pencil class="heroicon-sm" />
                </button>
                <form method="POST" action="{{ route('master.kategori.destroy', $k->id_kategori) }}">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-outline-danger btn-sm py-0 px-2 btn-delete-confirm" title="Hapus"><x-heroicon-o-trash class="heroicon-sm" /></button>
                </form>
              </div>
            </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>

  <!-- Range Penghasilan -->
  <div class="col-md-6">
    <div class="card h-100 shadow-xs border">
      <div class="card-header bg-white d-flex justify-content-between align-items-center py-2.5">
        <div>
          <div class="fw-bold d-flex align-items-center gap-1"><x-heroicon-o-banknotes class="heroicon-sm text-primary" /> Rentang Penghasilan Orang Tua</div>
          <div class="text-muted small">Contoh: Rp 1.000.000 - Rp 2.000.000</div>
        </div>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('master.penghasilan.store') }}" class="d-flex gap-2 mb-3">
          @csrf
          <input type="text" name="range_penghasilan" class="form-control form-control-sm" placeholder="Contoh: Rp 1.000.000 - Rp 2.000.000" required>
          <button type="submit" class="btn btn-primary btn-sm text-nowrap d-inline-flex align-items-center gap-1"><x-heroicon-o-plus class="heroicon-sm" /> Tambah</button>
        </form>
        <ul class="list-group list-group-flush small">
          @foreach($penghasilanList as $p)
            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
              <span class="fw-semibold text-dark">{{ $p->range_penghasilan }}</span>
              <div class="d-flex align-items-center gap-1">
                <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2" title="Edit" onclick="openEditMaster('{{ route('master.penghasilan.update', $p->id_penghasilan) }}', 'range_penghasilan', '{{ addslashes($p->range_penghasilan) }}', 'Edit Rentang Penghasilan', 'Rentang Penghasilan')">
                  <x-heroicon-o-pencil class="heroicon-sm" />
                </button>
                <form method="POST" action="{{ route('master.penghasilan.destroy', $p->id_penghasilan) }}">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-outline-danger btn-sm py-0 px-2 btn-delete-confirm" title="Hapus"><x-heroicon-o-trash class="heroicon-sm" /></button>
                </form>
              </div>
            </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>

  <!-- Sumber Biaya -->
  <div class="col-md-6">
    <div class="card h-100 shadow-xs border">
      <div class="card-header bg-white d-flex justify-content-between align-items-center py-2.5">
        <div>
          <div class="fw-bold d-flex align-items-center gap-1"><x-heroicon-o-wallet class="heroicon-sm text-primary" /> Sumber Biaya Pendidikan</div>
          <div class="text-muted small">Contoh: Orang Tua / Wali / Beasiswa</div>
        </div>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('master.sumber_biaya.store') }}" class="d-flex gap-2 mb-3">
          @csrf
          <input type="text" name="nama_sumber_biaya" class="form-control form-control-sm" placeholder="Contoh: Orang Tua / Beasiswa" required>
          <button type="submit" class="btn btn-primary btn-sm text-nowrap d-inline-flex align-items-center gap-1"><x-heroicon-o-plus class="heroicon-sm" /> Tambah</button>
        </form>
        <ul class="list-group list-group-flush small">
          @foreach($sumberBiayaList as $sb)
            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
              <span class="fw-semibold text-dark">{{ $sb->nama_sumber_biaya }}</span>
              <div class="d-flex align-items-center gap-1">
                <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2" title="Edit" onclick="openEditMaster('{{ route('master.sumber_biaya.update', $sb->id_sumber_biaya) }}', 'nama_sumber_biaya', '{{ addslashes($sb->nama_sumber_biaya) }}', 'Edit Sumber Biaya', 'Nama Sumber Biaya')">
                  <x-heroicon-o-pencil class="heroicon-sm" />
                </button>
                <form method="POST" action="{{ route('master.sumber_biaya.destroy', $sb->id_sumber_biaya) }}">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-outline-danger btn-sm py-0 px-2 btn-delete-confirm" title="Hapus"><x-heroicon-o-trash class="heroicon-sm" /></button>
                </form>
              </div>
            </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>

  <!-- Pekerjaan Referensi -->
  <div class="col-md-6">
    <div class="card h-100 shadow-xs border">
      <div class="card-header bg-white d-flex justify-content-between align-items-center py-2.5">
        <div>
          <div class="fw-bold d-flex align-items-center gap-1"><x-heroicon-o-briefcase class="heroicon-sm text-primary" /> Pekerjaan Orang Tua / Wali</div>
          <div class="text-muted small">Daftar referensi pilihan pekerjaan</div>
        </div>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('master.pekerjaan.store') }}" class="d-flex gap-2 mb-3">
          @csrf
          <input type="text" name="nama_pekerjaan" class="form-control form-control-sm" placeholder="Contoh: Petani / PNS / Wiraswasta" required>
          <button type="submit" class="btn btn-primary btn-sm text-nowrap d-inline-flex align-items-center gap-1"><x-heroicon-o-plus class="heroicon-sm" /> Tambah</button>
        </form>
        <ul class="list-group list-group-flush small" style="max-height: 380px; overflow-y: auto;">
          @foreach($pekerjaanList as $pk)
            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
              <span class="fw-semibold text-dark">{{ $pk->nama_pekerjaan }}</span>
              <div class="d-flex align-items-center gap-1">
                <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2" title="Edit" onclick="openEditMaster('{{ route('master.pekerjaan.update', $pk->id_pekerjaan) }}', 'nama_pekerjaan', '{{ addslashes($pk->nama_pekerjaan) }}', 'Edit Pekerjaan', 'Nama Pekerjaan')">
                  <x-heroicon-o-pencil class="heroicon-sm" />
                </button>
                <form method="POST" action="{{ route('master.pekerjaan.destroy', $pk->id_pekerjaan) }}">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-outline-danger btn-sm py-0 px-2 btn-delete-confirm" title="Hapus"><x-heroicon-o-trash class="heroicon-sm" /></button>
                </form>
              </div>
            </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
</div>

<!-- Modal Edit Master Reusable -->
<div class="modal fade" id="modalEditMaster" tabindex="-1" aria-labelledby="modalEditTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form method="POST" id="formEditMaster" class="modal-content shadow">
      @csrf
      @method('PUT')
      <div class="modal-header py-2.5">
        <h6 class="modal-title fw-bold text-dark d-flex align-items-center gap-1" id="modalEditTitle">
          <x-heroicon-o-pencil-square class="heroicon-sm text-primary" /> Edit Data Referensi
        </h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label small fw-semibold text-dark" id="modalEditLabel">Nilai Referensi</label>
          <input type="text" name="value" id="modalEditInput" class="form-control" required autofocus>
        </div>
      </div>
      <div class="modal-footer py-2 bg-light">
        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1">
          <x-heroicon-o-check class="heroicon-sm" /> Simpan Perubahan
        </button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
  function openEditMaster(url, fieldName, currentValue, title, label) {
    const form = document.getElementById('formEditMaster');
    form.action = url;

    const input = document.getElementById('modalEditInput');
    input.name = fieldName;
    input.value = currentValue;

    document.getElementById('modalEditTitle').innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:16px;height:16px;" class="text-primary me-1"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>' + title;
    document.getElementById('modalEditLabel').innerText = label;

    const modal = new bootstrap.Modal(document.getElementById('modalEditMaster'));
    modal.show();

    setTimeout(() => input.focus(), 400);
  }
</script>
@endpush
@endsection