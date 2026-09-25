@php
  $appName = \App\Models\AppSetting::get('app_name', 'E-IPP');
  $schoolName = \App\Models\AppSetting::get('school_name', 'SMAN Benlutu');
  $schoolLogo = \App\Models\AppSetting::get('school_logo', 'logo_1767853884.png');
  $user = Auth::user();
  $lvl = strtolower(trim($user->level ?? ''));

  // Count incomplete profile notifications
  $notifQuery = \App\Models\Siswa::aktif();
  if ($lvl === 'wali' && $user->kelas_id) {
    $notifQuery->where('kelas_id', $user->kelas_id);
  }
  $notifCount = (clone $notifQuery)->where(function($q) {
    $q->whereNull('nisn')->orWhere('nisn', '')
      ->orWhereNull('no_kk')->orWhere('no_kk', '')
      ->orWhereNull('foto')->orWhere('foto', '')
      ->orWhereNull('nama_ayah')->orWhere('nama_ayah', '')
      ->orWhereNull('nama_ibu')->orWhere('nama_ibu', '');
  })->count();

  // Active state trackers for dropdown menus
  $isAkademikActive = (request()->routeIs('siswa.*') && !request()->routeIs('siswa.konversi'))
                      || request()->routeIs('notifications.*')
                      || request()->routeIs('siswapindah.*')
                      || request()->routeIs('kenaikankelas.*')
                      || request()->routeIs('kelulusan.*');

  $isMasterActive = request()->routeIs('kelas.*')
                    || request()->routeIs('walikelas.*')
                    || request()->routeIs('gtk.*')
                    || request()->routeIs('master.*');

  $isIppActive = request()->routeIs('siswa.konversi');

  $isSystemActive = request()->routeIs('account.*')
                    || request()->routeIs('activity.*')
                    || request()->routeIs('settings.*');

  $isWaliClassActive = (request()->routeIs('siswa.*') && !request()->routeIs('siswa.konversi')) || request()->routeIs('notifications.*');
@endphp

<aside class="app-sidebar">
  <!-- Brand Header -->
  <a href="{{ route('dashboard') }}" class="sidebar-brand">
    <img src="{{ asset('assets/dist/img/' . $schoolLogo) }}" alt="Logo">
    <div>
      <div class="brand-title">{{ $appName }}</div>
      <div class="brand-subtitle">{{ $schoolName }}</div>
    </div>
  </a>

  <!-- Navigation Items -->
  <nav class="sidebar-nav">
    <div class="sidebar-nav-title">Menu Utama</div>
    <a href="{{ route('dashboard') }}" class="sidebar-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
      <x-heroicon-o-squares-2x2 class="heroicon" />
      <span class="nav-text">Dashboard</span>
    </a>

    @if($lvl === 'admin')
      <div class="sidebar-nav-title">Menu Manajemen</div>

      <!-- 1. KESISWAAN & AKADEMIK (DROPDOWN) -->
      <a class="sidebar-nav-item has-dropdown {{ $isAkademikActive ? 'parent-active' : '' }}" 
         data-bs-toggle="collapse" 
         href="#menuAkademik" 
         role="button" 
         aria-expanded="{{ $isAkademikActive ? 'true' : 'false' }}" 
         aria-controls="menuAkademik">
        <x-heroicon-o-academic-cap class="heroicon" />
        <span class="nav-text">Kesiswaan & Akademik</span>
        @if($notifCount > 0)
          <span class="badge bg-danger rounded-pill">{{ $notifCount }}</span>
        @endif
        <x-heroicon-o-chevron-right class="dropdown-arrow" style="width: 14px; height: 14px; min-width: 14px; min-height: 14px;" />
      </a>
      <div class="collapse {{ $isAkademikActive ? 'show' : '' }}" id="menuAkademik">
        <div class="sidebar-submenu">
          <a href="{{ route('siswa.index') }}" class="sidebar-sub-item {{ (request()->routeIs('siswa.index') || request()->routeIs('siswa.show') || request()->routeIs('siswa.edit')) ? 'active' : '' }}">
            <x-heroicon-o-users class="heroicon-sm" />
            <span class="sub-text">Data Siswa</span>
          </a>
          <a href="{{ route('siswa.create') }}" class="sidebar-sub-item {{ request()->routeIs('siswa.create') ? 'active' : '' }}">
            <x-heroicon-o-user-plus class="heroicon-sm" />
            <span class="sub-text">Tambah Siswa</span>
          </a>
          <a href="{{ route('notifications.index') }}" class="sidebar-sub-item {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
            <x-heroicon-o-clipboard-document-check class="heroicon-sm" />
            <span class="sub-text">Validasi & Progres</span>
            @if($notifCount > 0)
              <span class="badge bg-danger rounded-pill">{{ $notifCount }}</span>
            @endif
          </a>
          <a href="{{ route('siswapindah.index') }}" class="sidebar-sub-item {{ request()->routeIs('siswapindah.*') ? 'active' : '' }}">
            <x-heroicon-o-arrows-right-left class="heroicon-sm" />
            <span class="sub-text">Pindah Kelas</span>
          </a>
          <a href="{{ route('kenaikankelas.index') }}" class="sidebar-sub-item {{ request()->routeIs('kenaikankelas.*') ? 'active' : '' }}">
            <x-heroicon-o-arrow-trending-up class="heroicon-sm" />
            <span class="sub-text">Kenaikan Kelas</span>
          </a>
          <a href="{{ route('kelulusan.index') }}" class="sidebar-sub-item {{ request()->routeIs('kelulusan.*') ? 'active' : '' }}">
            <x-heroicon-o-check-badge class="heroicon-sm" />
            <span class="sub-text">Kelulusan & Alumni</span>
          </a>
        </div>
      </div>

      <!-- 2. DATA MASTER (DROPDOWN) -->
      <a class="sidebar-nav-item has-dropdown {{ $isMasterActive ? 'parent-active' : '' }}" 
         data-bs-toggle="collapse" 
         href="#menuMaster" 
         role="button" 
         aria-expanded="{{ $isMasterActive ? 'true' : 'false' }}" 
         aria-controls="menuMaster">
        <x-heroicon-o-circle-stack class="heroicon" />
        <span class="nav-text">Data Master</span>
        <x-heroicon-o-chevron-right class="dropdown-arrow" style="width: 14px; height: 14px; min-width: 14px; min-height: 14px;" />
      </a>
      <div class="collapse {{ $isMasterActive ? 'show' : '' }}" id="menuMaster">
        <div class="sidebar-submenu">
          <a href="{{ route('kelas.index') }}" class="sidebar-sub-item {{ request()->routeIs('kelas.*') ? 'active' : '' }}">
            <x-heroicon-o-building-office class="heroicon-sm" />
            <span class="sub-text">Data Kelas</span>
          </a>
          <a href="{{ route('walikelas.index') }}" class="sidebar-sub-item {{ request()->routeIs('walikelas.*') ? 'active' : '' }}">
            <x-heroicon-o-user-group class="heroicon-sm" />
            <span class="sub-text">Data Wali Kelas</span>
          </a>
          <a href="{{ route('gtk.index') }}" class="sidebar-sub-item {{ request()->routeIs('gtk.*') ? 'active' : '' }}">
            <x-heroicon-o-identification class="heroicon-sm" />
            <span class="sub-text">Data Guru & GTK</span>
          </a>
          <a href="{{ route('master.index') }}" class="sidebar-sub-item {{ request()->routeIs('master.*') ? 'active' : '' }}">
            <x-heroicon-o-bookmark-square class="heroicon-sm" />
            <span class="sub-text">Data Referensi</span>
          </a>
        </div>
      </div>

      <!-- 3. KEUANGAN IPP (DROPDOWN) -->
      <a class="sidebar-nav-item has-dropdown {{ $isIppActive ? 'parent-active' : '' }}" 
         data-bs-toggle="collapse" 
         href="#menuIpp" 
         role="button" 
         aria-expanded="{{ $isIppActive ? 'true' : 'false' }}" 
         aria-controls="menuIpp">
        <x-heroicon-o-banknotes class="heroicon" />
        <span class="nav-text">Keuangan IPP</span>
        <x-heroicon-o-chevron-right class="dropdown-arrow" style="width: 14px; height: 14px; min-width: 14px; min-height: 14px;" />
      </a>
      <div class="collapse {{ $isIppActive ? 'show' : '' }}" id="menuIpp">
        <div class="sidebar-submenu">
          <a href="{{ route('siswa.konversi') }}" class="sidebar-sub-item {{ request()->routeIs('siswa.konversi') ? 'active' : '' }}">
            <x-heroicon-o-calculator class="heroicon-sm" />
            <span class="sub-text">Konversi IPP</span>
          </a>
        </div>
      </div>

      <!-- 4. SISTEM & PENGATURAN (DROPDOWN) -->
      <a class="sidebar-nav-item has-dropdown {{ $isSystemActive ? 'parent-active' : '' }}" 
         data-bs-toggle="collapse" 
         href="#menuSystem" 
         role="button" 
         aria-expanded="{{ $isSystemActive ? 'true' : 'false' }}" 
         aria-controls="menuSystem">
        <x-heroicon-o-cog-6-tooth class="heroicon" />
        <span class="nav-text">Sistem & Pengaturan</span>
        <x-heroicon-o-chevron-right class="dropdown-arrow" style="width: 14px; height: 14px; min-width: 14px; min-height: 14px;" />
      </a>
      <div class="collapse {{ $isSystemActive ? 'show' : '' }}" id="menuSystem">
        <div class="sidebar-submenu">
          <a href="{{ route('account.index') }}" class="sidebar-sub-item {{ request()->routeIs('account.*') ? 'active' : '' }}">
            <x-heroicon-o-shield-check class="heroicon-sm" />
            <span class="sub-text">Manajemen Akun</span>
          </a>
          <a href="{{ route('activity.index') }}" class="sidebar-sub-item {{ request()->routeIs('activity.*') ? 'active' : '' }}">
            <x-heroicon-o-clock class="heroicon-sm" />
            <span class="sub-text">Log Aktivitas</span>
          </a>
          <a href="{{ route('settings.index') }}" class="sidebar-sub-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
            <x-heroicon-o-adjustments-horizontal class="heroicon-sm" />
            <span class="sub-text">Pengaturan Sistem</span>
          </a>
        </div>
      </div>
    @endif

    @if($lvl === 'wali' || in_array($lvl, ['walikelas', 'wali kelas']))
      <!-- Menu Wali Kelas -->
      <div class="sidebar-nav-title">Menu Kelas Binaan</div>
      <a class="sidebar-nav-item has-dropdown {{ $isWaliClassActive ? 'parent-active' : '' }}" 
         data-bs-toggle="collapse" 
         href="#menuWaliKelas" 
         role="button" 
         aria-expanded="{{ $isWaliClassActive ? 'true' : 'false' }}" 
         aria-controls="menuWaliKelas">
        <x-heroicon-o-user-group class="heroicon" />
        <span class="nav-text">Kelas {{ $user->kelas->nama_kelas ?? ($user->effectiveKelas()->nama_kelas ?? 'Binaan') }}</span>
        @if($notifCount > 0)
          <span class="badge bg-danger rounded-pill">{{ $notifCount }}</span>
        @endif
        <x-heroicon-o-chevron-right class="dropdown-arrow" style="width: 14px; height: 14px; min-width: 14px; min-height: 14px;" />
      </a>
      <div class="collapse {{ $isWaliClassActive ? 'show' : '' }}" id="menuWaliKelas">
        <div class="sidebar-submenu">
          <a href="{{ route('siswa.index') }}" class="sidebar-sub-item {{ (request()->routeIs('siswa.index') || request()->routeIs('siswa.show') || request()->routeIs('siswa.edit')) ? 'active' : '' }}">
            <x-heroicon-o-users class="heroicon-sm" />
            <span class="sub-text">Data Siswa Kelas</span>
          </a>
          <a href="{{ route('siswa.create') }}" class="sidebar-sub-item {{ request()->routeIs('siswa.create') ? 'active' : '' }}">
            <x-heroicon-o-user-plus class="heroicon-sm" />
            <span class="sub-text">Tambah Siswa</span>
          </a>
          <a href="{{ route('notifications.index') }}" class="sidebar-sub-item {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
            <x-heroicon-o-clipboard-document-check class="heroicon-sm" />
            <span class="sub-text">Validasi & Progres</span>
            @if($notifCount > 0)
              <span class="badge bg-danger rounded-pill">{{ $notifCount }}</span>
            @endif
          </a>
        </div>
      </div>
    @endif

    <!-- Akun Pengguna -->
    <div class="sidebar-nav-title">Akun Pengguna</div>
    <a href="{{ route('profile.index') }}" class="sidebar-nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
      <x-heroicon-o-user-circle class="heroicon" />
      <span class="nav-text">Profil Akun</span>
    </a>

    <!-- Keluar -->
    <form method="POST" action="{{ route('logout') }}" id="sidebarLogoutForm">
      @csrf
      <a href="javascript:void(0)" class="sidebar-nav-item btn-logout-confirm" data-form="sidebarLogoutForm" style="color: #f87171;">
        <x-heroicon-o-arrow-left-on-rectangle class="heroicon" style="color: #f87171;" />
        <span class="nav-text">Keluar</span>
      </a>
    </form>
  </nav>

  <!-- Sidebar Footer: Logged in User Status -->
  <div class="sidebar-footer d-flex align-items-center gap-2">
    @if($user->photo_url)
      <img src="{{ $user->photo_url }}" alt="{{ $user->nama }}" class="rounded-circle object-fit-cover flex-shrink-0 border border-1 border-secondary" style="width: 34px; height: 34px;">
    @else
      <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 34px; height: 34px; font-size: 0.85rem;">
        {{ strtoupper(substr($user->nama ?? $user->username ?? 'U', 0, 1)) }}
      </div>
    @endif
    <div class="overflow-hidden">
      <div class="text-white fw-semibold text-truncate" style="font-size: 0.88rem;">{{ $user->nama ?? $user->username }}</div>
      <div class="text-secondary small d-flex align-items-center gap-1 mt-0.5" style="font-size: 0.72rem;">
        <span class="d-inline-block rounded-circle bg-success" style="width: 6px; height: 6px;"></span>
        <span>{{ $user->role_name }}</span>
        @if($user->kelas)
          <span class="badge bg-secondary-subtle text-secondary py-0 px-1" style="font-size: 0.68rem;">{{ $user->kelas->nama_kelas }}</span>
        @endif
      </div>
    </div>
  </div>
</aside>