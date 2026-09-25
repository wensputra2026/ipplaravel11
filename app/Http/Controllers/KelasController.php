<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Walikelas;
use App\Models\Gtk;
use App\Models\ActivityLog;
use App\Models\AppSetting;

class KelasController extends Controller
{
    public function index()
    {
        $activeTa = AppSetting::get('active_tahun_ajaran', '2026/2027');
        $activeSem = AppSetting::get('active_semester', 'Ganjil');

        $kelas = Kelas::withCount(['siswa' => function($q) {
            $q->aktif();
        }])->orderBy('tingkat')->orderBy('nama_kelas')->paginate(10)->withQueryString();

        $gtkList = Gtk::orderBy('nama')->get();

        return view('kelas.index', compact('kelas', 'gtkList', 'activeTa', 'activeSem'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:50',
            'tingkat' => 'required|string|max:10',
            'jurusan' => 'nullable|string|max:50',
        ]);

        $activeTa = AppSetting::get('active_tahun_ajaran', '2026/2027');
        $activeSem = AppSetting::get('active_semester', 'Ganjil');

        $kelas = Kelas::create([
            'nama_kelas' => $validated['nama_kelas'],
            'tingkat' => $validated['tingkat'],
            'jurusan' => $validated['jurusan'],
            'tahun_ajaran' => $activeTa,
            'semester' => $activeSem,
        ]);

        ActivityLog::record('create', 'kelas', "Menambahkan kelas baru: {$kelas->nama_kelas} ({$kelas->tingkat})");

        return redirect()->route('kelas.index')->with('success', "Kelas {$kelas->nama_kelas} berhasil ditambahkan!");
    }

    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:50',
            'tingkat' => 'required|string|max:10',
            'jurusan' => 'nullable|string|max:50',
        ]);

        $kelas->update($validated);

        ActivityLog::record('update', 'kelas', "Memperbarui kelas: {$kelas->nama_kelas}");

        return redirect()->route('kelas.index')->with('success', "Data kelas {$kelas->nama_kelas} berhasil diperbarui!");
    }

    public function destroy($id)
    {
        $kelas = Kelas::withCount('siswa')->findOrFail($id);

        if ($kelas->siswa_count > 0) {
            return back()->with('error', "Kelas {$kelas->nama_kelas} tidak dapat dihapus karena masih memiliki {$kelas->siswa_count} siswa aktif!");
        }

        $nama = $kelas->nama_kelas;
        $kelas->delete();

        ActivityLog::record('delete', 'kelas', "Menghapus kelas: {$nama}");

        return redirect()->route('kelas.index')->with('success', "Kelas {$nama} berhasil dihapus.");
    }
}