@php
  $appName = \App\Models\AppSetting::get('app_name', 'E-IPP');
  $schoolName = \App\Models\AppSetting::get('school_name', 'SMAN Benlutu');
  $schoolLogo = \App\Models\AppSetting::get('school_logo', 'logo_1767853884.png');
  $activeTa = \App\Models\AppSetting::get('active_tahun_ajaran', '2025/2026');
  $activeSem = \App\Models\AppSetting::get('active_semester', 'Genap');
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
@endphp

<header class="laravel-navbar">
  <div class="container-fluid px-3 px-md-4">
    <div class="d-flex align-items-center justify-content-between py-2">
      <!-- Left: Brand & Nav Links -->
      <div class="d-flex align-items-center gap-3 gap-lg-4">
        <a href="{{ route('dashboard') }}" class="laravel-brand">
          <div class="p-1 bg-light rounded-3 border">
            <img src="{{ asset('assets/dist/img/' . $schoolLogo) }}" alt="Logo">
          </div>
          <div class="d-none d-sm-block">
            <div class="lh-sm fw-bold text-dark">{{ $appName }}</div>
            <div class="text-secondary small fw-normal" style="font-size: 0.76rem;">{{ $schoolName }}</div>
          </div>
        </a>

        <!-- Desktop Navigation Links -->
        <nav class="d-none d-lg-flex align-items-center gap-1">
          <a href="{{ route('dashboard') }}" class="laravel-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <x-heroicon-o-squares-2x2 class="heroicon-sm" />
            Dashboard
          </a>

          @if($lvl === 'admin')
            <!-- Dropdown Akademik & Siswa -->
            <div class="dropdown">
              <button class="laravel-dropdown-toggle {{ (request()->routeIs('siswa.*') || request()->routeIs('kelas.*') || request()->routeIs('walikelas.*') || request()->routeIs('gtk.*') || request()->routeIs('notifications.*') || request()->routeIs('siswapindah.*') || request()->routeIs('kenaikankelas.*') || request()->routeIs('kelulusan.*')) && !request()->routeIs('siswa.konversi') ? 'active' : '' }}" type="button" data-bs-toggle="dropdown">
                <x-heroicon-o-academic-cap class="heroicon-sm" />
                Akademik &amp; Siswa
                <x-heroicon-o-chevron-down class="heroicon-xs ms-1" style="width: 12px; height: 12px;" />
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item {{ request()->routeIs('siswa.index') ? 'active' : '' }}" href="{{ route('siswa.index') }}"><x-heroicon-o-users class="heroicon-sm text-primary me-1" /> Data Siswa</a></li>
                <li><a class="dropdown-item {{ request()->routeIs('kelas.*') ? 'active' : '' }}" href="{{ route('kelas.index') }}"><x-heroicon-o-building-library class="heroicon-sm text-primary me-1" /> Data Kelas</a></li>
                <li><a class="dropdown-item {{ request()->routeIs('walikelas.*') ? 'active' : '' }}" href="{{ route('walikelas.index') }}"><x-heroicon-o-user-group class="heroicon-sm text-primary me-1" /> Data Wali Kelas</a></li>
                <li><a class="dropdown-item {{ request()->routeIs('gtk.*') ? 'active' : '' }}" href="{{ route('gtk.index') }}"><x-heroicon-o-identification class="heroicon-sm text-primary me-1" /> Data GTK</a></li>
                <li>
                  <a class="dropdown-item {{ request()->routeIs('notifications.*') ? 'active' : '' }}" href="{{ route('notifications.index') }}">
                    <x-heroicon-o-clipboard-document-check class="heroicon-sm text-primary me-1" /> Validasi &amp; Progres
                    @if($notifCount > 0)
                      <span class="badge bg-danger ms-auto">{{ $notifCount }}</span>
                    @endif
                  </a>
                </li>
                <li><hr class="dropdown-divider my-1"></li>
                <li><a class="dropdown-item {{ request()->routeIs('siswapindah.*') ? 'active' : '' }}" href="{{ route('siswapindah.index') }}"><x-heroicon-o-arrows-right-left class="heroicon-sm text-secondary me-1" /> Pindah Kelas</a></li>
                <li><a class="dropdown-item {{ request()->routeIs('kenaikankelas.*') ? 'active' : '' }}" href="{{ route('kenaikankelas.index') }}"><x-heroicon-o-arrow-trending-up class="heroicon-sm text-secondary me-1" /> Kenaikan Kelas</a></li>
                <li><a class="dropdown-item {{ request()->routeIs('kelulusan.*') ? 'active' : '' }}" href="{{ route('kelulusan.index') }}"><x-heroicon-o-academic-cap class="heroicon-sm text-secondary me-1" /> Kelulusan &amp; Alumni</a></li>
              </ul>
            </div>

            <!-- Dropdown Referensi & Konversi -->
            <div class="dropdown">
              <button class="laravel-dropdown-toggle {{ request()->routeIs('master.*') || request()->routeIs('siswa.konversi') ? 'active' : '' }}" type="button" data-bs-toggle="dropdown">
                <x-heroicon-o-adjustments-horizontal class="heroicon-sm" />
                Master &amp; Konversi
                <x-heroicon-o-chevron-down class="heroicon-xs ms-1" style="width: 12px; height: 12px;" />
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item {{ request()->routeIs('master.*') ? 'active' : '' }}" href="{{ route('master.index') }}"><x-heroicon-o-circle-stack class="heroicon-sm text-primary me-1" /> Data Referensi</a></li>
                <li><a class="dropdown-item {{ request()->routeIs('siswa.konversi') ? 'active' : '' }}" href="{{ route('siswa.konversi') }}"><x-heroicon-o-calculator class="heroicon-sm text-primary me-1" /> Konversi IPP</a></li>
              </ul>
            </div>

            <!-- Dropdown Sistem -->
            <div class="dropdown">
              <button class="laravel-dropdown-toggle {{ request()->routeIs('account.*') || request()->routeIs('activity.*') || request()->routeIs('settings.*') ? 'active' : '' }}" type="button" data-bs-toggle="dropdown">
                <x-heroicon-o-cog-6-tooth class="heroicon-sm" />
                Sistem
                <x-heroicon-o-chevron-down class="heroicon-xs ms-1" style="width: 12px; height: 12px;" />
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item {{ request()->routeIs('account.*') ? 'active' : '' }}" href="{{ route('account.index') }}"><x-heroicon-o-user-circle class="heroicon-sm text-primary me-1" /> Manajemen Akun</a></li>
                <li><a class="dropdown-item {{ request()->routeIs('activity.*') ? 'active' : '' }}" href="{{ route('activity.index') }}"><x-heroicon-o-clock class="heroicon-sm text-primary me-1" /> Recent Activity</a></li>
                <li><a class="dropdown-item {{ request()->routeIs('settings.*') ? 'active' : '' }}" href="{{ route('settings.index') }}"><x-heroicon-o-wrench-screwdriver class="heroicon-sm text-primary me-1" /> Pengaturan Sistem</a></li>
              </ul>
            </div>
          @endif

          @if($lvl === 'wali' || in_array($lvl, ['walikelas', 'wali kelas']))
            <a href="{{ route('siswa.index') }}" class="laravel-nav-link {{ request()->routeIs('siswa.*') ? 'active' : '' }}">
              <x-heroicon-o-academic-cap class="heroicon-sm" />
              Data Siswa Kelas
            </a>
            <a href="{{ route('notifications.index') }}" class="laravel-nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
              <x-heroicon-o-clipboard-document-check class="heroicon-sm" />
              Validasi
              @if($notifCount > 0)
                <span class="badge bg-danger ms-1">{{ $notifCount }}</span>
              @endif
            </a>
          @endif
        </nav>
      </div>

      <!-- Right: Period Indicator & User Profile -->
      <div class="d-flex align-items-center gap-2 gap-sm-3">
        <!-- Academic Period Badge -->
        <span class="badge bg-light text-secondary border px-2 py-1 d-none d-xl-inline-block small" style="font-size: 0.75rem;">
          <x-heroicon-o-calendar-days class="heroicon-sm me-1 text-primary" />
          TA: {{ $activeTa }} ({{ $activeSem }})
        </span>

        <!-- Public Portal Link -->
        <a href="{{ route('welcome') }}" target="_blank" class="btn btn-light btn-sm text-secondary border d-inline-flex align-items-center gap-1" title="Buka Portal Publik">
          <x-heroicon-o-globe-alt class="heroicon-sm" />
          <span class="d-none d-md-inline">Portal Publik</span>
        </a>
          <span class="d-none d-md-inline">Portal Publik</span>
        </a>

        <!-- User Dropdown (Laravel Style) -->
        <div class="dropdown">
          <button class="btn btn-light btn-sm dropdown-toggle d-flex align-items-center gap-2 py-1 px-2 border" type="button" data-bs-toggle="dropdown" style="border-radius: 8px;">
            @if($user->photo_url)
              <img src="{{ $user->photo_url }}" alt="{{ $user->nama }}" class="rounded-circle object-fit-cover shadow-xs border" style="width: 28px; height: 28px;">
            @else
              <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width: 28px; height: 28px; font-size: 0.78rem; background: #4f46e5;">
                {{ strtoupper(substr($user->nama ?? $user->username, 0, 1)) }}
              </div>
            @endif
            <div class="text-start d-none d-md-block lh-sm">
              <div class="fw-semibold text-dark text-truncate" style="max-width: 120px; font-size: 0.82rem;">{{ $user->nama ?? $user->username }}</div>
              <div class="text-muted small" style="font-size: 0.7rem;">{{ $user->role_name }}</div>
            </div>
          </button>
          <ul class="dropdown-menu dropdown-menu-end shadow-sm">
            <li>
              <div class="px-3 py-2 border-bottom">
                <div class="fw-bold text-dark">{{ $user->nama }}</div>
                <div class="text-muted small">{{ $user->email ?? $user->username }}</div>
                <div class="mt-1">
                  <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 0.68rem;">Role: {{ $user->role_name }}</span>
                  @if($user->kelas)
                    <span class="badge bg-light text-secondary border ms-1" style="font-size: 0.68rem;">{{ $user->kelas->nama_kelas }}</span>
                  @endif
                </div>
              </div>
            </li>
            <li>
              <a class="dropdown-item py-2" href="{{ route('profile.index') }}">
                <x-heroicon-o-user-circle class="heroicon-sm text-primary me-1" /> Profil Saya
              </a>
            </li>
            @if($lvl === 'admin')
              <li>
                <a class="dropdown-item py-2" href="{{ route('settings.index') }}">
                  <x-heroicon-o-wrench-screwdriver class="heroicon-sm text-primary me-1" /> Pengaturan
                </a>
              </li>
            @endif
            <li><hr class="dropdown-divider my-1"></li>
            <li>
              <form method="POST" action="{{ route('logout') }}" id="logoutFormNavbar">
                @csrf
                <button type="submit" class="dropdown-item py-2 text-danger border-0 bg-transparent w-100 text-start">
                  <x-heroicon-o-arrow-right-on-rectangle class="heroicon-sm text-danger me-1" /> Keluar
                </button>
              </form>
            </li>
          </ul>
        </div>

        <!-- Mobile Menu Toggle Button -->
        <button class="btn btn-light btn-sm d-lg-none border p-2" type="button" data-bs-toggle="collapse" data-bs-target="#mobileNavCollapse">
          <x-heroicon-o-bars-3 class="heroicon" />
        </button>
      </div>
    </div>

    <!-- Mobile Navigation Drawer / Collapse (Laravel Style) -->
    <div class="collapse d-lg-none pb-3 border-top pt-2" id="mobileNavCollapse">
      <div class="d-flex flex-column gap-1">
        <a href="{{ route('dashboard') }}" class="laravel-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
          <x-heroicon-o-squares-2x2 class="heroicon-sm" />
          Dashboard
        </a>

        @if($lvl === 'admin')
          <div class="text-uppercase text-muted fw-bold px-2 pt-2 pb-1" style="font-size: 0.68rem; letter-spacing: 0.05em;">Akademik &amp; Siswa</div>
          <a href="{{ route('siswa.index') }}" class="laravel-nav-link {{ request()->routeIs('siswa.index') ? 'active' : '' }}">
            <x-heroicon-o-users class="heroicon-sm" />
            Data Siswa
          </a>
          <a href="{{ route('kelas.index') }}" class="laravel-nav-link {{ request()->routeIs('kelas.*') ? 'active' : '' }}">
            <x-heroicon-o-building-library class="heroicon-sm" />
            Data Kelas
          </a>
          <a href="{{ route('walikelas.index') }}" class="laravel-nav-link {{ request()->routeIs('walikelas.*') ? 'active' : '' }}">
            <x-heroicon-o-user-group class="heroicon-sm" />
            Data Wali Kelas
          </a>
          <a href="{{ route('gtk.index') }}" class="laravel-nav-link {{ request()->routeIs('gtk.*') ? 'active' : '' }}">
            <x-heroicon-o-identification class="heroicon-sm" />
            Data GTK
          </a>
          <a href="{{ route('notifications.index') }}" class="laravel-nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
            <x-heroicon-o-clipboard-document-check class="heroicon-sm" />
            Validasi &amp; Progres
            @if($notifCount > 0)
              <span class="badge bg-danger ms-auto">{{ $notifCount }}</span>
            @endif
          </a>
          <a href="{{ route('siswapindah.index') }}" class="laravel-nav-link {{ request()->routeIs('siswapindah.*') ? 'active' : '' }}">
            <x-heroicon-o-arrows-right-left class="heroicon-sm" />
            Pindah Kelas
          </a>
          <a href="{{ route('kenaikankelas.index') }}" class="laravel-nav-link {{ request()->routeIs('kenaikankelas.*') ? 'active' : '' }}">
            <x-heroicon-o-arrow-trending-up class="heroicon-sm" />
            Kenaikan Kelas
          </a>
          <a href="{{ route('kelulusan.index') }}" class="laravel-nav-link {{ request()->routeIs('kelulusan.*') ? 'active' : '' }}">
            <x-heroicon-o-academic-cap class="heroicon-sm" />
            Kelulusan &amp; Alumni
          </a>

          <div class="text-uppercase text-muted fw-bold px-2 pt-3 pb-1" style="font-size: 0.68rem; letter-spacing: 0.05em;">Master &amp; Sistem</div>
          <a href="{{ route('master.index') }}" class="laravel-nav-link {{ request()->routeIs('master.*') ? 'active' : '' }}">
            <x-heroicon-o-circle-stack class="heroicon-sm" />
            Data Referensi
          </a>
          <a href="{{ route('siswa.konversi') }}" class="laravel-nav-link {{ request()->routeIs('siswa.konversi') ? 'active' : '' }}">
            <x-heroicon-o-calculator class="heroicon-sm" />
            Konversi IPP
          </a>
          <a href="{{ route('account.index') }}" class="laravel-nav-link {{ request()->routeIs('account.*') ? 'active' : '' }}">
            <x-heroicon-o-user-circle class="heroicon-sm" />
            Manajemen Akun
          </a>
          <a href="{{ route('activity.index') }}" class="laravel-nav-link {{ request()->routeIs('activity.*') ? 'active' : '' }}">
            <x-heroicon-o-clock class="heroicon-sm" />
            Recent Activity
          </a>
          <a href="{{ route('settings.index') }}" class="laravel-nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
            <x-heroicon-o-wrench-screwdriver class="heroicon-sm" />
            Pengaturan Sistem
          </a>
        @endif

        @if($lvl === 'wali' || in_array($lvl, ['walikelas', 'wali kelas']))
          <a href="{{ route('siswa.index') }}" class="laravel-nav-link {{ request()->routeIs('siswa.*') ? 'active' : '' }}">
            <x-heroicon-o-academic-cap class="heroicon-sm" />
            Data Siswa Kelas
          </a>
          <a href="{{ route('notifications.index') }}" class="laravel-nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
            <x-heroicon-o-clipboard-document-check class="heroicon-sm" />
            Validasi
            @if($notifCount > 0)
              <span class="badge bg-danger ms-auto">{{ $notifCount }}</span>
            @endif
          </a>
        @endif

        <div class="text-uppercase text-muted fw-bold px-2 pt-3 pb-1" style="font-size: 0.68rem; letter-spacing: 0.05em;">Akun</div>
        <a href="{{ route('profile.index') }}" class="laravel-nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
          <x-heroicon-o-user-circle class="heroicon-sm" />
          Profil Saya
        </a>
      </div>
    </div>
  </div>
</header>