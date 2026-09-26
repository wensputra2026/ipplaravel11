<!-- Modal Import Data Siswa dari Excel -->
<div class="modal fade" id="importExcelModal" tabindex="-1" aria-labelledby="importExcelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">
      <div class="modal-header bg-success text-white py-3 px-4">
        <div class="d-flex align-items-center gap-2">
          <x-heroicon-o-arrow-up-tray class="heroicon text-white" />
          <h5 class="modal-title fw-bold mb-0" id="importExcelModalLabel">Import Data Siswa dari Excel</h5>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data" id="formImportExcel">
        @csrf
        <div class="modal-body p-4">
          <!-- Petunjuk dan Download Template -->
          <div class="p-3 mb-4 rounded-3 border" style="background-color: #f8fafc;">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-2">
              <div class="fw-bold text-dark d-flex align-items-center gap-1.5">
                <x-heroicon-o-information-circle class="heroicon-sm text-success" />
                Format Template Excel
              </div>
              <a href="{{ route('siswa.template') }}" class="btn btn-outline-success btn-sm d-inline-flex align-items-center gap-1 shadow-xs bg-white">
                <x-heroicon-o-arrow-down-tray class="heroicon-sm" /> Download Template Excel
              </a>
            </div>
            <p class="small text-muted mb-2">
              Gunakan format template resmi agar data dapat dipetakan secara akurat ke dalam basis data:
            </p>
            <div class="row g-2 small">
              <div class="col-md-4">
                <div class="p-2 rounded border bg-white">
                  <span class="badge bg-secondary-subtle text-secondary me-1">Kolom A - N</span>
                  <div class="fw-semibold text-dark mt-1">Data Siswa</div>
                  <div class="text-muted" style="font-size: 0.75rem;">Kelas, Nama, JK, Agama, TTL, Alamat, RT/RW, Dusun, Kelurahan, Kategori.</div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="p-2 rounded border bg-white">
                  <span class="badge bg-warning-subtle text-dark me-1">Kolom O - W</span>
                  <div class="fw-semibold text-dark mt-1">Data Orang Tua Kandung</div>
                  <div class="text-muted" style="font-size: 0.75rem;">Nama Ayah, Pekerjaan, Penghasilan, Alamat; Nama Ibu, Pekerjaan, Penghasilan, Tanggungan.</div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="p-2 rounded border bg-white">
                  <span class="badge bg-info-subtle text-info me-1">Kolom X - AF</span>
                  <div class="fw-semibold text-dark mt-1">Data Wali</div>
                  <div class="text-muted" style="font-size: 0.75rem;">Nama Wali L/P, Pekerjaan, Penghasilan, Alamat Domisili, Tanggungan.</div>
                </div>
              </div>
            </div>
          </div>

          <!-- File Input -->
          <div class="mb-4">
            <label class="form-label fw-bold small text-dark mb-1">
              Pilih File Spreadsheet (.xlsx, .xls, .csv) <span class="text-danger">*</span>
            </label>
            <div class="input-group">
              <input type="file" name="excel_file" id="excel_file_input" class="form-control form-control-lg" accept=".xlsx,.xls,.csv" required onchange="handleExcelFileSelect(this)">
            </div>
            <div id="fileHelpText" class="form-text mt-1 text-muted small">
              Mendukung file Microsoft Excel (.xlsx, .xls) dan CSV (.csv). Ukuran maksimal file adalah 10 MB.
            </div>
          </div>

          <!-- Pilihan Metode Impor -->
          <div class="mb-3">
            <label class="form-label fw-bold small text-dark mb-2">Metode Penanganan Data Duplikat</label>
            <div class="row g-2">
              <div class="col-md-4">
                <label class="p-3 border rounded-3 w-100 cursor-pointer h-100 d-flex gap-2 align-items-start bg-light-subtle shadow-xs" style="cursor: pointer;">
                  <input type="radio" name="mode" value="merge" class="form-check-input mt-1" checked>
                  <div>
                    <div class="fw-bold text-dark small">Lengkapi yang kosong</div>
                    <div class="text-muted" style="font-size: 0.75rem;">
                      Hanya mengisi kolom kosong pada siswa yang cocok. Data baru otomatis ditambahkan.
                    </div>
                  </div>
                </label>
              </div>
              <div class="col-md-4">
                <label class="p-3 border rounded-3 w-100 cursor-pointer h-100 d-flex gap-2 align-items-start bg-light-subtle shadow-xs" style="cursor: pointer;">
                  <input type="radio" name="mode" value="skip" class="form-check-input mt-1">
                  <div>
                    <div class="fw-bold text-dark small">Lewati jika sudah ada</div>
                    <div class="text-muted" style="font-size: 0.75rem;">
                      Cegah duplikasi total. Hanya memasukkan siswa baru, data yang sudah ada di database tidak disentuh.
                    </div>
                  </div>
                </label>
              </div>
              <div class="col-md-4">
                <label class="p-3 border rounded-3 w-100 cursor-pointer h-100 d-flex gap-2 align-items-start bg-light-subtle shadow-xs" style="cursor: pointer;">
                  <input type="radio" name="mode" value="overwrite" class="form-check-input mt-1">
                  <div>
                    <div class="fw-bold text-dark small">Timpa data lama</div>
                    <div class="text-muted" style="font-size: 0.75rem;">
                      Memperbarui semua nilai siswa yang cocok dengan data file Excel terbaru.
                    </div>
                  </div>
                </label>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer bg-light px-4 py-3 border-top">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success d-inline-flex align-items-center gap-1.5 px-4 font-weight-bold" id="btnSubmitImport">
            <x-heroicon-o-arrow-up-tray class="heroicon-sm" /> Mulai Import Data
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  function handleExcelFileSelect(input) {
    const file = input.files[0];
    const help = document.getElementById('fileHelpText');
    if (file) {
      const sizeKB = (file.size / 1024).toFixed(1);
      const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
      const displaySize = file.size > 1024 * 1024 ? `${sizeMB} MB` : `${sizeKB} KB`;
      help.innerHTML = `<span class="text-success fw-semibold">✓ File terpilih: <strong>${file.name}</strong> (${displaySize})</span>`;
    }
  }

  document.getElementById('formImportExcel')?.addEventListener('submit', function() {
    const btn = document.getElementById('btnSubmitImport');
    if (btn) {
      btn.disabled = true;
      btn.innerHTML = `<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Memproses Import...`;
    }
  });
</script>
