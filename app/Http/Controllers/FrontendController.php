<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Walikelas;
use App\Models\Gtk;
use App\Models\AppSetting;
use App\Models\MasterTahunAjaran;
use App\Models\MasterPekerjaan;
use App\Models\MasterPenghasilan;

class FrontendController extends Controller
{
    public function index(Request $request)
    {
        $settingTa = AppSetting::get('active_tahun_ajaran', '2025/2026');
        $settingSem = AppSetting::get('active_semester', 'Genap');

        $tahunAktif = $request->get('tahun_ajaran', session('tahun_ajaran', $settingTa));
        $semesterAktif = $request->get('semester', session('semester', $settingSem));

        // Keep in session
        session(['tahun_ajaran' => $tahunAktif, 'semester' => $semesterAktif]);

        // Base Query Siswa
        $query = Siswa::aktif();
        if (!empty($tahunAktif)) {
            $query->where('tahun_ajaran', $tahunAktif);
        }
        if (!empty($semesterAktif)) {
            $query->whereRaw('LOWER(semester) = ?', [strtolower($semesterAktif)]);
        }

        $allSiswa = $query->get();
        $totalSiswa = $allSiswa->count();
        $totalLaki = $allSiswa->where('jk', 'L')->count();
        $totalPerempuan = $allSiswa->where('jk', 'P')->count();

        // Total Kelas
        $kelasQuery = Kelas::query();
        if (!empty($tahunAktif)) {
            $kelasQuery->where('tahun_ajaran', $tahunAktif);
        }
        if (!empty($semesterAktif)) {
            $kelasQuery->whereRaw('LOWER(semester) = ?', [strtolower($semesterAktif)]);
        }
        $totalKelas = $kelasQuery->count();
        if ($totalKelas === 0 && Kelas::count() > 0) {
            $totalKelas = Kelas::count();
        }

        // Tally helper functions
        $tally = function($collection, $field) {
            $counts = [];
            foreach ($collection as $item) {
                $val = trim((string)($item->{$field} ?? ''));
                if ($val === '') continue;
                $counts[$val] = ($counts[$val] ?? 0) + 1;
            }
            arsort($counts);
            return $counts;
        };

        $tallyWithMaster = function($collection, $field, $masterList, $masterField) {
            $counts = [];
            foreach ($masterList as $m) {
                $val = trim((string)($m->{$masterField} ?? ''));
                if ($val !== '') {
                    $counts[$val] = 0;
                }
            }
            foreach ($collection as $item) {
                $val = trim((string)($item->{$field} ?? ''));
                if ($val === '') continue;
                $counts[$val] = ($counts[$val] ?? 0) + 1;
            }
            arsort($counts);
            return $counts;
        };

        // Master Lists
        $masterPekerjaan = MasterPekerjaan::orderBy('nama_pekerjaan')->get();
        $masterPenghasilan = MasterPenghasilan::orderBy('id_penghasilan')->get();

        // Demographics
        $distJk = [];
        if ($totalLaki > 0) $distJk['Laki-laki'] = $totalLaki;
        if ($totalPerempuan > 0) $distJk['Perempuan'] = $totalPerempuan;

        $distAgama = $tally($allSiswa, 'agama');
        $distKategori = $tally($allSiswa, 'kategori_siswa');
        $distIpp = $tally($allSiswa, 'kategori_ipp');

        // Pekerjaan & Penghasilan Orang Tua
        $distPekerjaanAyah = $tallyWithMaster($allSiswa, 'pekerjaan_ayah', $masterPekerjaan, 'nama_pekerjaan');
        $distPekerjaanIbu = $tallyWithMaster($allSiswa, 'pekerjaan_ibu', $masterPekerjaan, 'nama_pekerjaan');
        $distPenghasilanAyah = $tallyWithMaster($allSiswa, 'penghasilan_ayah', $masterPenghasilan, 'range_penghasilan');
        $distPenghasilanIbu = $tallyWithMaster($allSiswa, 'penghasilan_ibu', $masterPenghasilan, 'range_penghasilan');

        // Kategorisasi Khusus
        $kategoriDetail = [
            'panti_asuhan' => 0,
            'korban_bencana' => 0,
            'terlantar' => 0,
            'ortu_abk' => 0,
            'ortu_sakit_menahun' => 0,
        ];
        foreach ($allSiswa as $s) {
            $val = strtolower(trim((string)$s->kategori_siswa));
            if ($val === '') continue;
            if ($val === 'a' || strpos($val, 'panti') !== false) {
                $kategoriDetail['panti_asuhan']++;
            } elseif ($val === 'b' || strpos($val, 'bencana') !== false) {
                $kategoriDetail['korban_bencana']++;
            } elseif ($val === 'c' || strpos($val, 'terlantar') !== false) {
                $kategoriDetail['terlantar']++;
            } elseif ($val === 'd' || strpos($val, 'abk') !== false || strpos($val, 'kebutuhan khusus') !== false) {
                $kategoriDetail['ortu_abk']++;
            } elseif ($val === 'e' || strpos($val, 'sakit') !== false) {
                $kategoriDetail['ortu_sakit_menahun']++;
            }
        }

        // Tanggungan Siswa
        $distTanggunganSiswa = $tally($allSiswa, 'jml_tanggungan_ortu');

        // Tanggungan Keluarga (Proxy Kepala Keluarga)
        $families = [];
        foreach ($allSiswa as $s) {
            $ayah = trim($s->nama_ayah ?? '');
            $ibu = trim($s->nama_ibu ?? '');
            $waliL = trim($s->nama_wali_l ?? '');
            $waliP = trim($s->nama_wali_p ?? '');

            if ($ayah && $ibu) {
                $familyKey = strtolower($ayah . '|' . $ibu);
            } elseif ($ayah) {
                $familyKey = strtolower($ayah . '|none');
            } elseif ($ibu) {
                $familyKey = strtolower('none|' . $ibu);
            } elseif ($waliL && $waliP) {
                $familyKey = strtolower('wali:' . $waliL . '|' . $waliP);
            } elseif ($waliL) {
                $familyKey = strtolower('wali:' . $waliL . '|none');
            } elseif ($waliP) {
                $familyKey = strtolower('wali:none|' . $waliP);
            } else {
                $familyKey = 'student:' . $s->id;
            }

            if (!isset($families[$familyKey])) {
                $kepala = $ayah ?: ($waliL ?: ($ibu ?: ($waliP ?: 'Tidak Teridentifikasi')));
                $families[$familyKey] = [
                    'kepala_keluarga' => $kepala,
                    'tanggungan' => (int)($s->jml_tanggungan_ortu ?: ($s->jml_tanggungan_wali ?: 0)),
                    'siswa_list' => [],
                ];
            }
            $families[$familyKey]['siswa_list'][] = $s->nama_siswa;
        }

        $distTanggunganKeluarga = [];
        foreach ($families as $f) {
            $t = (string)$f['tanggungan'];
            $distTanggunganKeluarga[$t] = ($distTanggunganKeluarga[$t] ?? 0) + 1;
        }
        ksort($distTanggunganKeluarga);

        uasort($families, function($a, $b) {
            return strcmp($a['kepala_keluarga'], $b['kepala_keluarga']);
        });

        // Class Progress
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $classProgress = [];
        foreach ($kelasList as $kelas) {
            $studentsInClass = $allSiswa->where('kelas_id', $kelas->id);
            $countInClass = $studentsInClass->count();
            if ($countInClass === 0) continue;

            $complete = 0;
            $incomplete = 0;
            $totalPct = 0;

            foreach ($studentsInClass as $st) {
                $fields = [
                    !empty($st->nis),
                    !empty($st->nisn),
                    !empty($st->no_kk),
                    !empty($st->nama_siswa),
                    !empty($st->jk),
                    !empty($st->tempat_lahir),
                    !empty($st->tanggal_lahir),
                    !empty($st->alamat),
                    (!empty($st->nama_ayah) || !empty($st->nama_wali_l)),
                    (!empty($st->nama_ibu) || !empty($st->nama_wali_p)),
                    !empty($st->kategori_ipp),
                ];
                $pct = round((count(array_filter($fields)) / count($fields)) * 100);
                $totalPct += $pct;
                if ($pct >= 100) {
                    $complete++;
                } else {
                    $incomplete++;
                }
            }

            $avgPct = round($totalPct / $countInClass);
            $classProgress[] = [
                'nama_kelas' => $kelas->nama_kelas,
                'total' => $countInClass,
                'complete' => $complete,
                'complete_pct' => round(($complete / $countInClass) * 100),
                'incomplete' => $incomplete,
                'avg_percentage' => $avgPct,
            ];
        }

        usort($classProgress, function($a, $b) {
            $map = ['X' => 1, 'XI' => 2, 'XII' => 3];
            $getGrade = function($name) use ($map) {
                foreach ($map as $key => $val) {
                    if (preg_match('/^' . $key . '\b/i', $name)) return $val;
                }
                return 99;
            };
            $gA = $getGrade($a['nama_kelas']);
            $gB = $getGrade($b['nama_kelas']);
            if ($gA !== $gB) return $gA - $gB;
            return strnatcasecmp($a['nama_kelas'], $b['nama_kelas']);
        });

        // Daftar Tahun Ajaran
        $daftarTahun = MasterTahunAjaran::pluck('tahun_ajaran')
            ->merge(Siswa::pluck('tahun_ajaran'))
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $appName = AppSetting::get('app_name', 'E-IPP');
        $schoolName = AppSetting::get('school_name', 'SMAN Benlutu');
        $schoolLogo = AppSetting::get('school_logo', 'logo_1767853884.png');
        $copyright = AppSetting::get('copyright', 'Copyright &copy; ' . date('Y') . ' ' . $schoolName . '. All rights reserved.');

        return view('frontend.index', [
            'appName' => $appName,
            'schoolName' => $schoolName,
            'schoolLogo' => $schoolLogo,
            'copyright' => $copyright,
            'totalSiswa' => $totalSiswa,
            'totalLaki' => $totalLaki,
            'totalPerempuan' => $totalPerempuan,
            'totalKelas' => $totalKelas,
            'tahunAktif' => $tahunAktif,
            'semesterAktif' => $semesterAktif,
            'daftarTahun' => $daftarTahun,
            'kategoriDetail' => $kategoriDetail,
            'distJk' => $distJk,
            'distAgama' => $distAgama,
            'distKategori' => $distKategori,
            'distIpp' => $distIpp,
            'distPekerjaanAyah' => $distPekerjaanAyah,
            'distPekerjaanIbu' => $distPekerjaanIbu,
            'distPenghasilanAyah' => $distPenghasilanAyah,
            'distPenghasilanIbu' => $distPenghasilanIbu,
            'distTanggunganSiswa' => $distTanggunganSiswa,
            'distTanggunganKeluarga' => $distTanggunganKeluarga,
            'families' => array_values($families),
            'classProgress' => $classProgress,
        ]);
    }
}