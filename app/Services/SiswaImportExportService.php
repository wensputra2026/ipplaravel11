<?php

namespace App\Services;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\MasterKategoriSiswa;
use App\Models\MasterPekerjaan;
use App\Models\MasterPenghasilan;
use App\Models\MasterSumberBiaya;
use App\Models\AppSetting;
use App\Models\ActivityLog;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class SiswaImportExportService
{
    /**
     * Download Template Excel untuk Import Data Siswa
     */
    public function downloadTemplate(): StreamedResponse
    {
        $schoolName = AppSetting::get('school_name', 'SMAN Benlutu');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Siswa');

        // Group Headers (Row 1)
        $sheet->mergeCells('A1:N1');
        $sheet->setCellValue('A1', 'Data Siswa');

        $sheet->mergeCells('O1:W1');
        $sheet->setCellValue('O1', 'Data Orangtua Kandung');

        $sheet->mergeCells('X1:AF1');
        $sheet->setCellValue('X1', 'Data Wali');

        // Detailed Headers (Row 2) - Sesuai sistem referensi IPP
        $headers = [
            'No',
            'Kelas',
            'Nama Siswa',
            'JK',
            'Agama',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Alamat',
            'RT',
            'RW',
            'Dusun',
            'Kelurahan',
            'Sumber Biaya Sekolah',
            'Kategori Siswa',
            'Nama Ayah',
            'Pekerjaan',
            'Penghasilan rata-rata/ bulan',
            'Alamat Domisili',
            'Nama Ibu',
            'Pekerjaan',
            'Penghasilan rata-rata/ bulan',
            'Alamat Domisili',
            'Jumlah tanggungan anak yang bersekolah di ' . $schoolName,
            'Nama Wali Laki-laki',
            'Pekerjaan',
            'Penghasilan rata-rata/ bulan',
            'Alamat Domisili',
            'Nama Wali Perempuan',
            'Pekerjaan',
            'Penghasilan rata-rata/ bulan',
            'Alamat Domisili',
            'Jumlah tanggungan anak yang bersekolah di ' . $schoolName
        ];

        $sheet->fromArray([$headers], null, 'A2');

        // Sample Data Rows (Row 3 & 4) untuk panduan pengisian pengguna
        $sampleRows = [
            [
                1,
                'X-1',
                'Contoh Siswa Laki-laki',
                'L',
                'Kristen Protestan',
                'Kupang',
                '2008-05-12',
                'Jl. Timor Raya No. 12',
                '01',
                '02',
                'Dusun I',
                'Benlutu',
                'Orang Tua',
                'Reguler',
                'Yohanes',
                'Petani',
                '< Rp 1.000.000',
                'Desa Benlutu',
                'Maria',
                'Ibu Rumah Tangga',
                '< Rp 1.000.000',
                'Desa Benlutu',
                2,
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                ''
            ],
            [
                2,
                'X-2',
                'Contoh Siswa Perempuan',
                'P',
                'Katolik',
                'Soe',
                '2008-08-20',
                'Jl. Soe No. 45',
                '03',
                '01',
                'Dusun II',
                'Benlutu',
                'Wali',
                'Afirmasi / SKTM',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                'Petrus',
                'Wiraswasta',
                'Rp 1.000.000 - Rp 2.000.000',
                'Desa Benlutu',
                'Elisabeth',
                'Pedagang',
                'Rp 1.000.000 - Rp 2.000.000',
                'Desa Benlutu',
                1
            ]
        ];

        $sheet->fromArray($sampleRows, null, 'A3');

        // Styling
        $lastCol = Coordinate::stringFromColumnIndex(count($headers));
        $headerRange = "A1:{$lastCol}2";

        $sheet->getStyle($headerRange)->getFont()->setBold(true);
        $sheet->getStyle("A1:{$lastCol}1")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A1:{$lastCol}1")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle("A2:{$lastCol}2")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A2:{$lastCol}2")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle("A2:{$lastCol}2")->getAlignment()->setWrapText(true);

        // Thin borders on headers and sample rows
        $sheet->getStyle("A1:{$lastCol}4")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Header Background Colors
        // Data Siswa: A-N (Light Gray)
        $sheet->getStyle('A1:N2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF0F0F0');
        // Data Orangtua: O-W (Light Yellow)
        $sheet->getStyle('O1:W2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFF9C4');
        // Data Wali: X-AF (Light Blue)
        $sheet->getStyle("X1:{$lastCol}2")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFE1F5FE');

        // Row Heights
        $sheet->getRowDimension(1)->setRowHeight(32);
        $sheet->getRowDimension(2)->setRowHeight(42);
        $sheet->getRowDimension(3)->setRowHeight(24);
        $sheet->getRowDimension(4)->setRowHeight(24);

        // Auto size columns
        foreach (range(1, count($headers)) as $i) {
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($i))->setAutoSize(true);
        }
        $sheet->freezePane('A3');

        // -------------------------------------------------------------
        // Sheet 2: Tab Referensi (Daftar Kelas, Agama, Kategori, dll)
        // -------------------------------------------------------------
        $refSheet = $spreadsheet->createSheet();
        $refSheet->setTitle('Referensi');

        $refHeaders = [
            'Rombel / Kelas',
            'Jenis Kelamin (JK)',
            'Agama',
            'Kategori Siswa',
            'Sumber Biaya Sekolah',
            'Pekerjaan Ortu / Wali',
            'Range Penghasilan Ortu / Wali'
        ];
        $refSheet->fromArray([$refHeaders], null, 'A1');

        $kelasList = Kelas::orderBy('nama_kelas')->pluck('nama_kelas')->toArray();
        $jkList = ['L (Laki-laki)', 'P (Perempuan)'];
        $agamaList = ['Kristen Protestan', 'Katolik', 'Islam', 'Hindu', 'Buddha', 'Konghucu'];

        $kategoriList = MasterKategoriSiswa::pluck('nama_kategori')->toArray();
        if (!in_array('Reguler', $kategoriList)) {
            array_unshift($kategoriList, 'Reguler');
        }

        $sumberBiayaList = MasterSumberBiaya::pluck('nama_sumber_biaya')->toArray();
        if (empty($sumberBiayaList)) {
            $sumberBiayaList = ['Orangtua Kandung', 'Wali', 'Beasiswa', 'Pihak Lainnya'];
        }

        $pekerjaanList = MasterPekerjaan::pluck('nama_pekerjaan')->toArray();
        $penghasilanList = MasterPenghasilan::pluck('range_penghasilan')->toArray();

        $maxRows = max(
            count($kelasList),
            count($jkList),
            count($agamaList),
            count($kategoriList),
            count($sumberBiayaList),
            count($pekerjaanList),
            count($penghasilanList)
        );

        $refData = [];
        for ($r = 0; $r < $maxRows; $r++) {
            $refData[] = [
                $kelasList[$r] ?? '',
                $jkList[$r] ?? '',
                $agamaList[$r] ?? '',
                $kategoriList[$r] ?? '',
                $sumberBiayaList[$r] ?? '',
                $pekerjaanList[$r] ?? '',
                $penghasilanList[$r] ?? '',
            ];
        }

        if (!empty($refData)) {
            $refSheet->fromArray($refData, null, 'A2');
        }

        $refLastCol = Coordinate::stringFromColumnIndex(count($refHeaders));
        $refTotalRows = $maxRows + 1;

        // Styling Sheet Referensi
        $refSheet->getStyle("A1:{$refLastCol}1")->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFFFF'));
        $refSheet->getStyle("A1:{$refLastCol}1")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF1E293B');
        $refSheet->getStyle("A1:{$refLastCol}1")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $refSheet->getStyle("A1:{$refLastCol}1")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $refSheet->getRowDimension(1)->setRowHeight(30);

        if ($refTotalRows >= 2) {
            $refSheet->getStyle("A1:{$refLastCol}{$refTotalRows}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        }

        foreach (range(1, count($refHeaders)) as $i) {
            $refSheet->getColumnDimension(Coordinate::stringFromColumnIndex($i))->setAutoSize(true);
        }
        $refSheet->freezePane('A2');

        // Pastikan saat spreadsheet dibuka, tab aktif adalah Data Siswa
        $spreadsheet->setActiveSheetIndex(0);

        $cleanSchool = preg_replace('/[^A-Za-z0-9_]/', '_', $schoolName);
        $filename = "Template_Import_Siswa_{$cleanSchool}.xlsx";

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Memproses file Excel yang diunggah untuk mengimpor atau memperbarui data siswa
     */
    public function import(UploadedFile $file, string $mode = 'merge', ?int $defaultKelasId = null): array
    {
        $activeTa = AppSetting::get('active_tahun_ajaran', date('Y') . '/' . (date('Y') + 1));
        $activeSem = AppSetting::get('active_semester', 'Ganjil');

        $kelasMap = [];
        foreach (Kelas::all() as $k) {
            $key = strtolower(trim($k->nama_kelas));
            $kelasMap[$key] = $k->id;
        }

        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheets = $spreadsheet->getAllSheets();

        $headerMapping = [
            'kelas' => 'rombel',
            'rombel' => 'rombel',
            'nama_siswa' => 'nama_siswa',
            'nama_lengkap' => 'nama_siswa',
            'nama' => 'nama_siswa',
            'jk' => 'jk',
            'jenis_kelamin' => 'jk',
            'agama' => 'agama',
            'tempat_lahir' => 'tempat_lahir',
            'tanggal_lahir' => 'tanggal_lahir',
            'ttl' => 'ttl',
            'alamat' => 'alamat',
            'alamat_domisili' => 'alamat',
            'rt' => 'rt',
            'rw' => 'rw',
            'dusun' => 'dusun',
            'kelurahan' => 'kelurahan',
            'desa' => 'kelurahan',
            'sumber_biaya_sekolah' => 'sumber_biaya',
            'sumber_biaya' => 'sumber_biaya',
            'kategori_siswa' => 'kategori_siswa',
            'nis' => 'nis',
            'nipd' => 'nis',
            'nisn' => 'nisn',
            'nik' => 'nik',
            'no_kk' => 'no_kk',

            // Ortu & Wali direct
            'nama_ayah' => 'nama_ayah',
            'pekerjaan_ayah' => 'pekerjaan_ayah',
            'penghasilan_ayah' => 'penghasilan_ayah',
            'penghasilan_rata_rata_bulan_ayah' => 'penghasilan_ayah',
            'alamat_ayah' => 'alamat_ayah',
            'alamat_domisili_ayah' => 'alamat_ayah',

            'nama_ibu' => 'nama_ibu',
            'pekerjaan_ibu' => 'pekerjaan_ibu',
            'penghasilan_ibu' => 'penghasilan_ibu',
            'penghasilan_rata_rata_bulan_ibu' => 'penghasilan_ibu',
            'alamat_ibu' => 'alamat_ibu',
            'alamat_domisili_ibu' => 'alamat_ibu',

            'jumlah_tanggungan_ortu' => 'jml_tanggungan_ortu',
            'tanggungan_ortu' => 'jml_tanggungan_ortu',

            'nama_wali_laki_laki' => 'nama_wali_l',
            'nama_wali_l' => 'nama_wali_l',
            'pekerjaan_wali_laki_laki' => 'pekerjaan_wali_l',
            'pekerjaan_wali_l' => 'pekerjaan_wali_l',
            'penghasilan_wali_laki_laki' => 'penghasilan_wali_l',
            'penghasilan_wali_l' => 'penghasilan_wali_l',
            'alamat_wali_laki_laki' => 'alamat_wali_l',
            'alamat_wali_l' => 'alamat_wali_l',

            'nama_wali_perempuan' => 'nama_wali_p',
            'nama_wali_p' => 'nama_wali_p',
            'pekerjaan_wali_perempuan' => 'pekerjaan_wali_p',
            'pekerjaan_wali_p' => 'pekerjaan_wali_p',
            'penghasilan_wali_perempuan' => 'penghasilan_wali_p',
            'penghasilan_wali_p' => 'penghasilan_wali_p',
            'alamat_wali_perempuan' => 'alamat_wali_p',
            'alamat_wali_p' => 'alamat_wali_p',

            'jumlah_tanggungan_wali' => 'jml_tanggungan_wali',
            'tanggungan_wali' => 'jml_tanggungan_wali',
        ];

        $norm = function ($x) {
            $x = str_replace(["\n", "\r"], ' ', (string)$x);
            $x = preg_replace('/[^a-z0-9]+/', '_', strtolower(trim($x)));
            return trim($x, '_');
        };

        $imported = 0;
        $updated = 0;
        $skipped = 0;
        $failed = 0;
        $errors = [];
        $skippedInfo = [];
        $totalProcessed = 0;

        // Pelacak duplikat internal di dalam file Excel yang diunggah
        $seenNis = [];
        $seenNisn = [];
        $seenNik = [];
        $seenNamaTgl = [];

        foreach ($sheets as $sheet) {
            // Lewati tab Referensi / Petunjuk / Panduan agar tidak diproses sebagai baris data siswa
            $sheetOriginalTitle = $sheet->getTitle();
            $sheetTitleLower = strtolower(trim($sheetOriginalTitle));
            if (
                str_contains($sheetTitleLower, 'referensi') ||
                str_contains($sheetTitleLower, 'panduan') ||
                str_contains($sheetTitleLower, 'petunjuk') ||
                str_contains($sheetTitleLower, 'master')
            ) {
                continue;
            }

            $rowsArr = $sheet->toArray(null, true, false, true);
            if (empty($rowsArr)) continue;

            $foundHeaderRow = 1;
            $headers = [];
            $groupHeaders = [];

            // Cari baris header yang memuat kolom penanda data siswa
            foreach ($rowsArr as $rIdx => $r) {
                $rNorm = array_map($norm, array_values($r));
                if (
                    in_array('nama_siswa', $rNorm) ||
                    in_array('nama_lengkap', $rNorm) ||
                    in_array('nama', $rNorm) ||
                    in_array('nis', $rNorm) ||
                    in_array('nipd', $rNorm)
                ) {
                    $headers = $rNorm;
                    $foundHeaderRow = $rIdx;
                    if ($rIdx > 1 && isset($rowsArr[$rIdx - 1])) {
                        $groupHeaders = array_values($rowsArr[$rIdx - 1]);
                    }
                    break;
                }
            }

            if (empty($headers)) {
                $headers = array_map($norm, array_values(reset($rowsArr)));
                $foundHeaderRow = 1;
            }

            // Disambiguasi header berdasarkan grup baris 1 (Data Siswa, Ortu, Wali)
            if (!empty($groupHeaders)) {
                $currentGroup = '';
                foreach ($headers as $idx => $val) {
                    if (isset($groupHeaders[$idx]) && !empty(trim((string)$groupHeaders[$idx]))) {
                        $g = strtolower(trim((string)$groupHeaders[$idx]));
                        if (str_contains($g, 'siswa')) {
                            $currentGroup = 'siswa';
                        } elseif (str_contains($g, 'orangtua') || str_contains($g, 'ortu')) {
                            $currentGroup = 'ortu';
                        } elseif (str_contains($g, 'wali')) {
                            $currentGroup = 'wali';
                        }
                    }

                    if ($currentGroup === 'ortu') {
                        // Kolom Ayah: indeks 14-17 (O-R)
                        if ($idx >= 14 && $idx <= 17) {
                            $headers[$idx] = 'ayah_' . $val;
                        } elseif ($idx >= 18 && $idx <= 21) {
                            // Kolom Ibu: indeks 18-21 (S-V)
                            $headers[$idx] = 'ibu_' . $val;
                        } elseif ($idx == 22 || str_contains($val, 'tanggungan')) {
                            $headers[$idx] = 'jml_tanggungan_ortu';
                        }
                    } elseif ($currentGroup === 'wali') {
                        // Kolom Wali L: indeks 23-26 (X-AA)
                        if ($idx >= 23 && $idx <= 26) {
                            $headers[$idx] = 'wali_l_' . $val;
                        } elseif ($idx >= 27 && $idx <= 30) {
                            // Kolom Wali P: indeks 27-30 (AB-AE)
                            $headers[$idx] = 'wali_p_' . $val;
                        } elseif ($idx == 31 || str_contains($val, 'tanggungan')) {
                            $headers[$idx] = 'jml_tanggungan_wali';
                        }
                    }
                }
            }

            $colLetters = array_keys(reset($rowsArr));

            // Iterasi baris data dalam sheet
            for ($rowIdx = $foundHeaderRow + 1; $rowIdx <= count($rowsArr); $rowIdx++) {
                if (!isset($rowsArr[$rowIdx])) continue;
                $rowVals = array_values($rowsArr[$rowIdx]);

                // 1. Cek apakah seluruh baris benar-benar kosong
                $hasAnyValue = false;
                foreach ($rowVals as $cellVal) {
                    if ($cellVal !== null && trim((string)$cellVal) !== '') {
                        $hasAnyValue = true;
                        break;
                    }
                }
                if (!$hasAnyValue) {
                    continue; // baris kosong diabaikan tanpa error
                }

                // 2. Lewati baris contoh/sample jika ada
                $namaCheck = trim((string)($rowVals[2] ?? ($rowVals[1] ?? '')));
                if (stripos($namaCheck, 'Contoh Siswa') !== false) {
                    continue;
                }

                $totalProcessed++;
                $data = [];
                $rombel = '';
                $rowErrors = [];
                $rawTgl = null;
                $rawJk = null;

                foreach ($headers as $colIdx => $clean) {
                    $rawVal = $rowVals[$colIdx] ?? null;
                    if ($rawVal === null || trim((string)$rawVal) === '') continue;
                    $val = trim((string)$rawVal);

                    $colLetter = $colLetters[$colIdx] ?? Coordinate::stringFromColumnIndex($colIdx + 1);
                    $cell = null;
                    try {
                        $cell = $sheet->getCell($colLetter . $rowIdx);
                    } catch (\Throwable $e) {}

                    // Pemetaan nama kolom
                    if ($clean === 'ttl') {
                        [$tempat, $tanggal] = $this->parseTTL($val);
                        if ($tempat) $data['tempat_lahir'] = $tempat;
                        if ($tanggal) {
                            $data['tanggal_lahir'] = $tanggal;
                        } else {
                            $rowErrors[] = "Format tanggal lahir pada TTL '{$val}' tidak dapat dipahami.";
                        }
                    } elseif ($clean === 'tempat_lahir') {
                        $data['tempat_lahir'] = $val;
                    } elseif ($clean === 'tanggal_lahir') {
                        $rawTgl = $val;
                        $parsedDate = $this->parseExcelDate($rawVal, $cell);
                        if ($parsedDate) {
                            $data['tanggal_lahir'] = $parsedDate;
                        } else {
                            $rowErrors[] = "Format tanggal lahir '{$val}' tidak valid (gunakan format YYYY-MM-DD atau DD/MM/YYYY).";
                        }
                    } elseif ($clean === 'jk' || $clean === 'jenis_kelamin') {
                        $rawJk = $val;
                    } elseif ($clean === 'ayah_nama_ayah' || $clean === 'nama_ayah') {
                        $data['nama_ayah'] = $val;
                    } elseif ($clean === 'ayah_pekerjaan' || $clean === 'pekerjaan_ayah') {
                        $data['pekerjaan_ayah'] = $val;
                    } elseif (str_contains($clean, 'ayah') && str_contains($clean, 'penghasilan')) {
                        $data['penghasilan_ayah'] = $val;
                    } elseif (str_contains($clean, 'ayah') && (str_contains($clean, 'alamat') || str_contains($clean, 'domisili'))) {
                        $data['alamat_ayah'] = $val;
                    } elseif ($clean === 'ibu_nama_ibu' || $clean === 'nama_ibu') {
                        $data['nama_ibu'] = $val;
                    } elseif ($clean === 'ibu_pekerjaan' || $clean === 'pekerjaan_ibu') {
                        $data['pekerjaan_ibu'] = $val;
                    } elseif (str_contains($clean, 'ibu') && str_contains($clean, 'penghasilan')) {
                        $data['penghasilan_ibu'] = $val;
                    } elseif (str_contains($clean, 'ibu') && (str_contains($clean, 'alamat') || str_contains($clean, 'domisili'))) {
                        $data['alamat_ibu'] = $val;
                    } elseif (str_contains($clean, 'wali_l') && str_contains($clean, 'nama')) {
                        $data['nama_wali_l'] = $val;
                    } elseif (str_contains($clean, 'wali_l') && str_contains($clean, 'pekerjaan')) {
                        $data['pekerjaan_wali_l'] = $val;
                    } elseif (str_contains($clean, 'wali_l') && str_contains($clean, 'penghasilan')) {
                        $data['penghasilan_wali_l'] = $val;
                    } elseif (str_contains($clean, 'wali_l') && (str_contains($clean, 'alamat') || str_contains($clean, 'domisili'))) {
                        $data['alamat_wali_l'] = $val;
                    } elseif (str_contains($clean, 'wali_p') && str_contains($clean, 'nama')) {
                        $data['nama_wali_p'] = $val;
                    } elseif (str_contains($clean, 'wali_p') && str_contains($clean, 'pekerjaan')) {
                        $data['pekerjaan_wali_p'] = $val;
                    } elseif (str_contains($clean, 'wali_p') && str_contains($clean, 'penghasilan')) {
                        $data['penghasilan_wali_p'] = $val;
                    } elseif (str_contains($clean, 'wali_p') && (str_contains($clean, 'alamat') || str_contains($clean, 'domisili'))) {
                        $data['alamat_wali_p'] = $val;
                    } elseif ($clean === 'jml_tanggungan_ortu' || (str_contains($clean, 'tanggungan') && str_contains($clean, 'ortu'))) {
                        $cleanNum = preg_replace('/[^0-9]/', '', (string)$val);
                        $data['jml_tanggungan_ortu'] = $cleanNum !== '' ? (int)$cleanNum : null;
                    } elseif ($clean === 'jml_tanggungan_wali' || (str_contains($clean, 'tanggungan') && str_contains($clean, 'wali'))) {
                        $cleanNum = preg_replace('/[^0-9]/', '', (string)$val);
                        $data['jml_tanggungan_wali'] = $cleanNum !== '' ? (int)$cleanNum : null;
                    } elseif (isset($headerMapping[$clean])) {
                        $target = $headerMapping[$clean];
                        if ($target === 'rombel') {
                            $rombel = $val;
                        } else {
                            $data[$target] = $val;
                        }
                    }
                }

                // 3. Validasi Nama Siswa (Wajib diisi)
                $nama = trim((string)($data['nama_siswa'] ?? ''));
                if ($nama === '') {
                    $errors[] = "Baris {$rowIdx} [Sheet '{$sheetOriginalTitle}']: Kolom Nama Siswa kosong (wajib diisi).";
                    $failed++;
                    continue;
                }
                $data['nama_siswa'] = $nama;

                // 4. Validasi & Normalisasi Jenis Kelamin (JK)
                if ($rawJk !== null) {
                    $jkLower = strtolower(trim($rawJk));
                    if ($jkLower === 'l' || str_starts_with($jkLower, 'laki') || $jkLower === 'pria' || $jkLower === '1') {
                        $data['jk'] = 'L';
                    } elseif ($jkLower === 'p' || str_starts_with($jkLower, 'peremp') || $jkLower === 'wanita' || $jkLower === '2') {
                        $data['jk'] = 'P';
                    } else {
                        $rowErrors[] = "Jenis Kelamin '{$rawJk}' tidak valid (hanya boleh 'L' atau 'P').";
                    }
                } else {
                    $data['jk'] = 'L'; // Nilai default jika kosong
                }

                // 5. Bersihkan NIS, NISN, NIK
                if (isset($data['nis'])) {
                    $data['nis'] = trim(preg_replace('/[^0-9A-Za-z]/', '', (string)$data['nis']));
                }
                if (isset($data['nisn'])) {
                    $data['nisn'] = trim(preg_replace('/[^0-9]/', '', (string)$data['nisn']));
                }
                if (isset($data['nik'])) {
                    $data['nik'] = trim(preg_replace('/[^0-9]/', '', (string)$data['nik']));
                }

                // 6. Deteksi Duplikasi Internal di dalam File Excel yang Sama
                if (!empty($data['nis'])) {
                    if (isset($seenNis[$data['nis']])) {
                        $rowErrors[] = "Duplikat di file Excel: NIS '{$data['nis']}' sudah tercantum di Baris {$seenNis[$data['nis']]}.";
                    } else {
                        $seenNis[$data['nis']] = $rowIdx;
                    }
                }

                if (!empty($data['nisn'])) {
                    if (isset($seenNisn[$data['nisn']])) {
                        $rowErrors[] = "Duplikat di file Excel: NISN '{$data['nisn']}' sudah tercantum di Baris {$seenNisn[$data['nisn']]}.";
                    } else {
                        $seenNisn[$data['nisn']] = $rowIdx;
                    }
                }

                if (!empty($data['nik'])) {
                    if (isset($seenNik[$data['nik']])) {
                        $rowErrors[] = "Duplikat di file Excel: NIK '{$data['nik']}' sudah tercantum di Baris {$seenNik[$data['nik']]}.";
                    } else {
                        $seenNik[$data['nik']] = $rowIdx;
                    }
                }

                if (!empty($data['nama_siswa']) && !empty($data['tanggal_lahir'])) {
                    $namaTglKey = strtolower($data['nama_siswa']) . '|' . $data['tanggal_lahir'];
                    if (isset($seenNamaTgl[$namaTglKey])) {
                        $rowErrors[] = "Duplikat di file Excel: Siswa '{$data['nama_siswa']}' dengan Tanggal Lahir sama sudah tercantum di Baris {$seenNamaTgl[$namaTglKey]}.";
                    } else {
                        $seenNamaTgl[$namaTglKey] = $rowIdx;
                    }
                }

                // 7. Jika ada kesalahan validasi pada baris ini, catat dan lewati
                if (!empty($rowErrors)) {
                    foreach ($rowErrors as $err) {
                        $errors[] = "Baris {$rowIdx} [Sheet '{$sheetOriginalTitle}', Siswa: {$nama}]: {$err}";
                    }
                    $failed++;
                    continue;
                }

                // 8. Normalisasi Kelas
                if (!empty($rombel)) {
                    $key = strtolower(trim($rombel));
                    if (isset($kelasMap[$key])) {
                        $data['kelas_id'] = $kelasMap[$key];
                    } else {
                        $tingkat = '10';
                        if (preg_match('/\b(xii|12)\b/i', $rombel)) {
                            $tingkat = '12';
                        } elseif (preg_match('/\b(xi|11)\b/i', $rombel)) {
                            $tingkat = '11';
                        }

                        $newKelas = Kelas::create([
                            'nama_kelas' => trim($rombel),
                            'tingkat' => $tingkat,
                        ]);
                        $kelasMap[$key] = $newKelas->id;
                        $data['kelas_id'] = $newKelas->id;
                    }
                } elseif (!isset($data['kelas_id']) && $defaultKelasId) {
                    $data['kelas_id'] = $defaultKelasId;
                }

                if (empty($data['kelas_id'])) {
                    $defaultK = Kelas::first();
                    if ($defaultK) {
                        $data['kelas_id'] = $defaultK->id;
                    } else {
                        $newK = Kelas::create(['nama_kelas' => 'X-1', 'tingkat' => '10']);
                        $data['kelas_id'] = $newK->id;
                    }
                }

                // Bersihkan RT / RW bila ada huruf
                if (isset($data['rt'])) {
                    $rtClean = preg_replace('/[^0-9]/', '', (string)$data['rt']);
                    if ($rtClean !== '') $data['rt'] = $rtClean;
                }
                if (isset($data['rw'])) {
                    $rwClean = preg_replace('/[^0-9]/', '', (string)$data['rw']);
                    if ($rwClean !== '') $data['rw'] = $rwClean;
                }

                // 9. Set default Tahun Ajaran & Semester berjalan agar konsisten dengan Dashboard
                if (empty($data['tahun_ajaran'])) $data['tahun_ajaran'] = $activeTa;
                if (empty($data['semester'])) $data['semester'] = $activeSem;
                if (empty($data['status'])) $data['status'] = 'Aktif';

                // 10. DETEKSI SISWA DUPLIKAT TERHADAP BASIS DATA
                $exists = null;
                $matchedBy = '';

                // Cek NIS
                if (!empty($data['nis'])) {
                    $exists = Siswa::where('nis', $data['nis'])->first();
                    if ($exists) $matchedBy = "NIS: {$data['nis']}";
                }
                // Cek NISN
                if (!$exists && !empty($data['nisn'])) {
                    $exists = Siswa::where('nisn', $data['nisn'])->first();
                    if ($exists) $matchedBy = "NISN: {$data['nisn']}";
                }
                // Cek NIK
                if (!$exists && !empty($data['nik']) && \Illuminate\Support\Facades\Schema::hasColumn('siswa', 'nik')) {
                    $exists = Siswa::where('nik', $data['nik'])->first();
                    if ($exists) $matchedBy = "NIK: {$data['nik']}";
                }
                // Cek Nama Lengkap + Tanggal Lahir
                if (!$exists && !empty($data['nama_siswa']) && !empty($data['tanggal_lahir'])) {
                    $q = Siswa::where('nama_siswa', $data['nama_siswa'])
                              ->where('tanggal_lahir', $data['tanggal_lahir']);
                    if (!empty($data['tempat_lahir'])) {
                        $q->where(function ($sub) use ($data) {
                            $sub->whereNull('tempat_lahir')
                                ->orWhere('tempat_lahir', '')
                                ->orWhere('tempat_lahir', $data['tempat_lahir']);
                        });
                    }
                    $exists = $q->first();
                    if ($exists) $matchedBy = "Nama & Tgl Lahir: {$data['nama_siswa']} ({$data['tanggal_lahir']})";
                }

                try {
                    if ($exists) {
                        if ($mode === 'skip') {
                            $skipped++;
                            $skippedInfo[] = "Baris {$rowIdx} (Siswa: {$nama}): Siswa sudah terdaftar di database ({$matchedBy}). Dilewati sesuai mode 'Lewati duplikat'.";
                        } elseif ($mode === 'overwrite') {
                            $updateData = [];
                            foreach ($data as $k => $v) {
                                if ($v !== null && $v !== '') {
                                    $updateData[$k] = $v;
                                }
                            }
                            if (!empty($updateData)) {
                                $exists->update($updateData);
                                $updated++;
                            } else {
                                $skipped++;
                            }
                        } else {
                            // Mode merge: Lengkapi yang kosong pada data database
                            $updateData = [];
                            foreach ($data as $k => $v) {
                                if ($v === null || $v === '') continue;
                                if (empty($exists->{$k})) {
                                    $updateData[$k] = $v;
                                }
                            }
                            if (!empty($updateData)) {
                                $exists->update($updateData);
                                $updated++;
                            } else {
                                $skipped++;
                                $skippedInfo[] = "Baris {$rowIdx} (Siswa: {$nama}): Data siswa sudah lengkap di database ({$matchedBy}), tidak ada data baru yang perlu digabungkan.";
                            }
                        }
                    } else {
                        // Tambah Siswa Baru
                        Siswa::create($data);
                        $imported++;
                    }
                } catch (\Throwable $e) {
                    $failed++;
                    $errors[] = "Baris {$rowIdx} [Sheet '{$sheetOriginalTitle}', Siswa: {$nama}]: Gagal menyimpan ke basis data: " . $e->getMessage();
                }
            }
        }

        if ($totalProcessed === 0 && empty($errors)) {
            return [
                'status' => 'empty',
                'total_rows' => 0,
                'imported' => 0,
                'updated' => 0,
                'skipped' => 0,
                'failed' => 0,
                'errors' => ['Tidak ada baris data siswa yang ditemukan dalam file Excel. Pastikan file berisi baris data pada sheet yang sesuai.'],
                'skipped_info' => [],
            ];
        }

        ActivityLog::record(
            'import',
            'siswa',
            "Mengimpor data siswa via Excel: {$imported} baru, {$updated} diperbarui, {$skipped} duplikat dilewati, {$failed} gagal."
        );

        $status = ($failed === 0 && ($imported + $updated) > 0)
            ? 'success'
            : (($imported + $updated > 0) ? 'warning' : ($failed > 0 ? 'error' : 'empty'));

        return [
            'status' => $status,
            'total_rows' => $totalProcessed,
            'imported' => $imported,
            'updated' => $updated,
            'skipped' => $skipped,
            'failed' => $failed,
            'errors' => $errors,
            'skipped_info' => $skippedInfo,
        ];
    }

    /**
     * Export data siswa ke file Excel
     */
    public function export($siswaList, string $filename = 'Data_Siswa.xlsx'): StreamedResponse
    {
        $schoolName = AppSetting::get('school_name', 'SMAN Benlutu');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Siswa');

        // Group Headers
        $sheet->mergeCells('A1:N1');
        $sheet->setCellValue('A1', 'Data Siswa');

        $sheet->mergeCells('O1:W1');
        $sheet->setCellValue('O1', 'Data Orangtua Kandung');

        $sheet->mergeCells('X1:AF1');
        $sheet->setCellValue('X1', 'Data Wali');

        $headers = [
            'No',
            'Kelas',
            'Nama Siswa',
            'JK',
            'Agama',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Alamat',
            'RT',
            'RW',
            'Dusun',
            'Kelurahan',
            'Sumber Biaya Sekolah',
            'Kategori Siswa',
            'Nama Ayah',
            'Pekerjaan',
            'Penghasilan rata-rata/ bulan',
            'Alamat Domisili',
            'Nama Ibu',
            'Pekerjaan',
            'Penghasilan rata-rata/ bulan',
            'Alamat Domisili',
            'Jumlah tanggungan anak yang bersekolah di ' . $schoolName,
            'Nama Wali Laki-laki',
            'Pekerjaan',
            'Penghasilan rata-rata/ bulan',
            'Alamat Domisili',
            'Nama Wali Perempuan',
            'Pekerjaan',
            'Penghasilan rata-rata/ bulan',
            'Alamat Domisili',
            'Jumlah tanggungan anak yang bersekolah di ' . $schoolName
        ];

        $sheet->fromArray([$headers], null, 'A2');

        $rows = [];
        foreach ($siswaList as $idx => $s) {
            $rows[] = [
                ($idx + 1),
                $s->kelas->nama_kelas ?? '-',
                $s->nama_siswa,
                $s->jk,
                $s->agama ?? '',
                $s->tempat_lahir ?? '',
                $s->tanggal_lahir ?? '',
                $s->alamat ?? '',
                $s->rt ?? '',
                $s->rw ?? '',
                $s->dusun ?? '',
                $s->kelurahan ?? '',
                $s->sumber_biaya ?? '',
                $s->kategori_siswa ?? '',
                $s->nama_ayah ?? '',
                $s->pekerjaan_ayah ?? '',
                $s->penghasilan_ayah ?? '',
                $s->alamat_ayah ?? '',
                $s->nama_ibu ?? '',
                $s->pekerjaan_ibu ?? '',
                $s->penghasilan_ibu ?? '',
                $s->alamat_ibu ?? '',
                $s->jml_tanggungan_ortu ?? '',
                $s->nama_wali_l ?? '',
                $s->pekerjaan_wali_l ?? '',
                $s->penghasilan_wali_l ?? '',
                $s->alamat_wali_l ?? '',
                $s->nama_wali_p ?? '',
                $s->pekerjaan_wali_p ?? '',
                $s->penghasilan_wali_p ?? '',
                $s->alamat_wali_p ?? '',
                $s->jml_tanggungan_wali ?? ''
            ];
        }

        if (!empty($rows)) {
            $sheet->fromArray($rows, null, 'A3');
        }

        $lastCol = Coordinate::stringFromColumnIndex(count($headers));
        $totalRows = count($rows) + 2;

        // Styling
        $sheet->getStyle("A1:{$lastCol}2")->getFont()->setBold(true);
        $sheet->getStyle("A1:{$lastCol}1")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A1:{$lastCol}2")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A1:{$lastCol}2")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle("A2:{$lastCol}2")->getAlignment()->setWrapText(true);

        $sheet->getStyle("A1:{$lastCol}{$totalRows}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Header Background Colors
        $sheet->getStyle('A1:N2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF0F0F0');
        $sheet->getStyle('O1:W2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFF9C4');
        $sheet->getStyle("X1:{$lastCol}2")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFE1F5FE');

        $sheet->getRowDimension(1)->setRowHeight(30);
        $sheet->getRowDimension(2)->setRowHeight(40);

        foreach (range(1, count($headers)) as $i) {
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($i))->setAutoSize(true);
        }

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Parse tanggal dari cell Excel atau berbagai format string Indonesia
     */
    protected function parseExcelDate($val, $cell = null): ?string
    {
        if (empty($val)) return null;

        if ($cell && ExcelDate::isDateTime($cell)) {
            try {
                return ExcelDate::excelToDateTimeObject($cell->getValue())->format('Y-m-d');
            } catch (\Throwable $e) {}
        }

        if (is_numeric($val) && $val > 1000 && $val < 100000) {
            try {
                return ExcelDate::excelToDateTimeObject((float)$val)->format('Y-m-d');
            } catch (\Throwable $e) {}
        }

        $str = trim((string)$val);
        if (empty($str)) return null;

        // Pemetaan nama bulan Indonesia ke Inggris
        $bulanIndo = [
            'januari' => 'january', 'pebruari' => 'february', 'februari' => 'february',
            'maret' => 'march', 'april' => 'april', 'mei' => 'may',
            'juni' => 'june', 'juli' => 'july', 'agustus' => 'august',
            'september' => 'september', 'oktober' => 'october', 'nopember' => 'november',
            'november' => 'november', 'desember' => 'december'
        ];
        $strLower = strtr(strtolower($str), $bulanIndo);

        // Format umum d/m/Y, d-m-Y, Y-m-d
        foreach (['d/m/Y', 'd-m-Y', 'Y-m-d', 'd F Y', 'd M Y'] as $fmt) {
            $d = \DateTime::createFromFormat($fmt, $strLower);
            if ($d && $d->format($fmt) === $strLower) {
                return $d->format('Y-m-d');
            }
        }

        $ts = strtotime($strLower);
        if ($ts && $ts > 0) {
            return date('Y-m-d', $ts);
        }

        return null;
    }

    /**
     * Memecah string Tempat, Tanggal Lahir (TTL)
     */
    protected function parseTTL(string $val): array
    {
        $val = trim($val);
        if ($val === '') return [null, null];

        $parts = preg_split('/[,\/]/', $val, 2);
        $tempat = trim($parts[0] ?? '');
        $tanggal = null;
        if (isset($parts[1]) && trim($parts[1]) !== '') {
            $tanggal = $this->parseExcelDate(trim($parts[1]));
        }
        return [$tempat ?: null, $tanggal];
    }
}
