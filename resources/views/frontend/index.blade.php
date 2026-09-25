<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @php
    $appName = $appName ?? \App\Models\AppSetting::get('app_name', 'E-IPP');
    $schoolName = $schoolName ?? \App\Models\AppSetting::get('school_name', 'SMAN Benlutu');
    $schoolLogo = $schoolLogo ?? \App\Models\AppSetting::get('school_logo', 'logo_1767853884.png');
    $copyright = $copyright ?? \App\Models\AppSetting::get('copyright', 'Copyright &copy; ' . date('Y') . ' ' . $schoolName . '. Hak cipta dilindungi.');
    $total = max(1, $totalSiswa);
  @endphp
  <title>{{ $schoolName }} &mdash; Portal Publik {{ $appName }}</title>
  <link rel="icon" href="{{ asset('assets/dist/img/' . $schoolLogo) }}">

  <!-- Fonts & Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <!-- Bootstrap 5.3.3 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

  <style>
    :root {
      --primary: #059669;
      --primary-dark: #047857;
      --primary-deep: #064e3b;
      --primary-light: #ecfdf5;
      --accent: #2563eb;
      --accent-light: #eff6ff;
      --amber: #d97706;
      --amber-light: #fffbeb;
      --rose: #e11d48;
      --rose-light: #fff1f2;
      --purple: #7c3aed;
      --purple-light: #f5f3ff;
      --slate-900: #0f172a;
      --slate-800: #1e293b;
      --slate-700: #334155;
      --slate-600: #475569;
      --slate-500: #64748b;
      --slate-400: #94a3b8;
      --slate-200: #e2e8f0;
      --slate-100: #f1f5f9;
      --slate-50: #f8fafc;
      --surface: #ffffff;
      --font-main: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      --font-display: 'Outfit', 'Plus Jakarta Sans', sans-serif;
      --radius-sm: 8px;
      --radius-md: 12px;
      --radius-lg: 16px;
      --radius-xl: 24px;
      --shadow-subtle: 0 4px 20px -2px rgba(15, 23, 42, 0.05);
      --shadow-hover: 0 16px 32px -8px rgba(15, 23, 42, 0.12);
    }

    * { box-sizing: border-box; }
    html { scroll-behavior: smooth; }

    body {
      margin: 0;
      font-family: var(--font-main);
      background-color: #f8fafc;
      color: var(--slate-800);
      line-height: 1.6;
      -webkit-font-smoothing: antialiased;
      overflow-x: hidden;
    }

    /* Standard Heroicons Styling */
    .heroicon {
      width: 20px;
      height: 20px;
      display: inline-block;
      vertical-align: middle;
      flex-shrink: 0;
    }
    .heroicon-sm {
      width: 16px;
      height: 16px;
      display: inline-block;
      vertical-align: middle;
      flex-shrink: 0;
    }
    .heroicon-lg {
      width: 24px;
      height: 24px;
      display: inline-block;
      vertical-align: middle;
      flex-shrink: 0;
    }

    /* Container */
    .container-custom {
      width: min(1200px, calc(100% - 2rem));
      margin-inline: auto;
    }

    /* Header & Navigation */
    .site-nav {
      position: sticky;
      top: 0;
      z-index: 1000;
      background: rgba(255, 255, 255, 0.88);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border-bottom: 1px solid rgba(226, 232, 240, 0.8);
      transition: all 0.25s ease;
    }

    .nav-inner {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0.85rem 0;
      gap: 1rem;
    }

    .brand-wrap {
      display: flex;
      align-items: center;
      gap: 0.85rem;
      text-decoration: none;
      color: inherit;
    }

    .brand-logo-frame {
      width: 44px;
      height: 44px;
      border-radius: var(--radius-md);
      background: #ffffff;
      padding: 4px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
      border: 1px solid var(--slate-200);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      transition: transform 0.2s ease;
    }

    .brand-wrap:hover .brand-logo-frame {
      transform: scale(1.05);
    }

    .brand-logo-frame img {
      width: 100%;
      height: 100%;
      object-fit: contain;
    }

    .brand-title {
      font-family: var(--font-display);
      font-weight: 700;
      font-size: 1.15rem;
      color: var(--slate-900);
      line-height: 1.2;
      letter-spacing: -0.01em;
    }

    .brand-subtitle {
      font-size: 0.75rem;
      font-weight: 600;
      color: var(--primary);
      text-transform: uppercase;
      letter-spacing: 0.05em;
      display: flex;
      align-items: center;
      gap: 0.35rem;
    }

    .status-dot-live {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: #10b981;
      display: inline-block;
      box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.25);
      animation: pulseLive 2s infinite;
    }

    @keyframes pulseLive {
      0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
      70% { box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
      100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }

    .nav-actions {
      display: flex;
      align-items: center;
      gap: 0.6rem;
    }

    .btn-nav-outline {
      display: inline-flex;
      align-items: center;
      gap: 0.45rem;
      padding: 0.55rem 1rem;
      border-radius: var(--radius-sm);
      font-size: 0.84rem;
      font-weight: 600;
      color: var(--slate-700);
      background: #ffffff;
      border: 1px solid var(--slate-200);
      text-decoration: none;
      transition: all 0.2s ease;
    }

    .btn-nav-outline:hover {
      background: var(--slate-100);
      color: var(--slate-900);
      border-color: var(--slate-400);
    }

    .btn-nav-primary {
      display: inline-flex;
      align-items: center;
      gap: 0.45rem;
      padding: 0.55rem 1.15rem;
      border-radius: var(--radius-sm);
      font-size: 0.84rem;
      font-weight: 600;
      color: #ffffff;
      background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
      border: 1px solid transparent;
      text-decoration: none;
      box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);
      transition: all 0.2s ease;
    }

    .btn-nav-primary:hover {
      background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-deep) 100%);
      color: #ffffff;
      transform: translateY(-1px);
      box-shadow: 0 6px 16px rgba(5, 150, 105, 0.35);
    }

    /* Hero Section */
    .hero-section {
      position: relative;
      background: linear-gradient(135deg, #064e3b 0%, #0f172a 65%, #022c22 100%);
      color: #ffffff;
      padding: 4.5rem 0 3.8rem;
      overflow: hidden;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .hero-glow-1 {
      position: absolute;
      top: -120px;
      right: -80px;
      width: 500px;
      height: 500px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(16, 185, 129, 0.25), transparent 70%);
      filter: blur(40px);
      pointer-events: none;
    }

    .hero-glow-2 {
      position: absolute;
      bottom: -150px;
      left: -100px;
      width: 600px;
      height: 600px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(37, 99, 235, 0.18), transparent 70%);
      filter: blur(50px);
      pointer-events: none;
    }

    .hero-grid-pattern {
      position: absolute;
      inset: 0;
      background-image: 
        radial-gradient(rgba(255, 255, 255, 0.08) 1px, transparent 1px);
      background-size: 24px 24px;
      opacity: 0.6;
      pointer-events: none;
    }

    .hero-content {
      position: relative;
      z-index: 2;
    }

    .badge-pill-light {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.4rem 0.95rem;
      border-radius: 9999px;
      background: rgba(255, 255, 255, 0.12);
      border: 1px solid rgba(255, 255, 255, 0.2);
      font-size: 0.78rem;
      font-weight: 600;
      color: #a7f3d0;
      backdrop-filter: blur(8px);
      margin-bottom: 1.25rem;
      letter-spacing: 0.02em;
    }

    .hero-title {
      font-family: var(--font-display);
      font-size: clamp(2.2rem, 4.5vw, 3.6rem);
      font-weight: 800;
      line-height: 1.1;
      letter-spacing: -0.03em;
      margin-bottom: 1rem;
      color: #ffffff;
    }

    .hero-title .text-gradient {
      background: linear-gradient(135deg, #a7f3d0 0%, #34d399 50%, #6ee7b7 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .hero-desc {
      font-size: clamp(0.98rem, 1.8vw, 1.15rem);
      color: rgba(255, 255, 255, 0.82);
      max-width: 620px;
      font-weight: 300;
      line-height: 1.6;
      margin-bottom: 2rem;
    }

    .hero-cta-group {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      gap: 0.85rem;
      margin-bottom: 2.5rem;
    }

    .btn-hero-solid {
      display: inline-flex;
      align-items: center;
      gap: 0.55rem;
      padding: 0.75rem 1.45rem;
      border-radius: var(--radius-md);
      font-weight: 700;
      font-size: 0.92rem;
      color: #064e3b;
      background: #ffffff;
      text-decoration: none;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
      transition: all 0.2s ease;
    }

    .btn-hero-solid:hover {
      background: #f0fdf4;
      color: #047857;
      transform: translateY(-2px);
      box-shadow: 0 14px 30px rgba(0, 0, 0, 0.25);
    }

    .btn-hero-translucent {
      display: inline-flex;
      align-items: center;
      gap: 0.55rem;
      padding: 0.75rem 1.45rem;
      border-radius: var(--radius-md);
      font-weight: 600;
      font-size: 0.92rem;
      color: #ffffff;
      background: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.25);
      text-decoration: none;
      backdrop-filter: blur(8px);
      transition: all 0.2s ease;
    }

    .btn-hero-translucent:hover {
      background: rgba(255, 255, 255, 0.18);
      color: #ffffff;
      border-color: rgba(255, 255, 255, 0.4);
    }

    /* Floating Filter Card */
    .filter-card {
      background: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border-radius: var(--radius-lg);
      padding: 1.25rem 1.5rem;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
      max-width: 720px;
    }

    .filter-card-label {
      font-size: 0.72rem;
      font-weight: 700;
      color: #a7f3d0;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      margin-bottom: 0.4rem;
      display: flex;
      align-items: center;
      gap: 0.4rem;
    }

    .filter-select {
      background: rgba(15, 23, 42, 0.5);
      border: 1px solid rgba(255, 255, 255, 0.25);
      color: #ffffff;
      border-radius: var(--radius-sm);
      padding: 0.65rem 0.85rem;
      font-size: 0.9rem;
      font-weight: 500;
      width: 100%;
      outline: none;
      transition: all 0.2s ease;
    }

    .filter-select:focus {
      border-color: #34d399;
      box-shadow: 0 0 0 3px rgba(52, 211, 153, 0.2);
    }

    .filter-select option {
      background: #1e293b;
      color: #ffffff;
    }

    .btn-filter-apply {
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      color: #ffffff;
      border: none;
      border-radius: var(--radius-sm);
      font-weight: 700;
      font-size: 0.9rem;
      padding: 0.68rem 1.25rem;
      width: 100%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.45rem;
      cursor: pointer;
      box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
      transition: all 0.2s ease;
    }

    .btn-filter-apply:hover {
      background: linear-gradient(135deg, #059669 0%, #047857 100%);
      transform: translateY(-1px);
      box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
    }

    /* Main Sections */
    .page-body {
      padding: 3rem 0 4rem;
    }

    .section-wrap {
      margin-bottom: 3.25rem;
    }

    .section-header {
      display: flex;
      align-items: flex-end;
      justify-content: space-between;
      gap: 1rem;
      margin-bottom: 1.5rem;
      flex-wrap: wrap;
    }

    .section-badge {
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      padding: 0.25rem 0.65rem;
      border-radius: 9999px;
      font-size: 0.72rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      margin-bottom: 0.35rem;
    }

    .section-title {
      font-family: var(--font-display);
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--slate-900);
      letter-spacing: -0.02em;
      margin: 0;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .section-desc {
      font-size: 0.88rem;
      color: var(--slate-500);
      margin: 0.25rem 0 0;
    }

    /* Metric Cards Grid */
    .metric-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 1.25rem;
    }

    .metric-card {
      background: var(--surface);
      border: 1px solid var(--slate-200);
      border-radius: var(--radius-lg);
      padding: 1.5rem;
      box-shadow: var(--shadow-subtle);
      transition: all 0.25s ease;
      position: relative;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .metric-card:hover {
      transform: translateY(-3px);
      box-shadow: var(--shadow-hover);
      border-color: var(--slate-300);
    }

    .metric-card-top {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      margin-bottom: 1rem;
    }

    .metric-icon-wrap {
      width: 48px;
      height: 48px;
      border-radius: var(--radius-md);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .metric-badge-pct {
      font-size: 0.75rem;
      font-weight: 700;
      padding: 0.2rem 0.55rem;
      border-radius: 9999px;
    }

    .metric-num {
      font-family: var(--font-display);
      font-size: 2.35rem;
      font-weight: 800;
      letter-spacing: -0.03em;
      color: var(--slate-900);
      line-height: 1;
      margin-bottom: 0.35rem;
    }

    .metric-title {
      font-size: 0.82rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      color: var(--slate-400);
      margin-bottom: 0.25rem;
    }

    .metric-footer {
      font-size: 0.8rem;
      color: var(--slate-500);
      display: flex;
      align-items: center;
      gap: 0.35rem;
    }

    /* Afirmasi Grid (5 Cards) */
    .affirm-grid-modern {
      display: grid;
      grid-template-columns: repeat(5, 1fr);
      gap: 1rem;
    }

    .affirm-card {
      background: var(--surface);
      border: 1px solid var(--slate-200);
      border-radius: var(--radius-md);
      padding: 1.25rem 1.15rem;
      box-shadow: var(--shadow-subtle);
      transition: all 0.25s ease;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      border-top: 4px solid transparent;
    }

    .affirm-card:hover {
      transform: translateY(-3px);
      box-shadow: var(--shadow-hover);
    }

    .affirm-card.afirm-a { border-top-color: var(--amber); }
    .affirm-card.afirm-b { border-top-color: var(--rose); }
    .affirm-card.afirm-c { border-top-color: var(--purple); }
    .affirm-card.afirm-d { border-top-color: var(--accent); }
    .affirm-card.afirm-e { border-top-color: var(--primary); }

    .affirm-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 0.75rem;
    }

    .affirm-tag {
      font-size: 0.72rem;
      font-weight: 800;
      letter-spacing: 0.06em;
      text-transform: uppercase;
    }

    .affirm-icon-box {
      width: 32px;
      height: 32px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .affirm-name {
      font-size: 0.88rem;
      font-weight: 600;
      color: var(--slate-700);
      line-height: 1.35;
      min-height: 2.6em;
      margin-bottom: 0.75rem;
    }

    .affirm-count {
      font-family: var(--font-display);
      font-size: 1.9rem;
      font-weight: 800;
      color: var(--slate-900);
      letter-spacing: -0.02em;
      display: flex;
      align-items: baseline;
      gap: 0.35rem;
    }

    .affirm-count small {
      font-size: 0.75rem;
      font-weight: 500;
      color: var(--slate-400);
    }

    /* Content Cards / Surface */
    .card-modern {
      background: var(--surface);
      border: 1px solid var(--slate-200);
      border-radius: var(--radius-lg);
      box-shadow: var(--shadow-subtle);
      overflow: hidden;
    }

    .card-modern-header {
      padding: 1.15rem 1.5rem;
      border-bottom: 1px solid var(--slate-200);
      background: #fafbfc;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
    }

    .card-modern-title {
      font-family: var(--font-display);
      font-size: 1.05rem;
      font-weight: 700;
      color: var(--slate-900);
      margin: 0;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .card-modern-body {
      padding: 1.5rem;
    }

    /* Sleek Table */
    .table-modern {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      margin: 0;
    }

    .table-modern thead th {
      background: #f8fafc;
      color: var(--slate-500);
      font-size: 0.72rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      padding: 0.85rem 1.25rem;
      border-bottom: 1px solid var(--slate-200);
      white-space: nowrap;
    }

    .table-modern tbody td {
      padding: 0.95rem 1.25rem;
      border-bottom: 1px solid #f1f5f9;
      font-size: 0.88rem;
      color: var(--slate-700);
      vertical-align: middle;
    }

    .table-modern tbody tr:last-child td {
      border-bottom: none;
    }

    .table-modern tbody tr {
      transition: background 0.15s ease;
    }

    .table-modern tbody tr:hover {
      background: #f8fafc;
    }

    /* Progress bar */
    .progress-pill {
      height: 8px;
      background: #e2e8f0;
      border-radius: 9999px;
      overflow: hidden;
      flex: 1;
    }

    .progress-fill {
      height: 100%;
      border-radius: 9999px;
      background: linear-gradient(90deg, #10b981 0%, #059669 100%);
      transition: width 0.6s ease;
    }

    .progress-fill.mid {
      background: linear-gradient(90deg, #f59e0b 0%, #d97706 100%);
    }

    .progress-fill.low {
      background: linear-gradient(90deg, #f43f5e 0%, #e11d48 100%);
    }

    /* Demographics & Socio-econ Grid */
    .grid-2-col {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1.25rem;
    }

    .grid-4-col {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 1.25rem;
    }

    .tally-item {
      margin-bottom: 0.85rem;
    }

    .tally-item:last-child {
      margin-bottom: 0;
    }

    .tally-meta {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 0.82rem;
      margin-bottom: 0.35rem;
    }

    .tally-label {
      color: var(--slate-700);
      font-weight: 500;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    .tally-val {
      font-weight: 700;
      color: var(--slate-900);
      white-space: nowrap;
    }

    .tally-val small {
      font-weight: 500;
      color: var(--slate-400);
    }

    /* Search Toolbar */
    .search-input-wrap {
      position: relative;
      min-width: 280px;
    }

    .search-input-wrap .search-icon {
      position: absolute;
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--slate-400);
      pointer-events: none;
    }

    .search-input-modern {
      width: 100%;
      padding: 0.55rem 0.85rem 0.55rem 2.4rem;
      border: 1px solid var(--slate-200);
      border-radius: var(--radius-sm);
      font-size: 0.84rem;
      background: #ffffff;
      outline: none;
      transition: all 0.2s ease;
    }

    .search-input-modern:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.15);
    }

    .badge-count {
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
      padding: 0.4rem 0.75rem;
      border-radius: var(--radius-sm);
      font-size: 0.78rem;
      font-weight: 600;
      background: var(--slate-100);
      color: var(--slate-700);
      border: 1px solid var(--slate-200);
    }

    /* Student pills in family table */
    .student-pill {
      display: inline-flex;
      align-items: center;
      gap: 0.3rem;
      padding: 0.22rem 0.6rem;
      margin: 0.15rem;
      border-radius: 6px;
      font-size: 0.78rem;
      font-weight: 500;
      background: var(--slate-100);
      color: var(--slate-700);
      border: 1px solid var(--slate-200);
    }

    .avatar-initial {
      width: 32px;
      height: 32px;
      border-radius: 8px;
      background: linear-gradient(135deg, var(--primary-light) 0%, #ccfbf1 100%);
      color: var(--primary-dark);
      font-weight: 700;
      font-size: 0.82rem;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    /* Footer */
    .site-footer {
      background: #ffffff;
      border-top: 1px solid var(--slate-200);
      padding: 2.5rem 0;
      color: var(--slate-500);
      font-size: 0.84rem;
    }

    .footer-inner {
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 1rem;
    }

    /* Responsive */
    @media (max-width: 991px) {
      .metric-grid { grid-template-columns: repeat(2, 1fr); }
      .affirm-grid-modern { grid-template-columns: repeat(2, 1fr); }
      .grid-4-col { grid-template-columns: repeat(2, 1fr); }
      .grid-2-col { grid-template-columns: 1fr; }
    }

    @media (max-width: 640px) {
      .container-custom { width: calc(100% - 1.25rem); }
      .hero-section { padding: 3rem 0 2.5rem; }
      .hero-cta-group { flex-direction: column; align-items: stretch; }
      .btn-hero-solid, .btn-hero-translucent { justify-content: center; }
      .metric-grid { grid-template-columns: 1fr; }
      .affirm-grid-modern { grid-template-columns: 1fr; }
      .grid-4-col { grid-template-columns: 1fr; }
      .search-input-wrap { width: 100%; min-width: 0; }
      .footer-inner { flex-direction: column; text-align: center; }
    }
  </style>
</head>
<body>

  <!-- Top Navigation Bar -->
  <header class="site-nav">
    <div class="container-custom nav-inner">
      <a href="{{ route('welcome') }}" class="brand-wrap">
        <div class="brand-logo-frame">
          <img src="{{ asset('assets/dist/img/' . $schoolLogo) }}" alt="Logo {{ $schoolName }}">
        </div>
        <div>
          <div class="brand-title">{{ $schoolName }}</div>
          <div class="brand-subtitle">
            <span class="status-dot-live"></span>
            Portal Transparansi {{ $appName }}
          </div>
        </div>
      </a>

      <div class="nav-actions">
        <a href="{{ route('welcome') }}" class="btn-nav-outline d-none d-sm-inline-flex" title="Segarkan Data">
          <x-heroicon-o-arrow-path class="heroicon-sm" />
          <span>Muat Ulang</span>
        </a>
        @auth
          <a href="{{ route('dashboard') }}" class="btn-nav-primary">
            <x-heroicon-o-squares-2x2 class="heroicon-sm" />
            <span>Dashboard</span>
          </a>
        @else
          <a href="{{ route('login') }}" class="btn-nav-primary">
            <x-heroicon-o-arrow-right-on-rectangle class="heroicon-sm" />
            <span>Masuk Petugas</span>
          </a>
        @endauth
      </div>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="hero-section">
    <div class="hero-glow-1"></div>
    <div class="hero-glow-2"></div>
    <div class="hero-grid-pattern"></div>

    <div class="container-custom hero-content">
      <div class="badge-pill-light">
        <x-heroicon-s-sparkles class="heroicon-sm text-warning" />
        <span>Sistem Informasi Transparansi &amp; Akuntabilitas Pendidikan</span>
      </div>

      <h1 class="hero-title">
        Transparansi Pembiayaan &amp;<br>
        <span class="text-gradient">Data Sosial Ekonomi Siswa</span>
      </h1>

      <p class="hero-desc">
        Akses publik data statistik peserta didik {{ $schoolName }}, pemetaan kondisi sosial ekonomi keluarga, serta skema subsidi iuran pendidikan ({{ $appName }}) yang berkeadilan.
      </p>

      <div class="hero-cta-group">
        <a href="#rekapitulasi" class="btn-hero-solid">
          <x-heroicon-o-chart-bar class="heroicon-sm" />
          <span>Eksplorasi Rekapitulasi</span>
        </a>
        @auth
          <a href="{{ route('dashboard') }}" class="btn-hero-translucent">
            <x-heroicon-o-shield-check class="heroicon-sm" />
            <span>Panel Administrator</span>
          </a>
        @else
          <a href="{{ route('login') }}" class="btn-hero-translucent">
            <x-heroicon-o-shield-check class="heroicon-sm" />
            <span>Portal Masuk Petugas</span>
          </a>
        @endauth
      </div>

      <!-- Floating Filter Panel -->
      <div class="filter-card">
        <form action="{{ route('welcome') }}" method="GET" id="filterPeriodeForm" class="row g-2 align-items-end">
          <div class="col-sm-5">
            <label for="tahun_ajaran" class="filter-card-label">
              <x-heroicon-o-calendar class="heroicon-sm" />
              <span>Tahun Ajaran</span>
            </label>
            <select id="tahun_ajaran" name="tahun_ajaran" class="filter-select" onchange="document.getElementById('filterPeriodeForm').submit()">
              <option value="">Semua Tahun Ajaran</option>
              @foreach($daftarTahun as $ta)
                <option value="{{ $ta }}" {{ $tahunAktif == $ta ? 'selected' : '' }}>{{ $ta }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-sm-4">
            <label for="semester" class="filter-card-label">
              <x-heroicon-o-clock class="heroicon-sm" />
              <span>Semester</span>
            </label>
            <select id="semester" name="semester" class="filter-select" onchange="document.getElementById('filterPeriodeForm').submit()">
              <option value="">Semua Semester</option>
              <option value="Ganjil" {{ strtolower((string)$semesterAktif) === 'ganjil' ? 'selected' : '' }}>Semester Ganjil</option>
              <option value="Genap" {{ strtolower((string)$semesterAktif) === 'genap' ? 'selected' : '' }}>Semester Genap</option>
            </select>
          </div>

          <div class="col-sm-3">
            <button type="submit" class="btn-filter-apply">
              <x-heroicon-o-funnel class="heroicon-sm" />
              <span>Terapkan</span>
            </button>
          </div>
        </form>
      </div>

    </div>
  </section>

  <!-- Page Main Content -->
  <main id="rekapitulasi" class="page-body">
    <div class="container-custom">

      <!-- Section: Key Metrics Overview -->
      <section class="section-wrap">
        <div class="section-header">
          <div>
            <span class="section-badge bg-primary-subtle text-success">
              <x-heroicon-o-chart-bar class="heroicon-sm" />
              Ikhtisar Umum
            </span>
            <h2 class="section-title">Ringkasan Statistik Periode</h2>
            <p class="section-desc">
              Data terverifikasi untuk periode <strong>{{ $tahunAktif ?: 'Semua Tahun' }}</strong> &bull; <strong>{{ $semesterAktif ? 'Semester ' . $semesterAktif : 'Semua Semester' }}</strong>
            </p>
          </div>
          <div class="text-muted small">
            Terakhir diperbarui: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
          </div>
        </div>

        <div class="metric-grid">
          <!-- Total Siswa -->
          <div class="metric-card">
            <div>
              <div class="metric-card-top">
                <div class="metric-icon-wrap bg-success-subtle text-success">
                  <x-heroicon-o-academic-cap class="heroicon-lg" />
                </div>
                <span class="metric-badge-pct bg-success-subtle text-success">100% Aktif</span>
              </div>
              <div class="metric-title">Total Siswa Aktif</div>
              <div class="metric-num">{{ number_format($totalSiswa) }}</div>
            </div>
            <div class="metric-footer">
              <x-heroicon-o-check-circle class="heroicon-sm text-success" />
              <span>Peserta didik terdaftar</span>
            </div>
          </div>

          <!-- Siswa Laki-laki -->
          <div class="metric-card">
            <div>
              <div class="metric-card-top">
                <div class="metric-icon-wrap bg-primary-subtle text-primary">
                  <x-heroicon-o-user class="heroicon-lg" />
                </div>
                <span class="metric-badge-pct bg-primary-subtle text-primary">
                  {{ $totalSiswa > 0 ? round(($totalLaki / $totalSiswa) * 100, 1) : 0 }}%
                </span>
              </div>
              <div class="metric-title">Siswa Laki-laki</div>
              <div class="metric-num">{{ number_format($totalLaki) }}</div>
            </div>
            <div class="metric-footer">
              <x-heroicon-o-user class="heroicon-sm text-primary" />
              <span>Proporsi peserta didik</span>
            </div>
          </div>

          <!-- Siswa Perempuan -->
          <div class="metric-card">
            <div>
              <div class="metric-card-top">
                <div class="metric-icon-wrap bg-danger-subtle text-danger">
                  <x-heroicon-o-user-group class="heroicon-lg" />
                </div>
                <span class="metric-badge-pct bg-danger-subtle text-danger">
                  {{ $totalSiswa > 0 ? round(($totalPerempuan / $totalSiswa) * 100, 1) : 0 }}%
                </span>
              </div>
              <div class="metric-title">Siswa Perempuan</div>
              <div class="metric-num">{{ number_format($totalPerempuan) }}</div>
            </div>
            <div class="metric-footer">
              <x-heroicon-o-user-group class="heroicon-sm text-danger" />
              <span>Proporsi peserta didik</span>
            </div>
          </div>

          <!-- Rombel -->
          <div class="metric-card">
            <div>
              <div class="metric-card-top">
                <div class="metric-icon-wrap bg-warning-subtle text-warning">
                  <x-heroicon-o-building-library class="heroicon-lg" />
                </div>
                <span class="metric-badge-pct bg-warning-subtle text-warning">Tingkat X, XI, XII</span>
              </div>
              <div class="metric-title">Rombongan Belajar</div>
              <div class="metric-num">{{ number_format($totalKelas) }}</div>
            </div>
            <div class="metric-footer">
              <x-heroicon-o-building-library class="heroicon-sm text-warning" />
              <span>Kelas aktif terdata</span>
            </div>
          </div>
        </div>
      </section>

      <!-- Section: Afirmasi (Bantuan Khusus) -->
      <section class="section-wrap">
        <div class="section-header">
          <div>
            <span class="section-badge bg-warning-subtle text-warning">
              <x-heroicon-o-shield-exclamation class="heroicon-sm" />
              Prioritas Afirmasi
            </span>
            <h2 class="section-title">Kategori Siswa Penerima Afirmasi Khusus</h2>
            <p class="section-desc">
              Kelompok peserta didik yang mendapat perhatian istimewa, subsidi penuh (0%), serta bantuan penunjang pendidikan.
            </p>
          </div>
        </div>

        <div class="affirm-grid-modern">
          <!-- Afirmasi A -->
          <div class="affirm-card afirm-a">
            <div class="affirm-header">
              <span class="affirm-tag text-warning">Afirmasi A</span>
              <div class="affirm-icon-box bg-warning-subtle text-warning">
                <x-heroicon-o-home-modern class="heroicon-sm" />
              </div>
            </div>
            <div class="affirm-name">Anak Panti Asuhan</div>
            <div class="affirm-count">
              {{ (int)$kategoriDetail['panti_asuhan'] }}
              <small>Siswa</small>
            </div>
          </div>

          <!-- Afirmasi B -->
          <div class="affirm-card afirm-b">
            <div class="affirm-header">
              <span class="affirm-tag text-danger">Afirmasi B</span>
              <div class="affirm-icon-box bg-danger-subtle text-danger">
                <x-heroicon-o-shield-exclamation class="heroicon-sm" />
              </div>
            </div>
            <div class="affirm-name">Anak Korban Bencana</div>
            <div class="affirm-count">
              {{ (int)$kategoriDetail['korban_bencana'] }}
              <small>Siswa</small>
            </div>
          </div>

          <!-- Afirmasi C -->
          <div class="affirm-card afirm-c">
            <div class="affirm-header">
              <span class="affirm-tag text-purple" style="color: var(--purple);">Afirmasi C</span>
              <div class="affirm-icon-box bg-purple-subtle text-purple" style="background: var(--purple-light); color: var(--purple);">
                <x-heroicon-o-heart class="heroicon-sm" />
              </div>
            </div>
            <div class="affirm-name">Anak Terlantar</div>
            <div class="affirm-count">
              {{ (int)$kategoriDetail['terlantar'] }}
              <small>Siswa</small>
            </div>
          </div>

          <!-- Afirmasi D -->
          <div class="affirm-card afirm-d">
            <div class="affirm-header">
              <span class="affirm-tag text-primary">Afirmasi D</span>
              <div class="affirm-icon-box bg-primary-subtle text-primary">
                <x-heroicon-o-sparkles class="heroicon-sm" />
              </div>
            </div>
            <div class="affirm-name">Orang Tua Berkebutuhan Khusus</div>
            <div class="affirm-count">
              {{ (int)$kategoriDetail['ortu_abk'] }}
              <small>Siswa</small>
            </div>
          </div>

          <!-- Afirmasi E -->
          <div class="affirm-card afirm-e">
            <div class="affirm-header">
              <span class="affirm-tag text-success">Afirmasi E</span>
              <div class="affirm-icon-box bg-success-subtle text-success">
                <x-heroicon-o-plus-circle class="heroicon-sm" />
              </div>
            </div>
            <div class="affirm-name">Orang Tua Sakit Menahun</div>
            <div class="affirm-count">
              {{ (int)$kategoriDetail['ortu_sakit_menahun'] }}
              <small>Siswa</small>
            </div>
          </div>
        </div>
      </section>

      <!-- Section: Kelengkapan Data per Rombel -->
      @if(!empty($classProgress))
        <section class="section-wrap">
          <div class="section-header">
            <div>
              <span class="section-badge bg-info-subtle text-info">
                <x-heroicon-o-building-library class="heroicon-sm" />
                Validitas Rombel
              </span>
              <h2 class="section-title">Kelengkapan Data per Rombongan Belajar</h2>
              <p class="section-desc">
                Pantauan transparansi dan progres kelengkapan identitas serta dokumen pendukung siswa di setiap kelas.
              </p>
            </div>
            <div class="badge-count">
              <x-heroicon-o-building-library class="heroicon-sm text-primary" />
              <span>{{ count($classProgress) }} Rombongan Belajar</span>
            </div>
          </div>

          <div class="card-modern">
            <div class="table-responsive">
              <table class="table-modern">
                <thead>
                  <tr>
                    <th>Kelas / Rombel</th>
                    <th class="text-center" style="width: 110px;">Total Siswa</th>
                    <th class="text-center" style="width: 120px;">Lengkap</th>
                    <th class="text-center" style="width: 120px;">Belum Lengkap</th>
                    <th style="min-width: 220px;">Persentase Kesiapan</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($classProgress as $row)
                    @php
                      $pct = (int) ($row['complete_pct'] ?? $row['avg_percentage'] ?? 0);
                      $tone = $pct >= 80 ? '' : ($pct >= 50 ? 'mid' : 'low');
                    @endphp
                    <tr>
                      <td>
                        <div class="d-flex align-items-center gap-2">
                          <div class="avatar-initial">{{ substr($row['nama_kelas'], 0, 3) }}</div>
                          <strong class="text-slate-900">{{ $row['nama_kelas'] }}</strong>
                        </div>
                      </td>
                      <td class="text-center fw-semibold">{{ (int)$row['total'] }}</td>
                      <td class="text-center">
                        <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill">
                          <x-heroicon-o-check-circle class="heroicon-sm me-1" />{{ (int)$row['complete'] }}
                        </span>
                      </td>
                      <td class="text-center">
                        @if((int)$row['incomplete'] > 0)
                          <span class="badge bg-danger-subtle text-danger px-2 py-1 rounded-pill">
                            <x-heroicon-o-exclamation-circle class="heroicon-sm me-1" />{{ (int)$row['incomplete'] }}
                          </span>
                        @else
                          <span class="badge bg-slate-100 text-muted px-2 py-1 rounded-pill">0</span>
                        @endif
                      </td>
                      <td>
                        <div class="d-flex align-items-center gap-2">
                          <div class="progress-pill">
                            <div class="progress-fill {{ $tone }}" style="width: {{ $pct }}%"></div>
                          </div>
                          <span class="small fw-bold {{ $pct >= 80 ? 'text-success' : ($pct >= 50 ? 'text-warning' : 'text-danger') }}" style="min-width: 38px;">
                            {{ $pct }}%
                          </span>
                        </div>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </section>
      @endif

      <!-- Section: Demographics -->
      <section class="section-wrap">
        <div class="section-header">
          <div>
            <span class="section-badge bg-primary-subtle text-primary">
              <x-heroicon-o-user-group class="heroicon-sm" />
              Sebaran Populasi
            </span>
            <h2 class="section-title">Profil Demografi &amp; Kategori Tarif IPP</h2>
            <p class="section-desc">Distribusi jenis kelamin, kepercayaan/agama, serta skema kategori iuran pendidikan siswa.</p>
          </div>
        </div>

        <div class="grid-4-col">
          @php
            $demoCards = [
              ['title' => 'Jenis Kelamin', 'icon' => 'o-user', 'data' => $distJk ?? [], 'color' => 'primary'],
              ['title' => 'Agama / Keyakinan', 'icon' => 'o-book-open', 'data' => $distAgama ?? [], 'color' => 'success'],
              ['title' => 'Kategori Siswa', 'icon' => 'o-tag', 'data' => $distKategori ?? [], 'color' => 'warning'],
              ['title' => 'Kategori Tarif IPP', 'icon' => 'o-banknotes', 'data' => $distIpp ?? [], 'color' => 'danger'],
            ];
          @endphp

          @foreach($demoCards as $card)
            <div class="card-modern">
              <div class="card-modern-header">
                <h3 class="card-modern-title">
                  @if($card['icon'] === 'o-user')
                    <x-heroicon-o-user class="heroicon-sm text-primary" />
                  @elseif($card['icon'] === 'o-book-open')
                    <x-heroicon-o-book-open class="heroicon-sm text-success" />
                  @elseif($card['icon'] === 'o-tag')
                    <x-heroicon-o-tag class="heroicon-sm text-warning" />
                  @elseif($card['icon'] === 'o-banknotes')
                    <x-heroicon-o-banknotes class="heroicon-sm text-danger" />
                  @endif
                  <span>{{ $card['title'] }}</span>
                </h3>
              </div>
              <div class="card-modern-body">
                @forelse($card['data'] as $label => $count)
                  @php $pct = round(($count / $total) * 100); @endphp
                  <div class="tally-item">
                    <div class="tally-meta">
                      <span class="tally-label" title="{{ $label }}">{{ $label }}</span>
                      <span class="tally-val">{{ $count }} <small>({{ $pct }}%)</small></span>
                    </div>
                    <div class="progress-pill">
                      <div class="progress-fill" style="width: {{ $pct }}%"></div>
                    </div>
                  </div>
                @empty
                  <div class="text-center py-3 text-muted small">Belum ada data tersedia</div>
                @endforelse
              </div>
            </div>
          @endforeach
        </div>
      </section>

      <!-- Section: Socio-Economics (Pekerjaan & Penghasilan Orang Tua) -->
      <section class="section-wrap">
        <div class="section-header">
          <div>
            <span class="section-badge bg-secondary-subtle text-secondary">
              <x-heroicon-o-briefcase class="heroicon-sm" />
              Karakteristik Keluarga
            </span>
            <h2 class="section-title">Kondisi Sosial Ekonomi Orang Tua / Wali</h2>
            <p class="section-desc">Distribusi pekerjaan dan kelompok rentang penghasilan ayah dan ibu peserta didik.</p>
          </div>
        </div>

        <div class="grid-2-col">
          <!-- Card Pekerjaan -->
          <div class="card-modern">
            <div class="card-modern-header">
              <h3 class="card-modern-title">
                <x-heroicon-o-briefcase class="heroicon text-primary" />
                <span>Pekerjaan Orang Tua</span>
              </h3>
            </div>
            <div class="card-modern-body">
              <div class="row g-4">
                <div class="col-sm-6 border-end-sm">
                  <div class="fw-bold text-success text-uppercase small mb-3 d-flex align-items-center gap-1">
                    <x-heroicon-o-user class="heroicon-sm" /> Pekerjaan Ayah
                  </div>
                  @forelse($distPekerjaanAyah ?? [] as $label => $count)
                    @php $pct = round(($count / $total) * 100); @endphp
                    <div class="tally-item">
                      <div class="tally-meta">
                        <span class="tally-label" title="{{ $label }}">{{ $label }}</span>
                        <span class="tally-val">{{ $count }} <small>({{ $pct }}%)</small></span>
                      </div>
                      <div class="progress-pill">
                        <div class="progress-fill" style="width: {{ $pct }}%"></div>
                      </div>
                    </div>
                  @empty
                    <div class="text-center py-2 text-muted small">Tidak ada data</div>
                  @endforelse
                </div>

                <div class="col-sm-6">
                  <div class="fw-bold text-danger text-uppercase small mb-3 d-flex align-items-center gap-1">
                    <x-heroicon-o-user-group class="heroicon-sm" /> Pekerjaan Ibu
                  </div>
                  @forelse($distPekerjaanIbu ?? [] as $label => $count)
                    @php $pct = round(($count / $total) * 100); @endphp
                    <div class="tally-item">
                      <div class="tally-meta">
                        <span class="tally-label" title="{{ $label }}">{{ $label }}</span>
                        <span class="tally-val">{{ $count }} <small>({{ $pct }}%)</small></span>
                      </div>
                      <div class="progress-pill">
                        <div class="progress-fill low" style="width: {{ $pct }}%"></div>
                      </div>
                    </div>
                  @empty
                    <div class="text-center py-2 text-muted small">Tidak ada data</div>
                  @endforelse
                </div>
              </div>
            </div>
          </div>

          <!-- Card Penghasilan -->
          <div class="card-modern">
            <div class="card-modern-header">
              <h3 class="card-modern-title">
                <x-heroicon-o-banknotes class="heroicon text-success" />
                <span>Penghasilan Orang Tua</span>
              </h3>
            </div>
            <div class="card-modern-body">
              <div class="row g-4">
                <div class="col-sm-6 border-end-sm">
                  <div class="fw-bold text-success text-uppercase small mb-3 d-flex align-items-center gap-1">
                    <x-heroicon-o-user class="heroicon-sm" /> Penghasilan Ayah
                  </div>
                  @forelse($distPenghasilanAyah ?? [] as $label => $count)
                    @php $pct = round(($count / $total) * 100); @endphp
                    <div class="tally-item">
                      <div class="tally-meta">
                        <span class="tally-label" title="{{ $label }}">{{ $label }}</span>
                        <span class="tally-val">{{ $count }} <small>({{ $pct }}%)</small></span>
                      </div>
                      <div class="progress-pill">
                        <div class="progress-fill" style="width: {{ $pct }}%"></div>
                      </div>
                    </div>
                  @empty
                    <div class="text-center py-2 text-muted small">Tidak ada data</div>
                  @endforelse
                </div>

                <div class="col-sm-6">
                  <div class="fw-bold text-warning text-uppercase small mb-3 d-flex align-items-center gap-1">
                    <x-heroicon-o-user-group class="heroicon-sm" /> Penghasilan Ibu
                  </div>
                  @forelse($distPenghasilanIbu ?? [] as $label => $count)
                    @php $pct = round(($count / $total) * 100); @endphp
                    <div class="tally-item">
                      <div class="tally-meta">
                        <span class="tally-label" title="{{ $label }}">{{ $label }}</span>
                        <span class="tally-val">{{ $count }} <small>({{ $pct }}%)</small></span>
                      </div>
                      <div class="progress-pill">
                        <div class="progress-fill mid" style="width: {{ $pct }}%"></div>
                      </div>
                    </div>
                  @empty
                    <div class="text-center py-2 text-muted small">Tidak ada data</div>
                  @endforelse
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Section: Tanggungan Bersekolah -->
      <section class="section-wrap">
        <div class="section-header">
          <div>
            <span class="section-badge bg-primary-subtle text-primary">
              <x-heroicon-o-academic-cap class="heroicon-sm" />
              Beban Tanggungan
            </span>
            <h2 class="section-title">Beban Tanggungan Anak Bersekolah</h2>
            <p class="section-desc">Perbandingan jumlah anak bersekolah yang ditanggung oleh orang tua per siswa dan per kepala keluarga.</p>
          </div>
        </div>

        <div class="grid-2-col">
          <div class="card-modern">
            <div class="card-modern-header">
              <h3 class="card-modern-title">
                <x-heroicon-o-user class="heroicon text-primary" />
                <span>Beban Tanggungan per Siswa</span>
              </h3>
            </div>
            <div class="card-modern-body">
              <p class="text-muted small mb-3">Distribusi berdasarkan isian formulir profil masing-masing peserta didik.</p>
              @php if (!empty($distTanggunganSiswa)) { ksort($distTanggunganSiswa); } @endphp
              @forelse($distTanggunganSiswa ?? [] as $label => $count)
                @php $pct = round(($count / $total) * 100); @endphp
                <div class="tally-item">
                  <div class="tally-meta">
                    <span class="tally-label">{{ $label !== '' ? $label : '0' }} Tanggungan Anak</span>
                    <span class="tally-val">{{ $count }} Siswa <small>({{ $pct }}%)</small></span>
                  </div>
                  <div class="progress-pill">
                    <div class="progress-fill" style="width: {{ $pct }}%"></div>
                  </div>
                </div>
              @empty
                <div class="text-center py-3 text-muted small">Tidak ada data</div>
              @endforelse
            </div>
          </div>

          <div class="card-modern">
            <div class="card-modern-header">
              <h3 class="card-modern-title">
                <x-heroicon-o-home-modern class="heroicon text-success" />
                <span>Beban Tanggungan per Kepala Keluarga</span>
              </h3>
            </div>
            <div class="card-modern-body">
              <p class="text-muted small mb-3">Dikelompokkan berdasarkan data nama orang tua / wali yang sama (KK identik).</p>
              @php
                $totalFamilies = array_sum($distTanggunganKeluarga ?? []);
                $tf = max(1, $totalFamilies);
              @endphp
              @forelse($distTanggunganKeluarga ?? [] as $label => $count)
                @php $pct = round(($count / $tf) * 100); @endphp
                <div class="tally-item">
                  <div class="tally-meta">
                    <span class="tally-label">{{ $label !== '' ? $label : '0' }} Tanggungan Anak</span>
                    <span class="tally-val">{{ $count }} Keluarga <small>({{ $pct }}%)</small></span>
                  </div>
                  <div class="progress-pill">
                    <div class="progress-fill mid" style="width: {{ $pct }}%"></div>
                  </div>
                </div>
              @empty
                <div class="text-center py-3 text-muted small">Tidak ada data</div>
              @endforelse
            </div>
          </div>
        </div>
      </section>

      <!-- Section: Rincian Keluarga (Interaktif dengan Pencarian) -->
      <section class="section-wrap mb-0">
        <div class="section-header">
          <div>
            <span class="section-badge bg-success-subtle text-success">
              <x-heroicon-o-magnifying-glass class="heroicon-sm" />
              Direktori Transparansi
            </span>
            <h2 class="section-title">Rincian Data Keluarga &amp; Siswa</h2>
            <p class="section-desc">Daftar kepala keluarga dan peserta didik yang berada dalam satu tanggungan keluarga.</p>
          </div>
          <div class="d-flex align-items-center gap-2 flex-wrap">
            <div class="search-input-wrap">
              <x-heroicon-o-magnifying-glass class="heroicon-sm search-icon" />
              <input type="search" id="familySearchInput" class="search-input-modern" placeholder="Cari nama orang tua atau siswa..." autocomplete="off">
            </div>
            <span class="badge-count">
              <x-heroicon-o-user-group class="heroicon-sm text-primary" />
              <span id="familyCountBadge">{{ count($families) }}</span> Keluarga
            </span>
          </div>
        </div>

        <div class="card-modern">
          <div style="max-height: 520px; overflow-y: auto;">
            <table class="table-modern" id="familyTable">
              <thead style="position: sticky; top: 0; z-index: 10;">
                <tr>
                  <th style="min-width: 240px;">Kepala Keluarga</th>
                  <th class="text-center" style="width: 140px;">Jumlah Tanggungan</th>
                  <th>Daftar Siswa Terdaftar</th>
                </tr>
              </thead>
              <tbody>
                @forelse($families as $f)
                  <tr class="family-row-item">
                    <td>
                      <div class="d-flex align-items-center gap-2">
                        <div class="avatar-initial">{{ strtoupper(substr($f['kepala_keluarga'], 0, 1)) }}</div>
                        <strong class="text-slate-900">{{ $f['kepala_keluarga'] }}</strong>
                      </div>
                    </td>
                    <td class="text-center">
                      <span class="badge bg-slate-100 text-slate-700 px-2 py-1 rounded-pill fw-bold">
                        {{ $f['tanggungan'] }} Anak
                      </span>
                    </td>
                    <td>
                      @foreach($f['siswa_list'] as $sname)
                        <span class="student-pill">
                          <x-heroicon-o-academic-cap class="heroicon-sm text-primary" />
                          <span>{{ $sname }}</span>
                        </span>
                      @endforeach
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="3" class="text-center py-4 text-muted">
                      Belum ada data keluarga yang tercatat pada periode ini.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </section>

    </div>
  </main>

  <!-- Site Footer -->
  <footer class="site-footer">
    <div class="container-custom footer-inner">
      <div class="d-flex align-items-center gap-2">
        <img src="{{ asset('assets/dist/img/' . $schoolLogo) }}" alt="Logo {{ $schoolName }}" style="width: 26px; height: 26px; object-fit: contain;">
        <div>
          <div class="fw-bold text-slate-800">{{ $schoolName }}</div>
          <div class="small text-muted">{!! $copyright !!}</div>
        </div>
      </div>
      <div class="text-end small text-muted">
        <div>Portal Transparansi Iuran Pendidikan &amp; Subsidi Siswa &bull; <strong>{{ $appName }}</strong></div>
        <div>Waktu Sistem: {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</div>
      </div>
    </div>
  </footer>

  <!-- Live Client-side Filter Script -->
  <script>
    document.getElementById('familySearchInput')?.addEventListener('input', function() {
      const query = this.value.toLowerCase().trim();
      const rows = document.querySelectorAll('#familyTable tbody tr.family-row-item');
      let visibleCount = 0;

      rows.forEach(function(row) {
        const text = row.innerText.toLowerCase();
        const match = text.includes(query);
        row.style.display = match ? '' : 'none';
        if (match) visibleCount++;
      });

      const badge = document.getElementById('familyCountBadge');
      if (badge) {
        badge.textContent = visibleCount;
      }
    });
  </script>
</body>
</html>
