<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\MasterKategoriSiswa;
use App\Models\MasterPekerjaan;
use App\Models\MasterPenghasilan;
use App\Models\MasterSumberBiaya;
use App\Models\MasterTahunAjaran;
use App\Models\AppSetting;
use App\Models\ActivityLog;
use App\Services\FileCompressionService;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isWali = $user->isWali();

        $query = Siswa::with('kelas');

        // Scope to Wali's class if user is Wali
        if ($isWali && $user->kelas_id) {
            $query->where('kelas_id', $user->kelas_id);
        } elseif ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        // Status Filter (default to Aktif)
        if ($request->filled('status')) {
            if ($request->status === 'Aktif') {
                $query->aktif();
            } else {
                $query->where('status', $request->status);
            }
        } else {
            $query->aktif();
        }

        // Search Filter
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('nama_siswa', 'like', "%{$s}%")
                  ->orWhere('nis', 'like', "%{$s}%")
                  ->orWhere('nisn', 'like', "%{$s}%");
            });
        }

        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $siswa = $query->orderBy('nama_siswa')->paginate(10)->withQueryString();

        return view('siswa.index', compact('siswa', 'kelasList'));
    }

    public function create()
    {
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $kategoriList = MasterKategoriSiswa::pluck('nama_kategori');
        $pekerjaanList = MasterPekerjaan::pluck('nama_pekerjaan');
        $penghasilanList = MasterPenghasilan::pluck('range_penghasilan');
        $sumberBiayaList = MasterSumberBiaya::pluck('nama_sumber_biaya');
        $tahunList = MasterTahunAjaran::pluck('tahun_ajaran');

        $activeTa = AppSetting::get('active_tahun_ajaran', '2026/2027');
        $activeSem = AppSetting::get('active_semester', 'Ganjil');

        return view('siswa.create', compact(
            'kelasList', 'kategoriList', 'pekerjaanList', 'penghasilanList',
            'sumberBiayaList', 'tahunList', 'activeTa', 'activeSem'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_siswa' => 'required|string|max:150',
            'nis' => 'nullable|string|max:50',
            'nisn' => 'nullable|string|max:50',
            'kelas_id' => 'required|exists:kelas,id',
            'jk' => 'required|in:L,P',
            'foto' => 'nullable|image|max:2048',
            'dok_kk' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'dok_sktm' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'dok_slip_gaji' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'dok_bansos' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except(['_token', 'foto', 'dok_kk', 'dok_sktm', 'dok_slip_gaji', 'dok_bansos']);
        $data['status'] = $data['status'] ?? 'Aktif';

        // File uploads
        $destination = public_path('uploads/siswa');
        if (!is_dir($destination)) {
            mkdir($destination, 0777, true);
        }

        foreach (['foto', 'dok_kk', 'dok_sktm', 'dok_slip_gaji', 'dok_bansos'] as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                if ($field === 'foto') {
                    $filename = FileCompressionService::compressAndUploadImage(
                        $file,
                        $destination,
                        'foto_siswa',
                        800,
                        1000,
                        82
                    );
                } else {
                    $filename = FileCompressionService::compressAndUploadDocument(
                        $file,
                        $destination,
                        $field
                    );
                }
                $data[$field] = $filename;
            }
        }

        $siswa = Siswa::create($data);

        ActivityLog::record('create', 'siswa', "Menambahkan siswa baru: {$siswa->nama_siswa} (NIS: {$siswa->nis})");

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil ditambahkan!');
    }

    public function show($id)
    {
        $siswa = Siswa::with('kelas')->findOrFail($id);
        return view('siswa.show', compact('siswa'));
    }

    public function edit($id)
    {
        $siswa = Siswa::findOrFail($id);
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $kategoriList = MasterKategoriSiswa::pluck('nama_kategori');
        $pekerjaanList = MasterPekerjaan::pluck('nama_pekerjaan');
        $penghasilanList = MasterPenghasilan::pluck('range_penghasilan');
        $sumberBiayaList = MasterSumberBiaya::pluck('nama_sumber_biaya');
        $tahunList = MasterTahunAjaran::pluck('tahun_ajaran');

        return view('siswa.edit', compact(
            'siswa', 'kelasList', 'kategoriList', 'pekerjaanList',
            'penghasilanList', 'sumberBiayaList', 'tahunList'
        ));
    }

    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);

        $validated = $request->validate([
            'nama_siswa' => 'required|string|max:150',
            'nis' => 'nullable|string|max:50',
            'nisn' => 'nullable|string|max:50',
            'kelas_id' => 'required|exists:kelas,id',
            'jk' => 'required|in:L,P',
            'foto' => 'nullable|image|max:2048',
            'dok_kk' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'dok_sktm' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'dok_slip_gaji' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'dok_bansos' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except(['_token', '_method', 'foto', 'dok_kk', 'dok_sktm', 'dok_slip_gaji', 'dok_bansos']);

        $destination = public_path('uploads/siswa');
        if (!is_dir($destination)) {
            mkdir($destination, 0777, true);
        }

        foreach (['foto', 'dok_kk', 'dok_sktm', 'dok_slip_gaji', 'dok_bansos'] as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);

                // Delete old file if present
                if (!empty($siswa->{$field}) && file_exists($destination . '/' . $siswa->{$field})) {
                    @unlink($destination . '/' . $siswa->{$field});
                }

                if ($field === 'foto') {
                    $filename = FileCompressionService::compressAndUploadImage(
                        $file,
                        $destination,
                        'foto_siswa',
                        800,
                        1000,
                        82
                    );
                } else {
                    $filename = FileCompressionService::compressAndUploadDocument(
                        $file,
                        $destination,
                        $field
                    );
                }
                $data[$field] = $filename;
            }
        }

        $siswa->update($data);

        ActivityLog::record('update', 'siswa', "Memperbarui data siswa: {$siswa->nama_siswa} (ID: {$siswa->id})");

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);
        $nama = $siswa->nama_siswa;
        $siswa->delete();

        ActivityLog::record('delete', 'siswa', "Menghapus siswa: {$nama} (ID: {$id})");

        return redirect()->route('siswa.index')->with('success', "Data siswa {$nama} berhasil dihapus.");
    }

    public function konversi()
    {
        $rulesOccupation = [
            ['nama' => 'Buruh / Tani / Nelayan / Tidak Bekerja', 'income' => '< Rp 1.000.000', 'range' => '< Rp 1.000.000', 'percentage' => '0% (Gratis)'],
            ['nama' => 'Pedagang Kecil / Wiraswasta Mikro / Serabutan', 'income' => 'Rp 1.000.000 - Rp 2.000.000', 'range' => 'Rp 1.000.000 - Rp 2.000.000', 'percentage' => '25%'],
            ['nama' => 'Karyawan Swasta / Honorer / Petani Mandiri', 'income' => 'Rp 2.000.000 - Rp 3.500.000', 'range' => 'Rp 2.000.000 - Rp 3.500.000', 'percentage' => '50%'],
            ['nama' => 'ASN / PNS Gol I & II / TNI-Polri Bintara', 'income' => 'Rp 3.500.000 - Rp 5.000.000', 'range' => 'Rp 3.500.000 - Rp 5.000.000', 'percentage' => '75%'],
            ['nama' => 'ASN / PNS Gol III & IV / Pejabat / Pengusaha Besar', 'income' => '> Rp 5.000.000', 'range' => '> Rp 5.000.000', 'percentage' => '100%'],
        ];

        $rulesRange = [
            ['range' => '< Rp 1.000.000 (Tidak Berpenghasilan / Kurang Mampu)', 'percentage' => '0% (Gratis)'],
            ['range' => 'Rp 1.000.000 - Rp 2.000.000', 'percentage' => '25%'],
            ['range' => 'Rp 2.000.000 - Rp 3.500.000', 'percentage' => '50%'],
            ['range' => 'Rp 3.500.000 - Rp 5.000.000', 'percentage' => '75%'],
            ['range' => '> Rp 5.000.000 (Mampu / Sejahtera)', 'percentage' => '100%'],
        ];

        return view('siswa.konversi', compact('rulesOccupation', 'rulesRange'));
    }

    public function processKonversi()
    {
        $siswaList = Siswa::aktif()->get();
        $updatedCount = 0;

        foreach ($siswaList as $s) {
            $kat = '100%';
            $nom = 100000;

            // Prioritas kategori khusus (Yatim / Piatu / SKTM / Bansos)
            $isKhusus = in_array(strtolower($s->kategori_siswa ?? ''), ['yatim', 'piatu', 'yatim piatu', 'disabilitas', 'panti asuhan', 'sktm', 'kurang mampu'])
                        || !empty($s->dok_sktm) || !empty($s->dok_bansos) || !empty($s->ket_bansos);

            if ($isKhusus) {
                $kat = '0% (Gratis)';
                $nom = 0;
            } else {
                // Periksa penghasilan ayah/wali
                $penghasilan = $s->penghasilan_ayah ?: ($s->penghasilan_ibu ?: $s->penghasilan_wali_l);
                if (stripos($penghasilan, '< 1') !== false || stripos($penghasilan, 'kurang') !== false || stripos($penghasilan, 'tidak berpenghasilan') !== false) {
                    $kat = '0% (Gratis)';
                    $nom = 0;
                } elseif (stripos($penghasilan, '1.000.000 - 2') !== false || stripos($penghasilan, '1jt - 2jt') !== false) {
                    $kat = '25%';
                    $nom = 25000;
                } elseif (stripos($penghasilan, '2.000.000 - 3') !== false || stripos($penghasilan, '2jt - 3') !== false) {
                    $kat = '50%';
                    $nom = 50000;
                } elseif (stripos($penghasilan, '3.500.000 - 5') !== false || stripos($penghasilan, '3jt - 5jt') !== false) {
                    $kat = '75%';
                    $nom = 75000;
                } elseif (stripos($penghasilan, '> 5') !== false || stripos($penghasilan, 'lebih dari 5') !== false) {
                    $kat = '100%';
                    $nom = 100000;
                }
            }

            $s->update([
                'kategori_ipp' => $kat,
                'nominal_ipp' => $nom,
            ]);
            $updatedCount++;
        }

        ActivityLog::record('konversi', 'siswa', "Menjalankan kalkulasi konversi massal IPP untuk {$updatedCount} siswa");

        return redirect()->route('siswa.konversi')->with('success', "Konversi massal selesai! Berhasil memperbarui {$updatedCount} data siswa.");
    }

    /**
     * Endpoint Remote AJAX Search untuk Tom Select
     * Mengembalikan format JSON: [{ "value": id, "text": label }]
     * Dibatasi maksimal 15 data untuk efisiensi performa.
     */
    public function searchAjax(Request $request)
    {
        $q = trim($request->get('q', ''));
        $query = Siswa::with('kelas');

        $user = Auth::user();
        if ($user && $user->isWali() && $user->kelas_id) {
            $query->where('kelas_id', $user->kelas_id);
        }

        if ($q !== '') {
            $query->where(function ($builder) use ($q) {
                $builder->where('nama_siswa', 'like', "%{$q}%")
                        ->orWhere('nis', 'like', "%{$q}%")
                        ->orWhere('nisn', 'like', "%{$q}%");
            });
        }

        $results = $query->orderBy('nama_siswa')
            ->limit(15)
            ->get()
            ->map(function ($s) {
                $kelasLabel = $s->kelas ? " ({$s->kelas->nama_kelas})" : '';
                return [
                    'value' => (string) $s->id,
                    'text'  => "{$s->nama_siswa}{$kelasLabel} - NIS: " . ($s->nis ?: '-'),
                ];
            });

        return response()->json($results);
    }
}