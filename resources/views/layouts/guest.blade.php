<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @php
    $appName = \App\Models\AppSetting::get('app_name', 'E-IPP');
    $schoolName = \App\Models\AppSetting::get('school_name', 'SMAN Benlutu');
    $schoolLogo = \App\Models\AppSetting::get('school_logo', 'logo_1767853884.png');
  @endphp
  <title>@yield('title', 'Masuk') - {{ $appName }} {{ $schoolName }}</title>
  <link rel="icon" href="{{ asset('assets/dist/img/' . $schoolLogo) }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
  <style>
    [x-cloak] { display: none !important; }

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
    body {
      background: radial-gradient(circle at 50% 10%, #e0f2fe 0%, #f1f5f9 55%, #e2e8f0 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2.5rem 1rem;
      margin: 0;
      font-family: 'Roboto', -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      color: #1e293b;
    }
    .login-container {
      width: 100%;
      max-width: 440px;
    }
    .login-card {
      background: #ffffff !important;
      border: 1px solid #e2e8f0;
      border-radius: 20px;
      box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.1), 0 0 1px 1px rgba(15, 23, 42, 0.03);
      overflow: hidden;
    }
    .form-control {
      border-color: #cbd5e1;
      border-radius: 10px;
      font-size: 0.92rem;
      padding: 0.6rem 0.85rem;
    }
    .form-control:focus {
      border-color: #2563eb;
      box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }
    .input-group-text {
      background-color: #f8fafc;
      border-color: #cbd5e1;
      color: #64748b;
      border-radius: 10px 0 0 10px;
    }
    .btn-login {
      background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
      border: none;
      color: #ffffff;
      font-weight: 600;
      padding: 0.7rem 1.25rem;
      border-radius: 10px;
      box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      width: 100%;
      transition: all 0.2s ease;
    }
    .btn-login:hover {
      background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
      box-shadow: 0 6px 18px rgba(37, 99, 235, 0.4);
      color: #ffffff;
      transform: translateY(-1px);
    }
    .btn-login:active {
      transform: translateY(0);
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="login-card">
      @yield('content')
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>