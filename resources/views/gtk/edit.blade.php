@extends('layouts.app')

@section('title', 'Edit GTK - ' . $gtk->nama)
@section('page_title', 'Perbarui Data GTK')
@section('page_subtitle', 'Edit data identitas, kepegawaian, dan kontak pendidik')

@section('page_actions')
  <a href="{{ route('gtk.index') }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
    <x-heroicon-o-arrow-left class="heroicon-sm" /> Kembali
  </a>
@endsection

@section('content')
<form method="POST" action="{{ route('gtk.update', $gtk->id) }}" enctype="multipart/form-data">
  @csrf
  @method('PUT')
  <div class="card">
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label small fw-semibold">Nama Lengkap & Gelar <span class="text-danger">*</span></label>
          <input type="text" name="nama" class="form-control" value="{{ old('nama', $gtk->nama) }}" required>
        </div>
        <div class="col-md-3">
          <label class="form-label small fw-semibold">NIP</label>
          <input type="text" name="nip" class="form-control" value="{{ old('nip', $gtk->nip) }}">
        </div>
        <div class="col-md-3">
          <label class="form-label small fw-semibold">NUPTK</label>
          <input type="text" name="nuptk" class="form-control" value="{{ old('nuptk', $gtk->nuptk) }}">
        </div>
        <div class="col-md-4">
          <label class="form-label small fw-semibold">Jenis Kelamin</label>
          <select name="jk" class="form-select">
            <option value="L" {{ old('jk', $gtk->jk) == 'L' ? 'selected' : '' }}>Laki-laki</option>
            <option value="P" {{ old('jk', $gtk->jk) == 'P' ? 'selected' : '' }}>Perempuan</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label small fw-semibold">Jenis PTK</label>
          <input type="text" name="jenis_ptk" class="form-control" value="{{ old('jenis_ptk', $gtk->jenis_ptk) }}">
        </div>
        <div class="col-md-4">
          <label class="form-label small fw-semibold">Status Kepegawaian</label>
          <input type="text" name="status_kepegawaian" class="form-control" value="{{ old('status_kepegawaian', $gtk->status_kepegawaian) }}">
        </div>
        <div class="col-md-6">
          <label class="form-label small fw-semibold">No HP / WhatsApp</label>
          <input type="text" name="hp" class="form-control" value="{{ old('hp', $gtk->hp) }}">
        </div>
        <div class="col-md-6">
          <label class="form-label small fw-semibold">Email</label>
          <input type="email" name="email" class="form-control" value="{{ old('email', $gtk->email) }}">
        </div>
        <div class="col-md-12">
          <label class="form-label small fw-semibold">Foto Profil GTK</label>
          <input type="file" name="foto" class="form-control" accept="image/*">
          @if($gtk->foto)
            <div class="mt-1 small text-success">Foto tersimpan: {{ $gtk->foto }}</div>
          @endif
        </div>
      </div>
    </div>
    <div class="card-footer bg-light d-flex justify-content-between">
      <a href="{{ route('gtk.index') }}" class="btn btn-outline-secondary">Batal</a>
      <button type="submit" class="btn btn-primary px-4 d-inline-flex align-items-center gap-1">
        <x-heroicon-o-check class="heroicon-sm" /> Perbarui Data GTK
      </button>
    </div>
  </div>
</form>
@endsection