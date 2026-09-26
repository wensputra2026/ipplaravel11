<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\WalikelasController;
use App\Http\Controllers\GtkController;
use App\Http\Controllers\KenaikanKelasController;
use App\Http\Controllers\SiswaPindahController;
use App\Http\Controllers\KelulusanController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [FrontendController::class, 'index'])->name('welcome');
Route::get('/welcome', [FrontendController::class, 'index']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login')->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Admin & Wali Kelas)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil Akun
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Siswa (Akses Admin & Wali Kelas)
    Route::get('/siswa/search-ajax', [SiswaController::class, 'searchAjax'])->middleware('throttle:search_ajax')->name('siswa.search_ajax');
    Route::get('/siswa/konversi', [SiswaController::class, 'konversi'])->name('siswa.konversi');
    Route::post('/siswa/konversi/proses', [SiswaController::class, 'processKonversi'])->name('siswa.process_konversi');
    Route::get('/siswa/template', [SiswaController::class, 'template'])->name('siswa.template');
    Route::post('/siswa/import', [SiswaController::class, 'import'])->name('siswa.import');
    Route::get('/siswa/export', [SiswaController::class, 'export'])->name('siswa.export');
    Route::resource('siswa', SiswaController::class);

    // Validasi & Progres (Akses Admin & Wali Kelas)
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    /*
    |--------------------------------------------------------------------------
    | Admin Only Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->group(function () {
        // Data Kelas
        Route::resource('kelas', KelasController::class)->only(['index', 'store', 'update', 'destroy']);

        // Penugasan Wali Kelas
        Route::resource('walikelas', WalikelasController::class)->only(['index', 'store', 'update', 'destroy']);

        // Data GTK
        Route::resource('gtk', GtkController::class);

        // Kenaikan Kelas
        Route::get('/kenaikankelas', [KenaikanKelasController::class, 'index'])->name('kenaikankelas.index');
        Route::post('/kenaikankelas/process', [KenaikanKelasController::class, 'process'])->name('kenaikankelas.process');

        // Pindah Kelas
        Route::get('/siswapindah', [SiswaPindahController::class, 'index'])->name('siswapindah.index');
        Route::post('/siswapindah/process', [SiswaPindahController::class, 'process'])->name('siswapindah.process');

        // Kelulusan & Alumni
        Route::get('/kelulusan', [KelulusanController::class, 'index'])->name('kelulusan.index');
        Route::post('/kelulusan/process', [KelulusanController::class, 'process'])->name('kelulusan.process');
        Route::post('/kelulusan/{id}/cancel', [KelulusanController::class, 'cancelGraduation'])->name('kelulusan.cancel');

        // Data Referensi / Master
        Route::get('/master', [MasterController::class, 'index'])->name('master.index');
        Route::post('/master/tahun-ajaran', [MasterController::class, 'storeTahunAjaran'])->name('master.tahun_ajaran.store');
        Route::post('/master/tahun-ajaran/{id}/set-active', [MasterController::class, 'setActiveTahunAjaran'])->name('master.tahun_ajaran.set_active');
        Route::delete('/master/tahun-ajaran/{id}', [MasterController::class, 'destroyTahunAjaran'])->name('master.tahun_ajaran.destroy');
        Route::post('/master/kategori', [MasterController::class, 'storeKategori'])->name('master.kategori.store');
        Route::delete('/master/kategori/{id}', [MasterController::class, 'destroyKategori'])->name('master.kategori.destroy');
        Route::post('/master/pekerjaan', [MasterController::class, 'storePekerjaan'])->name('master.pekerjaan.store');
        Route::delete('/master/pekerjaan/{id}', [MasterController::class, 'destroyPekerjaan'])->name('master.pekerjaan.destroy');
        Route::post('/master/penghasilan', [MasterController::class, 'storePenghasilan'])->name('master.penghasilan.store');
        Route::delete('/master/penghasilan/{id}', [MasterController::class, 'destroyPenghasilan'])->name('master.penghasilan.destroy');
        Route::post('/master/sumber-biaya', [MasterController::class, 'storeSumberBiaya'])->name('master.sumber_biaya.store');
        Route::delete('/master/sumber-biaya/{id}', [MasterController::class, 'destroySumberBiaya'])->name('master.sumber_biaya.destroy');

        // Manajemen Akun
        Route::post('/account/{id}/reset-password', [AccountController::class, 'resetPassword'])->name('account.reset_password');
        Route::post('/account/{id}/toggle-status', [AccountController::class, 'toggleStatus'])->name('account.toggle_status');
        Route::resource('account', AccountController::class)->only(['index', 'store', 'update', 'destroy']);

        // Recent Activity
        Route::get('/activity', [ActivityController::class, 'index'])->name('activity.index');

        // Pengaturan Sistem
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    });
});