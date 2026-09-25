<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\MasterTahunAjaran;
use App\Models\AppSetting;
use App\Models\ActivityLog;

class KelulusanController extends Controller
{
    public function index(Request $request)
    {
        $activeTa = AppSetting::get('active_tahun_ajaran', '2026/2027');
        $activeSem = AppSetting::get('active_semester', 'Ganjil');

        $kelasXiiList = Kelas::where('tingkat', 'XII')->orderBy('nama_kelas')->get();
        $tahunList = MasterTahunAjaran::pluck('tahun_ajaran');

        // Tab selection (proses vs alumni)
        $tab = $request->get('tab', 'alumni');

        // Process tab data
        $selectedKelasId = $request->get('kelas_id');
        $siswaCalon = collect();
        if ($selectedKelasId) {
            $siswaCalon = Siswa::aktif()->where('kelas_id', $selectedKelasId)->orderBy('nama_siswa')->get();
        }

        // Alumni tab data
        $alumniQuery = Siswa::lulus()->with('kelas');
        if ($request->filled('filter_tahun_lulus')) {
            $alumniQuery->where('tahun_lulus', $request->filter_tahun_lulus);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $alumniQuery->where(function($q) use ($s) {
                $q->where('nama_siswa', 'like', "%{$s}%")
                  ->orWhere('nis', 'like', "%{$s}%")
                  ->orWhere('no_ijazah', 'like', "%{$s}%");
            });
        }
        $alumniList = $alumniQuery->orderBy('tahun_lulus', 'desc')->orderBy('nama_siswa')->paginate(15)->withQueryString();

        return view('kelulusan.index', compact(
            'kelasXiiList', 'tahunList', 'activeTa', 'activeSem', 'tab',
            'selectedKelasId', 'siswaCalon', 'alumniList'
        ));
    }

    public function process(Request $request)
    {
        $request->validate([
            'siswa_ids' => 'required|array|min:1',
            'tahun_lulus' => 'required|string',
            'tanggal_lulus' => 'required|date',
        ]);

        $ids = $request->siswa_ids;
        $tahunLulus = $request->tahun_lulus;
        $tanggalLulus = $request->tanggal_lulus;

        Siswa::whereIn('id', $ids)->update([
            'status' => 'Lulus',
            'tahun_lulus' => $tahunLulus,
            'tanggal_lulus' => $tanggalLulus,
        ]);

        $count = count($ids);
        ActivityLog::record('kelulusan', 'akademik', "Menetapkan kelulusan untuk {$count} siswa pada tahun {$tahunLulus}");

        return redirect()->route('kelulusan.index', ['tab' => 'alumni'])
            ->with('success', "Selamat! {$count} siswa berhasil diproses kelulusannya.");
    }

    public function cancelGraduation($id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->update([
            'status' => 'Aktif',
            'tahun_lulus' => null,
            'tanggal_lulus' => null,
            'no_ijazah' => null,
        ]);

        ActivityLog::record('cancel_kelulusan', 'akademik', "Membatalkan kelulusan siswa: {$siswa->nama_siswa}");

        return redirect()->route('kelulusan.index', ['tab' => 'alumni'])
            ->with('success', "Kelulusan siswa {$siswa->nama_siswa} telah dibatalkan (kembali aktif).");
    }
}