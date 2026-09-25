@extends('layouts.app')

@section('title', 'Manajemen Pengguna')
@section('page_title', 'Manajemen Pengguna Sistem')
@section('page_subtitle', 'Pengaturan akun Administrator, hak akses Wali Kelas, dan reset kata sandi')

@section('page_actions')
  <button type="button" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
    <x-heroicon-o-user-plus class="heroicon-sm" /> Tambah Pengguna
  </button>
@endsection

@section('content')
<div class="card">
  <div class="card-header bg-white">
    <form method="GET" action="{{ route('account.index') }}" class="row g-2 align-items-center w-100">
      <div class="col-md-3">
        <select name="level" class="form-select form-select-sm" onchange="this.form.submit()">
          <option value="">-- Semua Role --</option>
          <option value="admin" {{ request('level') === 'admin' ? 'selected' : '' }}>Administrator</option>
          <option value="wali" {{ request('level') === 'wali' ? 'selected' : '' }}>Wali Kelas</option>
        </select>
      </div>
      <div class="col-md-5">
        <div class="input-group input-group-sm">
          <input type="text" name="search" class="form-control" placeholder="Cari nama, username, email..." value="{{ request('search') }}">
          <button class="btn btn-primary d-inline-flex align-items-center gap-1" type="submit">
            <x-heroicon-o-magnifying-glass class="heroicon-sm" /> Cari
          </button>
        </div>
      </div>
    </form>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr>
            <th style="width: 50px;">No</th>
            <th>Pengguna</th>
            <th>Nama Lengkap</th>
            <th>Role</th>
            <th>Kelas Binaan</th>
            <th>Status</th>
            <th>Login Terakhir</th>
            <th class="text-end" style="width: 140px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($users as $idx => $u)
            <tr>
              <td>{{ $users->firstItem() + $idx }}</td>
              <td>
                <div class="fw-bold">{{ $u->username }}</div>
                <div class="text-muted small">{{ $u->email ?: '-' }}</div>
              </td>
              <td>{{ $u->nama }}</td>
              <td>
                <span class="badge {{ $u->isAdmin() ? 'bg-primary' : 'bg-info' }}">
                  {{ $u->role_name }}
                </span>
              </td>
              <td>
                @if($u->kelas)
                  <span class="badge bg-secondary-subtle text-secondary">{{ $u->kelas->nama_kelas }}</span>
                @else
                  <span class="text-muted small">-</span>
                @endif
              </td>
              <td>
                <span class="badge {{ $u->is_active ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle' }}">
                  {{ $u->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
              </td>
              <td class="small text-muted">
                {{ $u->last_login ? \Carbon\Carbon::parse($u->last_login)->diffForHumans() : 'Belum pernah' }}
              </td>
              <td class="text-end">
                <div class="btn-group btn-group-sm">
                  <!-- Edit User -->
                  <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEditUser{{ $u->id }}" title="Edit Data Pengguna">
                    <x-heroicon-o-pencil-square class="heroicon-sm" />
                  </button>

                  <!-- Reset Password Modal Trigger -->
                  <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalResetUser{{ $u->id }}" title="Reset Kata Sandi">
                    <x-heroicon-o-key class="heroicon-sm" />
                  </button>

                  @if($u->id !== Auth::id())
                    <!-- Toggle Status Active / Inactive -->
                    <form method="POST" action="{{ route('account.toggle_status', $u->id) }}" class="d-inline">
                      @csrf
                      <button type="submit" class="btn btn-outline-{{ $u->is_active ? 'secondary' : 'success' }}" title="{{ $u->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}" data-confirm-title="{{ $u->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}" data-confirm="Apakah Anda yakin ingin {{ $u->is_active ? 'menonaktifkan' : 'mengaktifkan kembali' }} akun {{ $u->nama }}?" data-confirm-icon="question" data-confirm-btn="Ya, Lanjutkan" data-confirm-btn-color="{{ $u->is_active ? '#64748b' : '#16a34a' }}">
                        <x-heroicon-o-power class="heroicon-sm" />
                      </button>
                    </form>

                    <!-- Hapus User -->
                    <form method="POST" action="{{ route('account.destroy', $u->id) }}" class="d-inline">
                      @csrf @method('DELETE')
                      <button type="button" class="btn btn-outline-danger btn-delete-confirm" title="Hapus Akun">
                        <x-heroicon-o-trash class="heroicon-sm" />
                      </button>
                    </form>
                  @endif
                </div>

                <!-- Modal Reset Password -->
                <div class="modal fade text-start" id="modalResetUser{{ $u->id }}" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <form method="POST" action="{{ route('account.reset_password', $u->id) }}">
                      @csrf
                      <div class="modal-content rounded-3 shadow border-0">
                        <div class="modal-header border-bottom">
                          <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                            <x-heroicon-o-key class="heroicon text-warning" /> Reset Kata Sandi
                          </h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4">
                          <div class="alert alert-warning d-flex align-items-start gap-2 mb-3 small">
                            <x-heroicon-o-exclamation-triangle class="heroicon text-warning flex-shrink-0 mt-0.5" />
                            <div>
                              Anda akan mereset kata sandi akun: <br>
                              <strong>{{ $u->nama }}</strong> (<span class="text-primary">{{ $u->username }}</span>).
                            </div>
                          </div>

                          <div class="mb-3" x-data="{ show: false }">
                            <label class="form-label small fw-semibold">Kata Sandi Baru</label>
                            <div class="input-group">
                              <input :type="show ? 'text' : 'password'" name="password" class="form-control font-monospace" value="123456" required>
                              <button class="btn btn-outline-secondary" type="button" @click="show = !show" title="Tampilkan/Sembunyikan">
                                <span x-show="!show"><x-heroicon-o-eye class="heroicon-sm" /></span>
                                <span x-show="show" x-cloak><x-heroicon-o-eye-slash class="heroicon-sm" /></span>
                              </button>
                            </div>
                            <div class="form-text small text-muted">
                              Kata sandi bawaan diisi <code>123456</code>. Anda dapat langsung mengklik tombol Reset atau menggantinya dengan kata sandi lain.
                            </div>
                          </div>
                        </div>
                        <div class="modal-footer bg-light border-top">
                          <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                          <button type="submit" class="btn btn-warning btn-sm px-3 fw-semibold">
                            <x-heroicon-o-key class="heroicon-sm me-1" /> Reset Kata Sandi
                          </button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>

                <!-- Modal Edit User -->
                <div class="modal fade text-start" id="modalEditUser{{ $u->id }}" tabindex="-1">
                  <div class="modal-dialog">
                    <form method="POST" action="{{ route('account.update', $u->id) }}">
                      @csrf @method('PUT')
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title fw-bold">Edit Akun - {{ $u->username }}</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                          <div class="mb-3">
                            <label class="form-label small fw-semibold">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="{{ $u->nama }}" required>
                          </div>
                          <div class="mb-3">
                            <label class="form-label small fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $u->email }}">
                          </div>
                          <div class="mb-3">
                            <label class="form-label small fw-semibold">Role Hak Akses</label>
                            <select name="level" class="form-select" required>
                              <option value="admin" {{ $u->level === 'admin' ? 'selected' : '' }}>Administrator</option>
                              <option value="wali" {{ $u->level === 'wali' ? 'selected' : '' }}>Wali Kelas</option>
                            </select>
                          </div>
                          <div class="mb-3">
                            <label class="form-label small fw-semibold">Kelas Binaan (Khusus Wali Kelas)</label>
                            <select name="kelas_id" class="form-select">
                              <option value="">-- Pilih Kelas --</option>
                              @foreach($kelasList as $k)
                                <option value="{{ $k->id }}" {{ $u->kelas_id == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="mb-3">
                            <label class="form-label small fw-semibold">Tautkan Data GTK</label>
                            <select name="gtk_id" class="form-select">
                              <option value="">-- Pilih Guru/GTK (Opsional) --</option>
                              @foreach($gtkList as $g)
                                <option value="{{ $g->id }}" {{ $u->gtk_id == $g->id ? 'selected' : '' }}>{{ $g->nama }} ({{ $g->nip ?: 'Non-NIP' }})</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="mb-3">
                            <label class="form-label small fw-semibold">Status Akun</label>
                            <select name="is_active" class="form-select" required>
                              <option value="1" {{ $u->is_active ? 'selected' : '' }}>Aktif</option>
                              <option value="0" {{ !$u->is_active ? 'selected' : '' }}>Nonaktifkan</option>
                            </select>
                          </div>
                          <div class="mb-3" x-data="{ showPassEdit: false }">
                            <label class="form-label small fw-semibold">Ubah Password (Kosongkan jika tidak diganti)</label>
                            <div class="input-group">
                              <input :type="showPassEdit ? 'text' : 'password'" name="password" class="form-control" placeholder="••••••••">
                              <button type="button" class="btn btn-outline-secondary" @click="showPassEdit = !showPassEdit" tabindex="-1" title="Lihat/Sembunyikan Sandi">
                                <span x-show="!showPassEdit"><x-heroicon-o-eye class="heroicon-sm" /></span>
                                <span x-show="showPassEdit" x-cloak><x-heroicon-o-eye-slash class="heroicon-sm" /></span>
                              </button>
                            </div>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </td>
            </tr>
          @empty
            <tr><td colspan="8" class="text-center text-muted py-5">Belum ada akun terdaftar</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if($users->hasPages())
    <div class="card-footer bg-white border-top d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 py-3">
      <div class="small text-muted text-center text-sm-start">Menampilkan <strong>{{ $users->firstItem() }}</strong> s/d <strong>{{ $users->lastItem() }}</strong> dari <strong>{{ $users->total() }}</strong> akun</div>
      <div>{{ $users->links('pagination::bootstrap-5') }}</div>
    </div>
  @endif
</div>

<!-- Modal Tambah User -->
<div class="modal fade" id="modalTambahUser" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('account.store') }}">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fw-bold">Tambah Akun Pengguna</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label small fw-semibold">Username <span class="text-danger">*</span></label>
            <input type="text" name="username" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" name="nama" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Email</label>
            <input type="email" name="email" class="form-control">
          </div>
          <div class="mb-3" x-data="{ showPassCreate: false }">
            <label class="form-label small fw-semibold">Password <span class="text-danger">*</span></label>
            <div class="input-group">
              <input :type="showPassCreate ? 'text' : 'password'" name="password" class="form-control" required placeholder="Minimal 4 karakter">
              <button type="button" class="btn btn-outline-secondary" @click="showPassCreate = !showPassCreate" tabindex="-1" title="Lihat/Sembunyikan Sandi">
                <span x-show="!showPassCreate"><x-heroicon-o-eye class="heroicon-sm" /></span>
                <span x-show="showPassCreate" x-cloak><x-heroicon-o-eye-slash class="heroicon-sm" /></span>
              </button>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Role Hak Akses <span class="text-danger">*</span></label>
            <select name="level" class="form-select" required>
              <option value="admin">Administrator</option>
              <option value="wali" selected>Wali Kelas</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Kelas Binaan (Untuk Wali Kelas)</label>
            <select name="kelas_id" class="form-select">
              <option value="">-- Pilih Kelas --</option>
              @foreach($kelasList as $k)
                <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Tautkan Data GTK</label>
            <select name="gtk_id" class="form-select">
              <option value="">-- Pilih Guru/GTK (Opsional) --</option>
              @foreach($gtkList as $g)
                <option value="{{ $g->id }}">{{ $g->nama }} ({{ $g->nip ?: 'Non-NIP' }})</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Buat Akun</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection