<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use App\Models\Kelas;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isWali = $user->isWali();
        $kelasId = $isWali ? $user->getEffectiveKelasId() : null;

        $baseQuery = Siswa::aktif();

        if ($isWali && $kelasId) {
            $baseQuery->where('kelas_id', $kelasId);
        } elseif ($request->filled('kelas_id')) {
            $baseQuery->where('kelas_id', $request->kelas_id);
        }

        $totalSiswa = (clone $baseQuery)->count();

        // Query for students with incomplete data
        $incompleteCondition = function($q) {
            $q->whereNull('nisn')->orWhere('nisn', '')
              ->orWhereNull('no_kk')->orWhere('no_kk', '')
              ->orWhereNull('foto')->orWhere('foto', '')
              ->orWhereNull('nama_ayah')->orWhere('nama_ayah', '')
              ->orWhereNull('nama_ibu')->orWhere('nama_ibu', '')
              ->orWhereNull('kategori_ipp')->orWhere('kategori_ipp', '');
        };

        $totalIncompleteCount = (clone $baseQuery)->where($incompleteCondition)->count();
        $lengkapCount = max(0, $totalSiswa - $totalIncompleteCount);
        $persenLengkap = $totalSiswa > 0 ? round(($lengkapCount / $totalSiswa) * 100, 1) : 100;

        $incompleteQuery = (clone $baseQuery)->with('kelas')->where($incompleteCondition);

        // Filter specific missing attribute
        if ($request->filled('missing')) {
            $m = $request->missing;
            if (in_array($m, ['nisn', 'no_kk', 'foto', 'nama_ayah', 'nama_ibu', 'kategori_ipp'])) {
                $incompleteQuery->where(function($q) use ($m) {
                    $q->whereNull($m)->orWhere($m, '');
                });
            }
        }

        // Search by student name or NIS
        if ($request->filled('search')) {
            $s = trim($request->search);
            $incompleteQuery->where(function($q) use ($s) {
                $q->where('nama_siswa', 'like', "%{$s}%")
                  ->orWhere('nis', 'like', "%{$s}%")
                  ->orWhere('nisn', 'like', "%{$s}%");
            });
        }

        // 10 items per page with query string
        $incompleteStudents = $incompleteQuery->orderBy('nama_siswa')->paginate(10)->withQueryString();

        $kelasList = Kelas::orderBy('nama_kelas')->get();

        return view('notifications.index', compact(
            'incompleteStudents',
            'totalSiswa',
            'lengkapCount',
            'totalIncompleteCount',
            'persenLengkap',
            'kelasList',
            'isWali',
            'kelasId'
        ));
    }
}