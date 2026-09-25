<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\MasterTahunAjaran;
use App\Models\AppSetting;
use App\Models\ActivityLog;

class KenaikanKelasController extends Controller
{
    public function index(Request $request)
    {
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        $tahunList = MasterTahunAjaran::pluck('tahun_ajaran');

        $activeTa = AppSetting::get('active_tahun_ajaran', '2026/2027');
        $activeSem = AppSetting::get('active_semester', 'Ganjil');

        $sourceKelasId = $request->get('source_kelas_id');
        $sourceKelas = $sourceKelasId ? Kelas::find($sourceKelasId) : null;

        $siswaList = collect();
        if ($sourceKelasId) {
            $siswaList = Siswa::aktif()->where('kelas_id', $sourceKelasId)->orderBy('nama_siswa')->get();
        }

        return view('kenaikankelas.index', compact(
            'kelasList', 'tahunList', 'activeTa', 'activeSem', 'sourceKelasId', 'sourceKelas', 'siswaList'
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
        $actions = $request->siswa_action; // [siswa_id => 'naik' / 'tinggal' / 'skip']

        $naikCount = 0;
        $tinggalCount = 0;

        foreach ($actions as $siswaId => $act) {
            if ($act === 'skip') continue;

            $siswa = Siswa::find($siswaId);
            if (!$siswa) continue;

            if ($act === 'naik') {
                $siswa->update([
                    'kelas_id' => $targetKelas->id,
                    'tahun_ajaran' => $request->target_tahun_ajaran,
                    'semester' => $request->target_semester,
                ]);
                $naikCount++;
            } elseif ($act === 'tinggal') {
                $siswa->update([
                    'tahun_ajaran' => $request->target_tahun_ajaran,
                    'semester' => $request->target_semester,
                ]);
                $tinggalCount++;
            }
        }

        ActivityLog::record('kenaikan_kelas', 'akademik', "Memproses kenaikan kelas dari {$sourceKelas->nama_kelas} ke {$targetKelas->nama_kelas}: {$naikCount} naik, {$tinggalCount} tinggal");

        return redirect()->route('kenaikankelas.index', ['source_kelas_id' => $request->source_kelas_id])
            ->with('success', "Proses kenaikan kelas berhasil! {$naikCount} siswa naik kelas, {$tinggalCount} tinggal kelas.");
    }
}