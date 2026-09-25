@extends('layouts.app')

@section('title', 'Recent Activity')
@section('page_title', 'Recent Activity (Log Sistem)')
@section('page_subtitle', 'Audit trail rekaman aktivitas pengguna dan perubahan data sistem')

@section('content')
<div class="card">
  <div class="card-header bg-white">
    <form method="GET" action="{{ route('activity.index') }}" class="row g-2 align-items-center w-100">
      <div class="col-md-3">
        <select name="module" class="form-select form-select-sm" onchange="this.form.submit()">
          <option value="">-- Semua Modul --</option>
          @foreach($modules as $m)
            <option value="{{ $m }}" {{ request('module') === $m ? 'selected' : '' }}>{{ strtoupper($m) }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-5">
        <div class="input-group input-group-sm">
          <input type="text" name="search" class="form-control" placeholder="Cari nama pengguna, aktivitas..." value="{{ request('search') }}">
          <button class="btn btn-primary d-inline-flex align-items-center gap-1" type="submit"><x-heroicon-o-magnifying-glass class="heroicon-sm" /> Cari</button>
        </div>
      </div>
      <div class="col-md-4 text-md-end">
        @if(request()->anyFilled(['module', 'search']))
          <a href="{{ route('activity.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
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
            <th>Waktu</th>
            <th>Pengguna</th>
            <th>Modul</th>
            <th>Aksi</th>
            <th>Deskripsi Aktivitas</th>
            <th>Alamat IP</th>
          </tr>
        </thead>
        <tbody>
          @forelse($logs as $idx => $l)
            <tr>
              <td>{{ $logs->firstItem() + $idx }}</td>
              <td class="small text-muted" style="white-space: nowrap;">
                {{ $l->created_at ? \Carbon\Carbon::parse($l->created_at)->translatedFormat('d M Y, H:i') : '-' }}
                <div class="small text-muted">{{ $l->created_at ? \Carbon\Carbon::parse($l->created_at)->diffForHumans() : '' }}</div>
              </td>
              <td class="fw-bold">{{ $l->username }}</td>
              <td><span class="badge bg-light text-secondary border">{{ strtoupper($l->module) }}</span></td>
              <td><span class="badge bg-primary-subtle text-primary">{{ $l->activity_type }}</span></td>
              <td>{{ $l->description }}</td>
              <td class="small text-muted">{{ $l->ip_address }}</td>
            </tr>
          @empty
            <tr><td colspan="7" class="text-center text-muted py-5">Belum ada riwayat aktivitas tercatat</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if($logs->hasPages())
    <div class="card-footer bg-white border-top d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 py-3">
      <div class="small text-muted text-center text-sm-start">Menampilkan <strong>{{ $logs->firstItem() }}</strong> s/d <strong>{{ $logs->lastItem() }}</strong> dari <strong>{{ $logs->total() }}</strong> entri log</div>
      <div>{{ $logs->links('pagination::bootstrap-5') }}</div>
    </div>
  @endif
</div>
@endsection