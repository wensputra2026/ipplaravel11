<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Gtk;
use App\Models\User;
use App\Models\Walikelas;
use App\Models\AppSetting;
use App\Models\ActivityLog;

use App\Models\MasterTahunAjaran;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user    = Auth::user();
        $isWali  = $user->isWali();
        $kelasId = $isWali ? ($user->kelas_id ?? null) : null;

        $activeTa  = AppSetting::get('active_tahun_ajaran', '2026/2027');
        $activeSem = AppSetting::get('active_semester', 'Ganjil');

        // Periode yang sedang ditampilkan: default ke periode aktif/berjalan, atau sesuai filter user
        $selectedTa  = $request->get('ta', $activeTa);
        $selectedSem = $request->get('semester', $activeSem);

        // Daftar tahun ajaran yang tersedia untuk dipilih
        $availableTaList = MasterTahunAjaran::orderBy('tahun_ajaran', 'desc')->pluck('tahun_ajaran')->toArray();
        $siswaTaList     = Siswa::distinct()->whereNotNull('tahun_ajaran')->where('tahun_ajaran', '!=', '')->pluck('tahun_ajaran')->toArray();
        $availableTaList = array_values(array_unique(array_merge($availableTaList, $siswaTaList, [$activeTa])));
        rsort($availableTaList);

        $availableSemList = ['Ganjil', 'Genap'];

        /* ----------------------------------------------------------------
         | BASE SISWA QUERY  (filtered by selected TA, semester, and class for Wali)
         * ---------------------------------------------------------------- */
        $siswaQuery = Siswa::whereHas('rombels', function($q) use ($selectedTa, $selectedSem, $isWali, $kelasId) {
            $q->where('tahun_ajaran', $selectedTa);
            if ($selectedSem) {
                $q->where('semester', $selectedSem);
            }
            $q->where('status', 'Aktif');
            if ($isWali && $kelasId) {
                $q->where('kelas_id', $kelasId);
            }
        });

        $totalSiswa = (clone $siswaQuery)->count();
        if ($totalSiswa === 0) {
            // Fallback to direct siswa query if no rombels recorded
            $fallbackQuery = Siswa::aktif()->where('tahun_ajaran', $selectedTa)->where('semester', $selectedSem);
            if ($isWali && $kelasId) $fallbackQuery->where('kelas_id', $kelasId);
            $allSiswa = $fallbackQuery->get()->toArray();
            $totalSiswa = count($allSiswa);
        } else {
            $allSiswa = (clone $siswaQuery)->get()->toArray();
        }

        /* ----------------------------------------------------------------
         | METRIC COUNTS
         * ---------------------------------------------------------------- */
        $totalAlumni = Siswa::whereHas('rombels', function($q) use ($selectedTa, $selectedSem) {
            $q->where('tahun_ajaran', $selectedTa);
            if ($selectedSem) {
                $q->where('semester', $selectedSem);
            }
            $q->where('status', 'Lulus');
        })->count();

        if ($totalAlumni === 0) {
            $totalAlumni = Siswa::lulus()
                ->where('tahun_ajaran', $selectedTa)
                ->where('semester', $selectedSem)
                ->count();
        }

        // Rekap jumlah siswa AKTIF per tingkat (X, XI, XII) untuk ditampilkan di kartu
        $rekapByTingkat = [];
        $tingkatList = ['X', 'XI', 'XII'];
        foreach ($tingkatList as $tingkat) {
            $count = Siswa::whereHas('rombels', function($q) use ($selectedTa, $selectedSem, $tingkat, $isWali, $kelasId) {
                $q->where('tahun_ajaran', $selectedTa)
                  ->where('semester', $selectedSem)
                  ->where('status', 'Aktif')
                  ->whereHas('kelas', fn($kq) => $kq->where('tingkat', $tingkat));
                if ($isWali && $kelasId) {
                    $q->where('kelas_id', $kelasId);
                }
            })->count();
            if ($count > 0) {
                $rekapByTingkat[$tingkat] = $count;
            }
        }

        // Rekap lulus per tingkat (biasanya hanya kelas XII di semester Genap)
        $rekapLulusByTingkat = [];
        foreach ($tingkatList as $tingkat) {
            $count = Siswa::whereHas('rombels', function($q) use ($selectedTa, $selectedSem, $tingkat) {
                $q->where('tahun_ajaran', $selectedTa)
                  ->where('semester', $selectedSem)
                  ->where('status', 'Lulus')
                  ->whereHas('kelas', fn($kq) => $kq->where('tingkat', $tingkat));
            })->count();
            if ($count > 0) {
                $rekapLulusByTingkat[$tingkat] = $count;
            }
        }

        // Wali only sees their own class; Admin sees all
        if ($isWali && $kelasId) {
            $totalKelas = 1;  // hanya kelas sendiri
            $totalGtk   = Gtk::count(); // GTK tetap semua (info sekolah)
        } else {
            $totalKelas = Kelas::count();
            $totalGtk   = Gtk::count();
        }
        $totalUsers = User::count();

        /* ----------------------------------------------------------------
         | HELPER TALLIES
         * ---------------------------------------------------------------- */
        $tally = function (array $list, string $key) {
            $counts = [];
            foreach ($list as $row) {
                $val = isset($row[$key]) ? trim((string)$row[$key]) : '';
                if ($val === '') continue;
                $counts[$val] = ($counts[$val] ?? 0) + 1;
            }
            arsort($counts);
            return $counts;
        };

        /* ----------------------------------------------------------------
         | KATEGORISASI KHUSUS  (filtered per siswa scope)
         * ---------------------------------------------------------------- */
        $catCounts = function (array $list) {
            $counts = [
                'panti_asuhan'     => 0,
                'korban_bencana'   => 0,
                'terlantar'        => 0,
                'ortu_abk'        => 0,
                'ortu_sakit_menahun' => 0,
            ];
            foreach ($list as $row) {
                $val = strtolower(trim((string)($row['kategori_siswa'] ?? '')));
                if ($val === '') continue;
                if ($val === 'a' || str_contains($val, 'panti'))                      { $counts['panti_asuhan']++;        continue; }
                if ($val === 'b' || str_contains($val, 'bencana'))                    { $counts['korban_bencana']++;      continue; }
                if ($val === 'c' || str_contains($val, 'terlantar'))                  { $counts['terlantar']++;           continue; }
                if ($val === 'd' || str_contains($val, 'abk') || str_contains($val, 'kebutuhan khusus')) { $counts['ortu_abk']++; continue; }
                if ($val === 'e' || str_contains($val, 'sakit'))                      { $counts['ortu_sakit_menahun']++;  continue; }
            }
            return $counts;
        };

        $stats = [
            'jk'               => $tally($allSiswa, 'jk'),
            'agama'            => $tally($allSiswa, 'agama'),
            'kategori'         => $tally($allSiswa, 'kategori_siswa'),
            'kategori_ipp'     => $tally($allSiswa, 'kategori_ipp'),
            'kategori_detail'  => $catCounts($allSiswa),
            'pekerjaan_ayah'   => $tally($allSiswa, 'pekerjaan_ayah'),
            'pekerjaan_ibu'    => $tally($allSiswa, 'pekerjaan_ibu'),
            'penghasilan_ayah' => $tally($allSiswa, 'penghasilan_ayah'),
            'penghasilan_ibu'  => $tally($allSiswa, 'penghasilan_ibu'),
        ];

        /* ----------------------------------------------------------------
         | REKAP KELAS  (Wali Kelas only sees their own class)
         * ---------------------------------------------------------------- */
        $waliList = Walikelas::with('gtk')
            ->where('tahun_ajaran', $selectedTa)
            ->where('semester', $selectedSem)
            ->get();

        $waliByKelas = [];
        foreach ($waliList as $w) {
            if ($w->id_kelas && $w->gtk) {
                $waliByKelas[$w->id_kelas] = $w->gtk->nama ?? $w->gtk->nama_lengkap;
            }
        }

        $rekapKelasQuery = Kelas::withCount(['rombels as siswa_count' => fn($q) => $q->where('tahun_ajaran', $selectedTa)
                ->where('semester', $selectedSem)
                ->where('status', 'Aktif')])
            ->orderBy('tingkat')
            ->orderBy('nama_kelas');

        if ($isWali && $kelasId) {
            $rekapKelasQuery->where('id', $kelasId);
        }
        $rekapKelas = $rekapKelasQuery->get();

        /* ----------------------------------------------------------------
         | TANGGUNGAN PER KELUARGA  (filtered per class for Wali)
         * ---------------------------------------------------------------- */
        $subQuery = DB::table('siswa')
            ->where('status', 'Aktif')
            ->where('tahun_ajaran', $selectedTa)
            ->where('semester', $selectedSem)
            ->selectRaw("id, nama_siswa, kelas_id, COALESCE(NULLIF(nama_ayah, ''), NULLIF(nama_wali_l, ''), NULLIF(nama_ibu, ''), 'Tanpa Nama') as kepala_keluarga");

        if ($isWali && $kelasId) {
            $subQuery->where('kelas_id', $kelasId);
        }

        $tanggunganList = DB::table(DB::raw("({$subQuery->toSql()}) as sub"))
            ->mergeBindings($subQuery)
            ->selectRaw("kepala_keluarga, COUNT(id) as jml_tanggungan, GROUP_CONCAT(nama_siswa SEPARATOR ', ') as anak_sekolah")
            ->groupBy('kepala_keluarga')
            ->orderByDesc('jml_tanggungan')
            ->orderBy('kepala_keluarga')
            ->take(10)
            ->get();

        /* ----------------------------------------------------------------
         | RECENT ACTIVITIES  (Wali Kelas: only own user's activity)
         * ---------------------------------------------------------------- */
        $activityQuery = ActivityLog::orderBy('created_at', 'desc');
        if ($isWali) {
            $activityQuery->where('username', $user->username);
        }
        $recentActivities = $activityQuery->take(10)->get();

        return view('dashboard', compact(
            'totalSiswa',
            'totalGtk',
            'totalKelas',
            'totalUsers',
            'totalAlumni',
            'rekapByTingkat',
            'rekapLulusByTingkat',
            'stats',
            'rekapKelas',
            'waliByKelas',
            'tanggunganList',
            'recentActivities',
            'activeTa',
            'activeSem',
            'selectedTa',
            'selectedSem',
            'availableTaList',
            'availableSemList',
            'isWali',
            'kelasId'
        ));
    }
}