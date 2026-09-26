<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Walikelas;
use App\Models\Kelas;
use App\Models\Gtk;
use App\Models\ActivityLog;
use App\Models\AppSetting;
use App\Models\MasterTahunAjaran;

class WalikelasController extends Controller
{
    public function index()
    {
        $activeTa = AppSetting::get('active_tahun_ajaran', '2026/2027');
        $activeSem = AppSetting::get('active_semester', 'Ganjil');

        $walikelasList = Walikelas::with(['gtk', 'kelas'])
            ->where('tahun_ajaran', $activeTa)
            ->where('semester', $activeSem)
            ->orderBy('id_walikelas', 'desc')
            ->paginate(10)
            ->withQueryString();

        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $gtkList = Gtk::orderBy('nama')->get();
        $tahunList = MasterTahunAjaran::orderBy('tahun_ajaran', 'desc')->pluck('tahun_ajaran');

        return view('walikelas.index', compact('walikelasList', 'kelasList', 'gtkList', 'tahunList', 'activeTa', 'activeSem'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_gtk' => 'required|exists:gtk,id',
            'id_kelas' => 'required|exists:kelas,id',
            'tahun_ajaran' => 'required|string|max:20',
            'semester' => 'required|string|max:20',
        ]);

        $wali = Walikelas::create([
            'id_gtk' => $validated['id_gtk'],
            'id_kelas' => $validated['id_kelas'],
            'tahun_ajaran' => $validated['tahun_ajaran'],
            'semester' => $validated['semester'],
            'created_at' => now(),
        ]);

        // Sync with User account linked to this GTK
        $userWali = \App\Models\User::where('gtk_id', $validated['id_gtk'])->first();
        if ($userWali && $userWali->isWali()) {
            $userWali->kelas_id = $validated['id_kelas'];
            $userWali->save();
        }

        ActivityLog::record('create', 'walikelas', "Menugaskan wali kelas untuk kelas ID {$wali->id_kelas}");

        return redirect()->route('walikelas.index')->with('success', 'Penugasan wali kelas berhasil disimpan!');
    }

    public function update(Request $request, $id)
    {
        $wali = Walikelas::findOrFail($id);

        $validated = $request->validate([
            'id_gtk' => 'required|exists:gtk,id',
            'id_kelas' => 'required|exists:kelas,id',
            'tahun_ajaran' => 'required|string|max:20',
            'semester' => 'required|string|max:20',
        ]);

        $wali->update([
            'id_gtk' => $validated['id_gtk'],
            'id_kelas' => $validated['id_kelas'],
            'tahun_ajaran' => $validated['tahun_ajaran'],
            'semester' => $validated['semester'],
        ]);

        // Sync with User account linked to this GTK
        $userWali = \App\Models\User::where('gtk_id', $validated['id_gtk'])->first();
        if ($userWali && $userWali->isWali()) {
            $userWali->kelas_id = $validated['id_kelas'];
            $userWali->save();
        }

        ActivityLog::record('update', 'walikelas', "Memperbarui penugasan wali kelas ID {$id}");

        return redirect()->route('walikelas.index')->with('success', 'Penugasan wali kelas berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $wali = Walikelas::findOrFail($id);
        $wali->delete();

        ActivityLog::record('delete', 'walikelas', "Menghapus penugasan wali kelas ID {$id}");

        return redirect()->route('walikelas.index')->with('success', 'Penugasan wali kelas berhasil dihapus.');
    }
}