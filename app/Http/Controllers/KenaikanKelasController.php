<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\MasterTahunAjaran;
use App\Models\AppSetting;
use App\Models\ActivityLog;

use App\Models\SiswaRombel;

class KenaikanKelasController extends Controller
{
    public function index(Request $request)
    {
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        $tahunList = MasterTahunAjaran::orderBy('tahun_ajaran', 'desc')->pluck('tahun_ajaran');

        $activeTa = AppSetting::get('active_tahun_ajaran', '2026/2027');
        $activeSem = AppSetting::get('active_semester', 'Ganjil');

        // Cari tahun ajaran sebelumnya untuk default asal kenaikan (misal: 2025/2026 Genap)
        $previousTa = null;
        foreach ($tahunList as $t) {
            if ($t < $activeTa) {
                $previousTa = $t;
                break;
            }
        }
        if (!$previousTa) {
            $previousTa = $activeTa;
        }

        $sourceTa = $request->get('source_tahun_ajaran', $previousTa);
        $sourceSem = $request->get('source_semester', 'Genap');
        $sourceKelasId = $request->get('source_kelas_id');
        $sourceKelas = $sourceKelasId ? Kelas::find($sourceKelasId) : null;

        $siswaList = collect();
        if ($sourceKelasId) {
            // Ambil siswa yang terdaftar di kelas & periode asal dari siswa_rombel
            $siswaList = Siswa::whereHas('rombels', function($q) use ($sourceKelasId, $sourceTa, $sourceSem) {
                $q->where('kelas_id', $sourceKelasId)
                  ->where('tahun_ajaran', $sourceTa)
                  ->where('semester', $sourceSem);
            })->orderBy('nama_siswa')->get();

            // Fallback jika belum ada data di siswa_rombel
            if ($siswaList->isEmpty()) {
                $siswaList = Siswa::aktif()->where('kelas_id', $sourceKelasId)->orderBy('nama_siswa')->get();
            }
        }

        return view('kenaikankelas.index', compact(
            'kelasList', 'tahunList', 'activeTa', 'activeSem',
            'sourceTa', 'sourceSem', 'sourceKelasId', 'sourceKelas', 'siswaList'
        ));
    }

    public function process(Request $request)
    {
        $request->validate([
            'source_kelas_id' => 'required|exists:kelas,id',
            'target_kelas_id' => 'required|exists:kelas,id',
            'target_tahun_ajaran' => 'required|string',
            'target_semester' => 'required|string',
            'siswa_action' => 'required|array',
        ]);

        $sourceKelas = Kelas::findOrFail($request->source_kelas_id);
        $targetKelas = Kelas::findOrFail($request->target_kelas_id);
        $sourceTa = $request->get('source_tahun_ajaran');
        $sourceSem = $request->get('source_semester');
        $actions = $request->siswa_action; // [siswa_id => 'naik' / 'tinggal' / 'skip']

        $naikCount = 0;
        $tinggalCount = 0;

        foreach ($actions as $siswaId => $act) {
            if ($act === 'skip') continue;

            $siswa = Siswa::find($siswaId);
            if (!$siswa) continue;

            if ($act === 'naik') {
                // 1. Simpan riwayat rombel baru di siswa_rombel
                SiswaRombel::updateOrCreate(
                    [
                        'siswa_id'     => $siswa->id,
                        'tahun_ajaran' => $request->target_tahun_ajaran,
                        'semester'     => $request->target_semester,
                    ],
                    [
                        'kelas_id'   => $targetKelas->id,
                        'status'     => 'Aktif',
                        'updated_at' => now(),
                    ]
                );

                // 2. Perbarui pointer kelas aktif pada tabel siswa
                $siswa->update([
                    'kelas_id'     => $targetKelas->id,
                    'tahun_ajaran' => $request->target_tahun_ajaran,
                    'semester'     => $request->target_semester,
                    'status'       => 'Aktif',
                ]);
                $naikCount++;
            } elseif ($act === 'tinggal') {
                // Tinggal kelas di kelas asal untuk periode target
                SiswaRombel::updateOrCreate(
                    [
                        'siswa_id'     => $siswa->id,
                        'tahun_ajaran' => $request->target_tahun_ajaran,
                        'semester'     => $request->target_semester,
                    ],
                    [
                        'kelas_id'   => $sourceKelas->id,
                        'status'     => 'Aktif',
                        'updated_at' => now(),
                    ]
                );

                $siswa->update([
                    'tahun_ajaran' => $request->target_tahun_ajaran,
                    'semester'     => $request->target_semester,
                ]);
                $tinggalCount++;
            }
        }

        ActivityLog::record('kenaikan_kelas', 'akademik', "Memproses kenaikan kelas dari {$sourceKelas->nama_kelas} ke {$targetKelas->nama_kelas} ({$request->target_tahun_ajaran} {$request->target_semester}): {$naikCount} naik, {$tinggalCount} tinggal");

        return redirect()->route('kenaikankelas.index', [
            'source_kelas_id' => $request->source_kelas_id,
            'source_tahun_ajaran' => $sourceTa,
            'source_semester' => $sourceSem,
        ])->with('success', "Proses kenaikan kelas berhasil! {$naikCount} siswa naik ke {$targetKelas->nama_kelas}, {$tinggalCount} tinggal kelas.");
    }
}