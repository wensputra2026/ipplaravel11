@extends('layouts.app')

@section('title', 'Tambah GTK Baru')
@section('page_title', 'Tambah Guru / Tenaga Kependidikan')
@section('page_subtitle', 'Entri data identitas, kepegawaian, dan kontak pendidik')

@section('page_actions')
  <a href="{{ route('gtk.index') }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
    <x-heroicon-o-arrow-left class="heroicon-sm" /> Kembali
  </a>
@endsection

@section('content')
<form method="POST" action="{{ route('gtk.store') }}" enctype="multipart/form-data">
  @csrf
  <div class="card">
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label small fw-semibold">Nama Lengkap & Gelar <span class="text-danger">*</span></label>
          <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
        </div>
        <div class="col-md-3">
          <label class="form-label small fw-semibold">NIP</label>
          <input type="text" name="nip" class="form-control" value="{{ old('nip') }}">
        </div>
        <div class="col-md-3">
          <label class="form-label small fw-semibold">NUPTK</label>
          <input type="text" name="nuptk" class="form-control" value="{{ old('nuptk') }}">
        </div>
        <div class="col-md-4">
          <label class="form-label small fw-semibold">Jenis Kelamin</label>
          <select name="jk" class="form-select">
            <option value="L">Laki-laki</option>
            <option value="P">Perempuan</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label small fw-semibold">Jenis PTK</label>
          <input type="text" name="jenis_ptk" class="form-control" value="{{ old('jenis_ptk') }}" placeholder="Guru Mapel / Guru BK / Tendik">
        </div>
        <div class="col-md-4">
          <label class="form-label small fw-semibold">Status Kepegawaian</label>
          <input type="text" name="status_kepegawaian" class="form-control" value="{{ old('status_kepegawaian') }}" placeholder="PNS / PPPK / GTT / Honorer">
        </div>
        <div class="col-md-6">
          <label class="form-label small fw-semibold">No HP / WhatsApp</label>
          <input type="text" name="hp" class="form-control" value="{{ old('hp') }}">
        </div>
        <div class="col-md-6">
          <label class="form-label small fw-semibold">Email</label>
          <input type="email" name="email" class="form-control" value="{{ old('email') }}">
        </div>
        <div class="col-md-12">
          <label class="form-label small fw-semibold">Foto Profil GTK</label>
          <input type="file" name="foto" class="form-control" accept="image/*">
        </div>
      </div>
    </div>
    <div class="card-footer bg-light d-flex justify-content-between">
      <a href="{{ route('gtk.index') }}" class="btn btn-outline-secondary">Batal</a>
      <button type="submit" class="btn btn-primary px-4 d-inline-flex align-items-center gap-1">
        <x-heroicon-o-check class="heroicon-sm" /> Simpan Data GTK
      </button>
    </div>
  </div>
</form>
@endsection