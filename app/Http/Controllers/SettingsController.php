<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppSetting;
use App\Models\MasterTahunAjaran;
use App\Models\ActivityLog;

class SettingsController extends Controller
{
    public function index()
    {
        $appName = AppSetting::get('app_name', 'E-IPP');
        $schoolName = AppSetting::get('school_name', 'SMAN Benlutu');
        $activeTa = AppSetting::get('active_tahun_ajaran', '2026/2027');
        $activeSem = AppSetting::get('active_semester', 'Ganjil');
        $schoolLogo = AppSetting::get('school_logo', 'logo_1767853884.png');

        $tahunList = MasterTahunAjaran::pluck('tahun_ajaran');

        return view('settings.index', compact(
            'appName', 'schoolName', 'activeTa', 'activeSem', 'schoolLogo', 'tahunList'
        ));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:100',
            'school_name' => 'required|string|max:150',
            'active_tahun_ajaran' => 'required|string|max:20',
            'active_semester' => 'required|string|max:20',
            'school_logo' => 'nullable|image|max:2048',
        ]);

        AppSetting::set('app_name', $validated['app_name']);
        AppSetting::set('school_name', $validated['school_name']);
        AppSetting::set('active_tahun_ajaran', $validated['active_tahun_ajaran']);
        AppSetting::set('active_semester', $validated['active_semester']);

        if ($request->hasFile('school_logo')) {
            $dest = public_path('assets/dist/img');
            $filename = \App\Services\FileCompressionService::compressAndUploadImage(
                $request->file('school_logo'),
                $dest,
                'logo',
                600,
                600,
                85
            );
            AppSetting::set('school_logo', $filename);
        }

        ActivityLog::record('update', 'settings', "Memperbarui konfigurasi sistem aplikasi");

        return redirect()->route('settings.index')->with('success', 'Pengaturan sistem berhasil disimpan!');
    }
}