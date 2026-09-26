<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterTahunAjaran;
use App\Models\MasterKategoriSiswa;
use App\Models\MasterPekerjaan;
use App\Models\MasterPenghasilan;
use App\Models\MasterSumberBiaya;
use App\Models\ActivityLog;
use App\Models\AppSetting;

class MasterController extends Controller
{
    public function index()
    {
        $tahunList = MasterTahunAjaran::orderBy('id_tahun_ajaran', 'desc')->get();
        $kategoriList = MasterKategoriSiswa::orderBy('id_kategori')->get();
        $pekerjaanList = MasterPekerjaan::orderBy('nama_pekerjaan')->get();
        $penghasilanList = MasterPenghasilan::orderBy('id_penghasilan')->get();
        $sumberBiayaList = MasterSumberBiaya::orderBy('id_sumber_biaya')->get();
        $activeTahunAjaran = AppSetting::get('active_tahun_ajaran', '2025/2026');

        return view('master.index', compact(
            'tahunList', 'kategoriList', 'pekerjaanList', 'penghasilanList', 'sumberBiayaList', 'activeTahunAjaran'
        ));
    }

    public function storeTahunAjaran(Request $request)
    {
        $request->validate(['tahun_ajaran' => 'required|string|max:20|unique:master_tahun_ajaran,tahun_ajaran']);
        MasterTahunAjaran::create(['tahun_ajaran' => $request->tahun_ajaran]);
        ActivityLog::record('create', 'master', "Menambahkan tahun ajaran: {$request->tahun_ajaran}");
        return back()->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    public function updateTahunAjaran(Request $request, $id)
    {
        $tahun = MasterTahunAjaran::findOrFail($id);
        $request->validate(['tahun_ajaran' => 'required|string|max:20|unique:master_tahun_ajaran,tahun_ajaran,' . $id . ',id_tahun_ajaran']);
        $old = $tahun->tahun_ajaran;
        $tahun->update(['tahun_ajaran' => $request->tahun_ajaran]);
        ActivityLog::record('update', 'master', "Mengubah tahun ajaran dari {$old} menjadi {$request->tahun_ajaran}");
        return back()->with('success', 'Tahun ajaran berhasil diperbarui.');
    }

    public function setActiveTahunAjaran($id)
    {
        $tahun = MasterTahunAjaran::findOrFail($id);
        AppSetting::set('active_tahun_ajaran', $tahun->tahun_ajaran);
        ActivityLog::record('update', 'settings', "Mengubah tahun ajaran aktif menjadi: {$tahun->tahun_ajaran}");
        return back()->with('success', "Tahun ajaran {$tahun->tahun_ajaran} berhasil diaktifkan untuk seluruh sistem!");
    }

    public function destroyTahunAjaran($id)
    {
        $tahun = MasterTahunAjaran::findOrFail($id);
        $activeTa = AppSetting::get('active_tahun_ajaran');

        if ($tahun->tahun_ajaran === $activeTa) {
            return back()->with('error', "Tahun ajaran {$tahun->tahun_ajaran} sedang aktif digunakan oleh sistem dan tidak dapat dihapus!");
        }

        $namaTahun = $tahun->tahun_ajaran;
        $tahun->delete();
        ActivityLog::record('delete', 'master', "Menghapus tahun ajaran: {$namaTahun}");
        return back()->with('success', 'Tahun ajaran berhasil dihapus.');
    }

    public function storeKategori(Request $request)
    {
        $request->validate(['nama_kategori' => 'required|string|max:150']);
        MasterKategoriSiswa::create(['nama_kategori' => $request->nama_kategori]);
        ActivityLog::record('create', 'master', "Menambahkan kategori siswa: {$request->nama_kategori}");
        return back()->with('success', 'Kategori siswa berhasil ditambahkan.');
    }

    public function updateKategori(Request $request, $id)
    {
        $kategori = MasterKategoriSiswa::findOrFail($id);
        $request->validate(['nama_kategori' => 'required|string|max:150']);
        $old = $kategori->nama_kategori;
        $kategori->update(['nama_kategori' => $request->nama_kategori]);
        ActivityLog::record('update', 'master', "Mengubah kategori siswa: {$old} -> {$request->nama_kategori}");
        return back()->with('success', 'Kategori siswa berhasil diperbarui.');
    }

    public function destroyKategori($id)
    {
        MasterKategoriSiswa::destroy($id);
        ActivityLog::record('delete', 'master', "Menghapus kategori siswa ID {$id}");
        return back()->with('success', 'Kategori siswa berhasil dihapus.');
    }

    public function storePekerjaan(Request $request)
    {
        $request->validate(['nama_pekerjaan' => 'required|string|max:100|unique:master_pekerjaan,nama_pekerjaan']);
        MasterPekerjaan::create(['nama_pekerjaan' => $request->nama_pekerjaan]);
        ActivityLog::record('create', 'master', "Menambahkan pekerjaan referensi: {$request->nama_pekerjaan}");
        return back()->with('success', 'Pekerjaan berhasil ditambahkan.');
    }

    public function updatePekerjaan(Request $request, $id)
    {
        $pekerjaan = MasterPekerjaan::findOrFail($id);
        $request->validate(['nama_pekerjaan' => 'required|string|max:100|unique:master_pekerjaan,nama_pekerjaan,' . $id . ',id_pekerjaan']);
        $old = $pekerjaan->nama_pekerjaan;
        $pekerjaan->update(['nama_pekerjaan' => $request->nama_pekerjaan]);
        ActivityLog::record('update', 'master', "Mengubah pekerjaan: {$old} -> {$request->nama_pekerjaan}");
        return back()->with('success', 'Pekerjaan berhasil diperbarui.');
    }

    public function destroyPekerjaan($id)
    {
        MasterPekerjaan::destroy($id);
        ActivityLog::record('delete', 'master', "Menghapus pekerjaan referensi ID {$id}");
        return back()->with('success', 'Pekerjaan berhasil dihapus.');
    }

    public function storePenghasilan(Request $request)
    {
        $request->validate(['range_penghasilan' => 'required|string|max:100|unique:master_penghasilan,range_penghasilan']);
        MasterPenghasilan::create(['range_penghasilan' => $request->range_penghasilan]);
        ActivityLog::record('create', 'master', "Menambahkan range penghasilan: {$request->range_penghasilan}");
        return back()->with('success', 'Rentang penghasilan berhasil ditambahkan.');
    }

    public function updatePenghasilan(Request $request, $id)
    {
        $penghasilan = MasterPenghasilan::findOrFail($id);
        $request->validate(['range_penghasilan' => 'required|string|max:100|unique:master_penghasilan,range_penghasilan,' . $id . ',id_penghasilan']);
        $old = $penghasilan->range_penghasilan;
        $penghasilan->update(['range_penghasilan' => $request->range_penghasilan]);
        ActivityLog::record('update', 'master', "Mengubah rentang penghasilan: {$old} -> {$request->range_penghasilan}");
        return back()->with('success', 'Rentang penghasilan berhasil diperbarui.');
    }

    public function destroyPenghasilan($id)
    {
        MasterPenghasilan::destroy($id);
        ActivityLog::record('delete', 'master', "Menghapus rentang penghasilan ID {$id}");
        return back()->with('success', 'Rentang penghasilan berhasil dihapus.');
    }

    public function storeSumberBiaya(Request $request)
    {
        $request->validate(['nama_sumber_biaya' => 'required|string|max:100']);
        MasterSumberBiaya::create(['nama_sumber_biaya' => $request->nama_sumber_biaya]);
        ActivityLog::record('create', 'master', "Menambahkan sumber biaya: {$request->nama_sumber_biaya}");
        return back()->with('success', 'Sumber biaya berhasil ditambahkan.');
    }

    public function updateSumberBiaya(Request $request, $id)
    {
        $sumber = MasterSumberBiaya::findOrFail($id);
        $request->validate(['nama_sumber_biaya' => 'required|string|max:100']);
        $old = $sumber->nama_sumber_biaya;
        $sumber->update(['nama_sumber_biaya' => $request->nama_sumber_biaya]);
        ActivityLog::record('update', 'master', "Mengubah sumber biaya: {$old} -> {$request->nama_sumber_biaya}");
        return back()->with('success', 'Sumber biaya berhasil diperbarui.');
    }

    public function destroySumberBiaya($id)
    {
        MasterSumberBiaya::destroy($id);
        ActivityLog::record('delete', 'master', "Menghapus sumber biaya ID {$id}");
        return back()->with('success', 'Sumber biaya berhasil dihapus.');
    }
}