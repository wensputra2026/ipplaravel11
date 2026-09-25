@extends('layouts.app')

@section('title', 'Detail GTK - ' . $gtk->nama)
@section('page_title', 'Profil Guru & Tenaga Kependidikan')
@section('page_subtitle', 'Biodata lengkap, data kepegawaian, dan informasi kontak pendidik')

@section('page_actions')
  <a href="{{ route('gtk.edit', $gtk->id) }}" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1">
    <x-heroicon-o-pencil-square class="heroicon-sm" /> Edit GTK
  </a>
  <a href="{{ route('gtk.index') }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
    <x-heroicon-o-arrow-left class="heroicon-sm" /> Kembali
  </a>
@endsection

@section('content')
<div class="row g-4">
  <div class="col-lg-4">
    <div class="card text-center p-4">
      <div class="mb-3">
        @if($gtk->foto && file_exists(public_path('uploads/gtk/' . $gtk->foto)))
          <img src="{{ asset('uploads/gtk/' . $gtk->foto) }}" alt="Foto" width="110" height="110" class="rounded-circle object-fit-cover shadow-sm border border-3 border-primary-subtle">
        @else
          <div class="rounded-circle bg-light text-muted d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 110px; height: 110px;">
            <x-heroicon-o-user class="heroicon-lg text-secondary" style="width: 50px; height: 50px;" />
          </div>
        @endif
      </div>
      <h5 class="fw-bold mb-1">{{ $gtk->nama }}</h5>
      <div class="text-muted small mb-3">NIP: {{ $gtk->nip ?: '-' }}</div>
      <div>
        <span class="badge bg-primary px-3 py-2 rounded-pill">{{ $gtk->jenis_ptk ?: 'Pendidik' }}</span>
        <span class="badge bg-secondary px-3 py-2 rounded-pill">{{ $gtk->status_kepegawaian ?: 'Honorer' }}</span>
      </div>
    </div>
  </div>

  <div class="col-lg-8">
    <div class="card">
      <div class="card-header bg-white">
        <div class="fw-bold d-flex align-items-center gap-1"><x-heroicon-o-identification class="heroicon-sm text-primary" /> Rincian Biodata GTK</div>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-sm-6">
            <label class="text-muted small">NUPTK</label>
            <div class="fw-semibold">{{ $gtk->nuptk ?: '-' }}</div>
          </div>
          <div class="col-sm-6">
            <label class="text-muted small">Jenis Kelamin</label>
            <div class="fw-semibold">{{ $gtk->jk == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
          </div>
          <div class="col-sm-6">
            <label class="text-muted small">Tempat, Tanggal Lahir</label>
            <div class="fw-semibold">{{ $gtk->tempat_lahir ? $gtk->tempat_lahir . ', ' : '' }}{{ $gtk->tanggal_lahir ? (\Carbon\Carbon::canBeCreatedFromFormat($gtk->tanggal_lahir, 'Y-m-d') || strtotime($gtk->tanggal_lahir) ? \Carbon\Carbon::parse($gtk->tanggal_lahir)->translatedFormat('d F Y') : $gtk->tanggal_lahir) : '-' }}</div>
          </div>
          <div class="col-sm-6">
            <label class="text-muted small">Agama</label>
            <div class="fw-semibold">{{ $gtk->agama ?: '-' }}</div>
          </div>
          <div class="col-sm-6">
            <label class="text-muted small">Nomor HP</label>
            <div class="fw-semibold">{{ $gtk->hp ?: '-' }}</div>
          </div>
          <div class="col-sm-6">
            <label class="text-muted small">Email</label>
            <div class="fw-semibold">{{ $gtk->email ?: '-' }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection