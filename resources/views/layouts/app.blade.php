<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  @php
    $appName = \App\Models\AppSetting::get('app_name', 'E-IPP');
    $schoolName = \App\Models\AppSetting::get('school_name', 'SMAN Benlutu');
    $schoolLogo = \App\Models\AppSetting::get('school_logo', 'logo_1767853884.png');
    $activeTa = \App\Models\AppSetting::get('active_tahun_ajaran', '2025/2026');
    $activeSem = \App\Models\AppSetting::get('active_semester', 'Genap');
    $user = Auth::user();
  @endphp

  <title>@yield('title', 'Dashboard') - {{ $appName }} {{ $schoolName }}</title>
  <link rel="icon" href="{{ asset('assets/dist/img/' . $schoolLogo) }}">

  <!-- Fonts & Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
  <!-- Bootstrap 5.3.3 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <!-- Tom Select CSS -->
  <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">

  <style>
    :root {
      --sidebar-width: 280px;
      --sidebar-bg: #1e293b;
      --sidebar-hover: #334155;
      --sidebar-active: #2563eb;
      --sidebar-text: #94a3b8;
      --sidebar-text-active: #ffffff;
      --topbar-height: 64px;
      --body-bg: #f8fafc;
    }

    body {
      font-family: 'Roboto', -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      background-color: var(--body-bg);
      color: #1e293b;
      min-height: 100vh;
      overflow-x: hidden;
    }

    /* Heroicons Base Styling */
    .heroicon,
    svg.heroicon {
      display: inline-block;
      vertical-align: middle;
      width: 20px !important;
      height: 20px !important;
      min-width: 20px !important;
      min-height: 20px !important;
      max-width: 20px !important;
      max-height: 20px !important;
      stroke-width: 1.8;
      flex-shrink: 0 !important;
    }

    .heroicon-sm,
    svg.heroicon-sm {
      display: inline-block;
      vertical-align: middle;
      width: 16px !important;
      height: 16px !important;
      min-width: 16px !important;
      min-height: 16px !important;
      max-width: 16px !important;
      max-height: 16px !important;
      stroke-width: 1.8;
      flex-shrink: 0 !important;
    }

    .heroicon-lg,
    svg.heroicon-lg {
      display: inline-block;
      vertical-align: middle;
      width: 24px !important;
      height: 24px !important;
      min-width: 24px !important;
      min-height: 24px !important;
      max-width: 24px !important;
      max-height: 24px !important;
      stroke-width: 1.8;
      flex-shrink: 0 !important;
    }

    .dropdown-arrow,
    svg.dropdown-arrow,
    .sidebar-nav-item .dropdown-arrow,
    .sidebar-nav-item svg.dropdown-arrow,
    .sidebar-nav-item.has-dropdown .dropdown-arrow,
    .sidebar-nav-item.has-dropdown svg.dropdown-arrow {
      width: 14px !important;
      height: 14px !important;
      min-width: 14px !important;
      min-height: 14px !important;
      max-width: 14px !important;
      max-height: 14px !important;
      stroke-width: 2.2 !important;
      margin-left: auto;
      flex-shrink: 0 !important;
      transition: transform 0.25s ease;
    }

    /* Tom Select Custom Styling for Bootstrap 5 */
    .ts-wrapper.form-select,
    .ts-wrapper.single .ts-control,
    .ts-wrapper.multi .ts-control {
      border-radius: 6px !important;
      border: 1px solid #dee2e6 !important;
      padding: 0.375rem 0.75rem !important;
      font-size: 0.875rem !important;
      min-height: 38px !important;
      background-color: #ffffff !important;
      box-shadow: none !important;
      display: flex !important;
      align-items: center !important;
      transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out !important;
    }
    .ts-wrapper.focus .ts-control {
      border-color: #86b7fe !important;
      box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
      outline: 0 !important;
    }
    .ts-dropdown {
      border-radius: 8px !important;
      border: 1px solid #cbd5e1 !important;
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05) !important;
      font-size: 0.875rem !important;
      z-index: 1060 !important;
    }
    .ts-dropdown .option {
      padding: 0.5rem 0.75rem !important;
    }
    .ts-dropdown .option.active,
    .ts-dropdown .option:hover {
      background-color: #eff6ff !important;
      color: #1d4ed8 !important;
    }
    .ts-wrapper .clear-button {
      color: #94a3b8 !important;
      margin-right: 0.5rem !important;
    }
    .ts-wrapper.plugin-remove_button .item .remove {
      border-left: 1px solid #cbd5e1 !important;
      margin-left: 6px !important;
      padding-left: 6px !important;
    }

    /* Sidebar Layout */
    .app-sidebar {
      position: fixed;
      top: 0;
      left: 0;
      bottom: 0;
      width: var(--sidebar-width);
      min-width: var(--sidebar-width);
      max-width: var(--sidebar-width);
      background-color: var(--sidebar-bg);
      color: var(--sidebar-text);
      z-index: 1040;
      display: flex;
      flex-direction: column;
      transition: transform 0.3s ease-in-out;
      overflow-y: auto;
      overflow-x: hidden !important;
      scrollbar-width: thin;
      scrollbar-color: rgba(255, 255, 255, 0.15) transparent;
      box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
    }

    .app-sidebar::-webkit-scrollbar {
      width: 4px;
    }
    .app-sidebar::-webkit-scrollbar-track {
      background: transparent;
    }
    .app-sidebar::-webkit-scrollbar-thumb {
      background: rgba(255, 255, 255, 0.15);
      border-radius: 4px;
    }
    .app-sidebar::-webkit-scrollbar-thumb:hover {
      background: rgba(255, 255, 255, 0.3);
    }

    .sidebar-brand {
      height: var(--topbar-height);
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 0 16px;
      color: #ffffff;
      text-decoration: none;
      border-bottom: 1px solid rgba(255, 255, 255, 0.08);
      flex-shrink: 0;
    }

    .sidebar-brand img {
      width: 38px;
      height: 38px;
      object-fit: contain;
      border-radius: 8px;
      background: rgba(255, 255, 255, 0.1);
      padding: 3px;
    }

    .sidebar-brand .brand-title {
      font-size: 1.15rem;
      font-weight: 700;
      line-height: 1.2;
      color: #ffffff;
      letter-spacing: -0.01em;
    }

    .sidebar-brand .brand-subtitle {
      font-size: 0.85rem;
      color: #94a3b8;
      font-weight: 400;
    }

    .sidebar-nav {
      padding: 14px 10px;
      flex-grow: 1;
    }

    .sidebar-nav-title {
      font-size: 0.8rem;
      text-transform: uppercase;
      font-weight: 700;
      color: #94a3b8;
      padding: 14px 12px 6px;
      letter-spacing: 0.05em;
    }

    .sidebar-nav-item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 9px 12px;
      color: var(--sidebar-text);
      text-decoration: none;
      border-radius: 8px;
      font-size: 0.95rem;
      font-weight: 500;
      margin-bottom: 3px;
      transition: background-color 0.15s ease, color 0.15s ease;
      line-height: 1.4;
    }

    .sidebar-nav-item > svg:first-child,
    .sidebar-nav-item > i:first-child {
      font-size: 1.2rem;
      width: 20px !important;
      height: 20px !important;
      min-width: 20px !important;
      min-height: 20px !important;
      max-width: 20px !important;
      max-height: 20px !important;
      text-align: center;
      flex-shrink: 0 !important;
      color: #94a3b8;
      transition: color 0.2s ease;
    }

    .sidebar-nav-item .nav-text {
      flex: 1 1 auto;
      line-height: 1.4;
      white-space: normal;
      word-break: normal;
      overflow: visible;
      text-overflow: clip;
    }

    .sidebar-nav-item:hover {
      background-color: var(--sidebar-hover);
      color: #f1f5f9;
    }

    .sidebar-nav-item:hover svg,
    .sidebar-nav-item:hover i:first-child {
      color: #f1f5f9;
    }

    .sidebar-nav-item.active {
      background-color: var(--sidebar-active);
      color: var(--sidebar-text-active);
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    }

    .sidebar-nav-item.active svg,
    .sidebar-nav-item.active i:first-child {
      color: #ffffff;
    }

    .sidebar-nav-item .badge {
      margin-left: auto;
      font-size: 0.75rem;
      padding: 3px 8px;
      flex-shrink: 0;
    }

    /* Sidebar Dropdown Submenus */
    .sidebar-nav-item.has-dropdown {
      cursor: pointer;
    }

    .sidebar-nav-item.has-dropdown .dropdown-arrow,
    .sidebar-nav-item.has-dropdown svg.dropdown-arrow {
      margin-left: auto;
      font-size: 0.75rem;
      width: 14px;
      height: 14px;
      flex-shrink: 0;
      transition: transform 0.25s ease;
    }

    .sidebar-nav-item.has-dropdown .badge + .dropdown-arrow,
    .sidebar-nav-item.has-dropdown .badge + svg.dropdown-arrow {
      margin-left: 6px;
    }

    .sidebar-nav-item.has-dropdown[aria-expanded="true"] .dropdown-arrow,
    .sidebar-nav-item.has-dropdown[aria-expanded="true"] svg.dropdown-arrow {
      transform: rotate(90deg);
    }

    .sidebar-nav-item.has-dropdown.parent-active {
      color: #f8fafc;
      background-color: rgba(255, 255, 255, 0.06);
    }

    .sidebar-nav-item.has-dropdown.parent-active svg:first-child,
    .sidebar-nav-item.has-dropdown.parent-active i:first-child {
      color: #38bdf8;
    }

    .sidebar-submenu {
      padding: 2px 0 4px 8px;
      margin-left: 12px;
      border-left: 2px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar-sub-item {
      display: flex;
      align-items: center;
      gap: 9px;
      padding: 8px 12px;
      color: #94a3b8;
      text-decoration: none;
      border-radius: 6px;
      font-size: 0.92rem;
      font-weight: 500;
      margin-bottom: 2px;
      transition: background-color 0.15s ease, color 0.15s ease;
      line-height: 1.4;
    }

    .sidebar-sub-item > svg:first-child,
    .sidebar-sub-item > i:first-child {
      font-size: 1rem;
      width: 17px !important;
      height: 17px !important;
      min-width: 17px !important;
      min-height: 17px !important;
      max-width: 17px !important;
      max-height: 17px !important;
      text-align: center;
      flex-shrink: 0 !important;
      color: #64748b;
      transition: color 0.15s ease;
    }

    .sidebar-sub-item .sub-text {
      flex: 1 1 auto;
      line-height: 1.4;
      white-space: normal;
      word-break: normal;
      overflow: visible;
      text-overflow: clip;
    }

    .sidebar-sub-item:hover {
      background-color: var(--sidebar-hover);
      color: #f8fafc;
    }

    .sidebar-sub-item:hover svg,
    .sidebar-sub-item:hover i {
      color: #38bdf8;
    }

    .sidebar-sub-item.active {
      color: #ffffff;
      background-color: rgba(37, 99, 235, 0.25);
      font-weight: 600;
    }

    .sidebar-sub-item.active svg,
    .sidebar-sub-item.active i {
      color: #38bdf8;
    }

    .sidebar-sub-item .badge {
      margin-left: auto;
      font-size: 0.72rem;
      padding: 2px 7px;
      flex-shrink: 0;
    }

    .sidebar-footer {
      padding: 14px 16px;
      border-top: 1px solid rgba(255, 255, 255, 0.08);
      font-size: 0.88rem;
      background: rgba(0, 0, 0, 0.15);
      flex-shrink: 0;
    }

    /* Main Area Layout */
    .app-main {
      margin-left: var(--sidebar-width);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      transition: margin-left 0.3s ease-in-out;
    }

    /* Topbar */
    .app-topbar {
      height: var(--topbar-height);
      background-color: #ffffff;
      border-bottom: 1px solid #e2e8f0;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 20px;
      position: sticky;
      top: 0;
      z-index: 1020;
    }

    .topbar-toggle {
      background: none;
      border: none;
      color: #475569;
      font-size: 1.4rem;
      cursor: pointer;
      display: none;
      padding: 4px;
      border-radius: 6px;
    }

    .topbar-toggle:hover {
      background-color: #f1f5f9;
    }

    /* Content Area */
    .app-content {
      padding: 14px 18px;
      flex-grow: 1;
    }

    /* App Footer */
    .app-footer {
      background-color: #ffffff;
      border-top: 1px solid #e2e8f0;
      padding: 12px 18px;
      font-size: 0.8125rem;
      color: #64748b;
      margin-top: auto;
    }

    .page-header {
      margin-bottom: 12px;
    }

    .page-title {
      font-size: 1.2rem;
      font-weight: 700;
      color: #0f172a;
      letter-spacing: -0.01em;
      margin-bottom: 2px;
      line-height: 1.2;
    }

    .page-subtitle {
      font-size: 0.78rem;
      color: #64748b;
      line-height: 1.2;
    }

    /* Card styling */
    .card {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 10px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .card-header {
      padding: 10px 14px;
    }

    .card-body {
      padding: 14px;
    }

    .card-footer {
      padding: 8px 14px;
    }

    /* Table Compact Styling */
    .table {
      font-size: 0.8125rem;
      margin-bottom: 0;
    }

    .table > :not(caption) > * > * {
      padding: 0.45rem 0.55rem;
    }

    .table th {
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 0.03em;
      color: #64748b;
      font-weight: 600;
      background-color: #f8fafc;
      white-space: nowrap;
    }

    /* Mobile Responsive */
    @media (max-width: 991.98px) {
      .app-sidebar {
        transform: translateX(-100%);
      }

      .app-sidebar.show {
        transform: translateX(0);
      }

      .app-main {
        margin-left: 0;
      }

      .topbar-toggle {
        display: block;
      }

      .sidebar-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(15, 23, 42, 0.5);
        z-index: 1030;
        display: none;
      }

      .sidebar-backdrop.show {
        display: block;
      }
    }

    @media (max-width: 767.98px) {
      .app-content {
        padding: 16px 12px;
      }
      .app-topbar {
        padding: 0 14px;
        height: 56px;
      }
      .page-header {
        margin-bottom: 16px;
      }
      .page-title {
        font-size: 1.25rem;
      }
      .card-body {
        padding: 16px 14px;
      }
      .card-header, .card-footer {
        padding: 12px 14px;
      }
      .table-responsive {
        border-radius: 8px;
        -webkit-overflow-scrolling: touch;
      }
      .table-responsive table {
        min-width: 680px;
      }
      .card-footer.d-flex {
        flex-direction: column !important;
        align-items: center !important;
        gap: 12px !important;
        text-align: center !important;
      }
      .stat-widget {
        padding: 14px;
      }
      .stat-widget .stat-icon {
        width: 44px;
        height: 44px;
        font-size: 1.25rem;
      }
      .stat-widget .stat-value {
        font-size: 1.35rem;
      }
      .app-footer {
        padding: 12px 14px;
        text-align: center;
      }
      .app-footer .footer-content {
        flex-direction: column !important;
        gap: 6px !important;
      }
    }
  </style>

  @stack('styles')
</head>
<body>

<!-- Mobile Sidebar Backdrop -->
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<!-- Sidebar -->
@include('layouts.partials.sidebar')

<!-- Main Content Area -->
<div class="app-main">
  <!-- Topbar -->
  <header class="app-topbar">
    <div class="d-flex align-items-center gap-3">
      <button class="topbar-toggle" id="sidebarToggle" type="button" aria-label="Toggle Navigation">
        <x-heroicon-o-bars-3 class="heroicon" style="width: 22px; height: 22px;" />
      </button>

      <div class="d-flex align-items-center gap-2">
        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1.5 rounded-pill small fw-semibold" style="font-size: 0.76rem;">
          <x-heroicon-o-calendar-days class="heroicon-sm me-1" style="vertical-align: -2px;" /> <span class="d-none d-sm-inline">Periode: </span>{{ $activeTa }} ({{ $activeSem }})
        </span>
      </div>
    </div>

    <!-- Right User Menu -->
    <div class="dropdown">
      <button class="btn btn-link text-decoration-none dropdown-toggle d-flex align-items-center gap-2 text-dark p-1" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        @if($user->photo_url)
          <img src="{{ $user->photo_url }}" alt="{{ $user->nama }}" class="rounded-circle object-fit-cover shadow-xs border border-1 border-secondary-subtle" style="width: 36px; height: 36px;">
        @else
          <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold shadow-xs" style="width: 36px; height: 36px; font-size: 0.95rem;">
            {{ strtoupper(substr($user->nama ?? $user->username ?? 'U', 0, 1)) }}
          </div>
        @endif
        <div class="d-none d-sm-block text-start lh-sm">
          <div class="fw-semibold text-dark" style="font-size: 0.88rem;">{{ $user->nama ?? $user->username }}</div>
          <div class="text-secondary small" style="font-size: 0.74rem;">Role: {{ $user->role_name }}</div>
        </div>
      </button>
      <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 mt-2" style="min-width: 200px;">
        <li class="px-3 py-2 border-bottom">
          <div class="fw-bold text-dark">{{ $user->nama ?? $user->username }}</div>
          <div class="text-muted small">{{ $user->email ?? $user->username }}</div>
        </li>
        <li>
          <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="{{ route('profile.index') }}">
            <x-heroicon-o-user class="heroicon-sm text-secondary" /> Profil Saya
          </a>
        </li>
        @if(Auth::user()->isAdmin())
          <li>
            <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="{{ route('settings.index') }}">
              <x-heroicon-o-adjustments-horizontal class="heroicon-sm text-secondary" /> Pengaturan
            </a>
          </li>
        @endif
        <li><hr class="dropdown-divider my-1"></li>
        <li>
          <form method="POST" action="{{ route('logout') }}" id="topbarLogoutForm">
            @csrf
            <a class="dropdown-item py-2 d-flex align-items-center gap-2 text-danger btn-logout-confirm" data-form="topbarLogoutForm" href="javascript:void(0)">
              <x-heroicon-o-arrow-left-on-rectangle class="heroicon-sm text-danger" /> Keluar
            </a>
          </form>
        </li>
      </ul>
    </div>
  </header>

  <!-- Content Body -->
  <main class="app-content">
    <!-- Page Header -->
    <div class="page-header d-flex flex-wrap align-items-center justify-content-between gap-3">
      <div>
        <h1 class="page-title">@yield('page_title', 'Dashboard')</h1>
        @hasSection('page_subtitle')
          <div class="page-subtitle">@yield('page_subtitle')</div>
        @endif
      </div>
      <div class="d-flex align-items-center gap-2">
        @yield('page_actions')
      </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4 rounded-3 border-0 shadow-sm" role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="me-2 text-success" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div>{!! session('success') !!}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @if(session('warning'))
      <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center mb-4 rounded-3 border-0 shadow-sm" role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="me-2 text-warning" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        <div>{!! session('warning') !!}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4 rounded-3 border-0 shadow-sm" role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="me-2 text-danger" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        <div>{!! session('error') !!}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @if ($errors->any())
      <div class="alert alert-danger alert-dismissible fade show mb-4 rounded-3 border-0 shadow-sm" role="alert">
        <div class="fw-bold mb-1 d-flex align-items-center gap-1.5"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="text-danger" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Terjadi kesalahan input:</div>
        <ul class="mb-0 ps-3">
          @foreach ($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <!-- Content Slot -->
    @yield('content')
  </main>

  <!-- Footer -->
  @include('layouts.partials.footer')
</div>

<!-- Core JS -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  $(function () {
    const $sidebar = $('.app-sidebar');
    const $backdrop = $('#sidebarBackdrop');

    $('#sidebarToggle').on('click', function () {
      $sidebar.toggleClass('show');
      $backdrop.toggleClass('show');
    });

    $backdrop.on('click', function () {
      $sidebar.removeClass('show');
      $backdrop.removeClass('show');
    });

    // SweetAlert2 Toast configuration
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true
    });

    // 1. Confirm Delete Helper
    $(document).on('click', '.btn-delete-confirm', function(e) {
      e.preventDefault();
      const form = $(this).closest('form');
      const itemTitle = $(this).data('title') || 'Konfirmasi Hapus Data';
      const itemText = $(this).data('text') || 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini bersifat permanen dan tidak dapat dibatalkan.';

      Swal.fire({
        title: itemTitle,
        text: itemText,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Hapus Data',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        focusCancel: true
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    });

    // 2. Generic Action Confirmation [data-confirm]
    $(document).on('click', '[data-confirm]', function(e) {
      e.preventDefault();
      const $el = $(this);
      const message = $el.data('confirm') || 'Apakah Anda yakin ingin melanjutkan tindakan ini?';
      const title = $el.data('confirm-title') || 'Konfirmasi Tindakan';
      const icon = $el.data('confirm-icon') || 'question';
      const confirmBtnText = $el.data('confirm-btn') || 'Ya, Lanjutkan';
      const confirmBtnColor = $el.data('confirm-btn-color') || '#2563eb';
      const formId = $el.data('form');
      const form = formId ? $('#' + formId) : $el.closest('form');
      const href = $el.attr('href');

      Swal.fire({
        title: title,
        text: message,
        icon: icon,
        showCancelButton: true,
        confirmButtonColor: confirmBtnColor,
        cancelButtonColor: '#64748b',
        confirmButtonText: confirmBtnText,
        cancelButtonText: 'Batal',
        reverseButtons: true,
        focusCancel: true
      }).then((result) => {
        if (result.isConfirmed) {
          if (form.length && ($el.is('button') || $el.is('input[type="submit"]') || formId)) {
            form.submit();
          } else if (href && href !== '#' && href !== 'javascript:void(0)') {
            window.location.href = href;
          } else if (form.length) {
            form.submit();
          }
        }
      });
    });

    // 3. Confirm Logout Helper (.btn-logout-confirm)
    $(document).on('click', '.btn-logout-confirm', function(e) {
      e.preventDefault();
      const formId = $(this).data('form');
      const form = formId ? $('#' + formId) : $(this).closest('form');

      Swal.fire({
        title: 'Konfirmasi Keluar',
        text: 'Apakah Anda yakin ingin mengakhiri sesi dan keluar dari sistem aplikasi?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Keluar',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        focusCancel: true
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    });
  });
</script>

<!-- Tom Select JS (must load before Alpine.js) -->
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

<script>
  // Auto-init Tom Select untuk semua elemen dengan class .tom-select, .select2, atau select[data-searchable]
  document.addEventListener('DOMContentLoaded', function () {
    window.initTomSelect = function (container = document) {
      if (typeof TomSelect === 'undefined') return;

      const selects = container.querySelectorAll('select.tom-select, select.select2, select[data-searchable]');
      selects.forEach(function (selectEl) {
        if (selectEl.tomselect) return; // hindari duplicate init

        const isMultiple = selectEl.hasAttribute('multiple');
        const plugins = {
          clear_button: { title: 'Hapus pilihan' }
        };
        if (isMultiple) {
          plugins.remove_button = { title: 'Hapus item' };
        }

        const placeholder = selectEl.getAttribute('placeholder') || 
                            selectEl.querySelector('option[value=""]')?.textContent.trim() || 
                            '-- Pilih opsi --';

        new TomSelect(selectEl, {
          create: false,
          plugins: plugins,
          maxItems: isMultiple ? null : 1,
          placeholder: placeholder,
          allowEmptyOption: true,
          closeAfterSelect: !isMultiple,
          searchField: ['text', 'value'],
          render: {
            no_results: function(data, escape) {
              return '<div class="no-results p-2 text-muted small">Tidak ada data ditemukan</div>';
            }
          }
        });
      });
    };

    window.initTomSelect();

    // Re-inisialisasi / sinkronisasi saat tab Bootstrap diubah
    const tabTriggers = document.querySelectorAll('button[data-bs-toggle="tab"], a[data-bs-toggle="tab"]');
    tabTriggers.forEach(function (tab) {
      tab.addEventListener('shown.bs.tab', function (e) {
        const targetSelector = e.target.getAttribute('data-bs-target') || e.target.getAttribute('href');
        if (targetSelector) {
          const targetPane = document.querySelector(targetSelector);
          if (targetPane) {
            window.initTomSelect(targetPane);
          }
        }
      });
    });
  });
</script>

<!-- Alpine.js -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
@stack('scripts')
</body>
</html>