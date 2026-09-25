<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\ActivityLog;

class SiswaPindahController extends Controller
{
    public function index(Request $request)
    {
        $kelasList = Kelas::orderBy('nama_kelas')->get();

        $sourceKelasId = $request->get('source_kelas_id');
        $siswaList = collect();

        if ($sourceKelasId) {
            $siswaList = Siswa::aktif()->where('kelas_id', $sourceKelasId)->orderBy('nama_siswa')->get();
        }

        return view('siswapindah.index', compact('kelasList', 'sourceKelasId', 'siswaList'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'source_kelas_id' => 'required|exists:kelas,id',
            'target_kelas_id' => 'required|exists:kelas,id|different:source_kelas_id',
            'siswa_ids' => 'required|array|min:1',
        ]);

        $sourceKelas = Kelas::findOrFail($request->source_kelas_id);
        $targetKelas = Kelas::findOrFail($request->target_kelas_id);

        Siswa::whereIn('id', $request->siswa_ids)->update([
            'kelas_id' => $targetKelas->id,
        ]);

        $count = count($request->siswa_ids);
        ActivityLog::record('pindah_kelas', 'akademik', "Memindahkan {$count} siswa dari {$sourceKelas->nama_kelas} ke {$targetKelas->nama_kelas}");

        return redirect()->route('siswapindah.index', ['source_kelas_id' => $request->source_kelas_id])
            ->with('success', "Berhasil memindahkan {$count} siswa ke kelas {$targetKelas->nama_kelas}!");
    }
}