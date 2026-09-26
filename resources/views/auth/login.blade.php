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

    <div class="mb-3">
      <label class="form-label small fw-semibold text-dark mb-1">Kata Sandi</label>
      <div class="input-group">
        <span class="input-group-text">
          <x-heroicon-o-lock-closed class="heroicon-sm" />
        </span>
        <input type="password" id="passwordInput" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••" required style="border-radius: 0;">
        <button class="btn btn-outline-secondary bg-light border d-flex align-items-center justify-content-center" type="button" id="togglePasswordBtn" onclick="togglePassword()" style="border-radius: 0 10px 10px 0; border-color: #cbd5e1 !important; width: 42px; min-width: 42px;" title="Lihat/Sembunyikan Kata Sandi">
          <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16" class="text-secondary">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
          </svg>
          <svg id="eyeSlash" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16" class="text-primary" style="display:none">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
          </svg>
        </button>
      </div>
      @error('password')
        <div class="invalid-feedback d-block">{{ $message }}</div>
      @enderror
    </div>

    <script>
      function togglePassword() {
        var input = document.getElementById('passwordInput');
        var eyeOpen = document.getElementById('eyeOpen');
        var eyeSlash = document.getElementById('eyeSlash');
        if (input.type === 'password') {
          input.type = 'text';
          eyeOpen.style.display = 'none';
          eyeSlash.style.display = 'flex';
        } else {
          input.type = 'password';
          eyeOpen.style.display = 'flex';
          eyeSlash.style.display = 'none';
        }
      }
    </script>

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