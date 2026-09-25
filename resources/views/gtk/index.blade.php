@extends('layouts.app')

@section('title', 'Data GTK')
@section('page_title', 'Guru & Tenaga Kependidikan')
@section('page_subtitle', 'Manajemen profil, kepegawaian, jabatan, dan biodata GTK')

@section('page_actions')
  <a href="{{ route('gtk.create') }}" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1">
    <x-heroicon-o-user-plus class="heroicon-sm" /> Tambah GTK
  </a>
@endsection

@section('content')
<div class="card">
  <div class="card-header bg-white">
    <form method="GET" action="{{ route('gtk.index') }}" class="row g-2 align-items-center w-100">
      <div class="col-md-6">
        <div class="input-group input-group-sm">
          <input type="text" name="search" class="form-control" placeholder="Cari nama, NIP, atau NUPTK..." value="{{ request('search') }}">
          <button class="btn btn-primary d-inline-flex align-items-center gap-1" type="submit">
            <x-heroicon-o-magnifying-glass class="heroicon-sm" /> Cari
          </button>
        </div>
      </div>
      <div class="col-md-6 text-md-end">
        @if(request('search'))
          <a href="{{ route('gtk.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
        @endif
      </div>
    </form>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr>
            <th style="width: 50px;">No</th>
            <th>Foto</th>
            <th>Nama Lengkap</th>
            <th>NIP / NUPTK</th>
            <th>Jenis PTK</th>
            <th>Status Kepegawaian</th>
            <th class="text-end" style="width: 140px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($gtkList as $idx => $g)
            <tr>
              <td>{{ $gtkList->firstItem() + $idx }}</td>
              <td>
                @if($g->foto && file_exists(public_path('uploads/gtk/' . $g->foto)))
                  <img src="{{ asset('uploads/gtk/' . $g->foto) }}" alt="Foto" width="34" height="34" class="rounded-circle object-fit-cover">
                @else
                  <div class="rounded-circle bg-light text-muted d-flex align-items-center justify-content-center" style="width: 34px; height: 34px; font-size: 0.8rem;">
                    <x-heroicon-o-user class="heroicon-sm text-secondary" />
                  </div>
                @endif
              </td>
              <td>
                <a href="{{ route('gtk.show', $g->id) }}" class="fw-bold text-decoration-none text-dark">
                  {{ $g->nama }}
                </a>
              </td>
              <td>
                <div>NIP: {{ $g->nip ?: '-' }}</div>
                <div class="text-muted small">NUPTK: {{ $g->nuptk ?: '-' }}</div>
              </td>
              <td><span class="badge bg-primary-subtle text-primary">{{ $g->jenis_ptk ?: '-' }}</span></td>
              <td><span class="badge bg-secondary-subtle text-secondary">{{ $g->status_kepegawaian ?: '-' }}</span></td>
              <td class="text-end">
                <div class="btn-group btn-group-sm">
                  <a href="{{ route('gtk.show', $g->id) }}" class="btn btn-outline-info" title="Detail">
                    <x-heroicon-o-eye class="heroicon-sm" />
                  </a>
                  <a href="{{ route('gtk.edit', $g->id) }}" class="btn btn-outline-primary" title="Edit">
                    <x-heroicon-o-pencil-square class="heroicon-sm" />
                  </a>
                  <form method="POST" action="{{ route('gtk.destroy', $g->id) }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-outline-danger btn-delete-confirm" title="Hapus">
                      <x-heroicon-o-trash class="heroicon-sm" />
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center text-muted py-5">Belum ada data Guru & Tenaga Kependidikan</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if($gtkList->hasPages())
    <div class="card-footer bg-white border-top d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 py-3">
      <div class="small text-muted text-center text-sm-start">
        Menampilkan <strong>{{ $gtkList->firstItem() }}</strong> s/d <strong>{{ $gtkList->lastItem() }}</strong> dari <strong>{{ $gtkList->total() }}</strong> data
      </div>
      <div>{{ $gtkList->links('pagination::bootstrap-5') }}</div>
    </div>
  @endif
</div>
@endsection