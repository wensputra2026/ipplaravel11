<footer class="app-footer">
  <div class="footer-content d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
    <div class="text-secondary small">
      &copy; {{ date('Y') }} <span class="fw-semibold text-dark">{{ $appName }}</span> &bull; {{ $schoolName }}. Hak Cipta Dilindungi.
    </div>
    <div class="d-flex align-items-center gap-2 small">
      <span class="badge bg-light text-secondary border px-2 py-1 rounded-pill fw-medium">v1.0</span>
      <span class="text-muted d-none d-sm-inline">&bull;</span>
      <span class="text-muted">Periode: <strong>{{ $activeTa }}</strong> ({{ $activeSem }})</span>
    </div>
  </div>
</footer>
