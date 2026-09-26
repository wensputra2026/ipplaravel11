@extends('layouts.guest')

@section('title', 'Masuk ke Sistem')

@section('content')
@php
  $appName = \App\Models\AppSetting::get('app_name', 'E-IPP');
  $schoolName = \App\Models\AppSetting::get('school_name', 'SMAN Benlutu');
  $schoolLogo = \App\Models\AppSetting::get('school_logo', 'logo_1767853884.png');
@endphp

<div class="p-4 p-sm-5">
  <div class="text-center mb-4">
    <div class="d-inline-flex p-2 bg-light rounded-4 border shadow-xs mb-3">
      <img src="{{ asset('assets/dist/img/' . $schoolLogo) }}" alt="Logo" class="img-fluid" style="height: 52px; width: 52px; object-fit: contain;">
    </div>
    <h3 class="fw-bold text-dark mb-1" style="font-size: 1.45rem; letter-spacing: -0.02em;">{{ $appName }}</h3>
    <p class="text-secondary small mb-2 fw-medium">{{ $schoolName }}</p>
    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1 rounded-pill d-inline-flex align-items-center gap-1" style="font-size: 0.72rem;">
      <x-heroicon-o-lock-closed class="heroicon-sm" style="width: 13px !important; height: 13px !important;" /> Portal Login
    </span>
  </div>

  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show small d-flex align-items-center mb-3" role="alert">
      <x-heroicon-o-exclamation-triangle class="heroicon me-2 flex-shrink-0" />
      <div>{{ session('error') }}</div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show small d-flex align-items-center mb-3" role="alert">
      <x-heroicon-o-check-circle class="heroicon me-2 flex-shrink-0" />
      <div>{{ session('success') }}</div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <form method="POST" action="{{ route('login.post') }}">
    @csrf
    <div class="mb-3">
      <label class="form-label small fw-semibold text-dark mb-1">Username atau Email</label>
      <div class="input-group">
        <span class="input-group-text">
          <x-heroicon-o-user class="heroicon-sm" />
        </span>
        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" placeholder="Masukkan username atau email" required autofocus style="border-radius: 0 10px 10px 0;">
      </div>
      @error('username')
        <div class="invalid-feedback d-block">{{ $message }}</div>
      @enderror
    </div>

    <div class="mb-3" x-data="{ showPw: false }">
      <label class="form-label small fw-semibold text-dark mb-1">Kata Sandi</label>
      <div class="input-group">
        <span class="input-group-text">
          <x-heroicon-o-lock-closed class="heroicon-sm" />
        </span>
        <input :type="showPw ? 'text' : 'password'" id="passwordInput" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••" required style="border-radius: 0;">
        <button class="btn btn-outline-secondary bg-light border d-flex align-items-center justify-content-center px-3" type="button" id="togglePasswordBtn" style="border-radius: 0 10px 10px 0; border-color: #cbd5e1 !important;" title="Lihat/Sembunyikan Kata Sandi" @click="showPw = !showPw">
          <span x-show="!showPw" class="d-flex align-items-center">
            <x-heroicon-o-eye class="heroicon-sm text-secondary" />
          </span>
          <span x-show="showPw" x-cloak class="d-flex align-items-center">
            <x-heroicon-o-eye-slash class="heroicon-sm text-primary" />
          </span>
        </button>
      </div>
      @error('password')
        <div class="invalid-feedback d-block">{{ $message }}</div>
      @enderror
    </div>

    <div class="d-flex align-items-center justify-content-between mb-4">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
        <label class="form-check-label small text-secondary" for="rememberMe">
          Ingat Saya
        </label>
      </div>
    </div>

    <button type="submit" class="btn btn-login">
      <x-heroicon-o-arrow-right-on-rectangle class="heroicon" /> Masuk Sekarang
    </button>
  </form>

  <div class="text-center mt-4 pt-3 border-top">
    <a href="{{ route('welcome') }}" class="text-decoration-none small text-secondary d-inline-flex align-items-center gap-1 hover-primary">
      <x-heroicon-o-arrow-left class="heroicon-sm" /> Kembali ke Halaman Utama
    </a>
  </div>
</div>


@endsection