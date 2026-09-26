<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';
    protected $guarded = ['id'];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function rombels()
    {
        return $this->hasMany(SiswaRombel::class, 'siswa_id');
    }

    public function rombelInPeriod($ta = null, $semester = null)
    {
        $ta = $ta ?: AppSetting::get('active_tahun_ajaran', '2026/2027');
        $semester = $semester ?: AppSetting::get('active_semester', 'Ganjil');
        return $this->rombels()->where('tahun_ajaran', $ta)->where('semester', $semester)->first();
    }

    public function getKelasNamaForPeriod($ta = null, $semester = null)
    {
        $ta = $ta ?: AppSetting::get('active_tahun_ajaran');
        $semester = $semester ?: AppSetting::get('active_semester');

        if ($ta) {
            $rombel = $this->relationLoaded('rombels')
                ? $this->rombels->first(function($r) use ($ta, $semester) {
                    return $r->tahun_ajaran == $ta && (!$semester || $r->semester == $semester);
                })
                : $this->rombelInPeriod($ta, $semester);

            if ($rombel && $rombel->kelas) {
                return $rombel->kelas->nama_kelas;
            }
        }
        return $this->kelas ? $this->kelas->nama_kelas : 'Belum ada';
    }

    public function scopeAktif($query)
    {
        return $query->where(function($q) {
            $q->whereNull('status')
              ->orWhere('status', 'Aktif')
              ->orWhere('status', '');
        });
    }

    public function scopeLulus($query)
    {
        return $query->where('status', 'Lulus');
    }

    public function getFotoUrlAttribute()
    {
        if (empty($this->foto)) return null;

        $paths = [
            'assets/uploads/siswa_foto/' . $this->foto,
            'uploads/siswa_foto/' . $this->foto,
            'assets/uploads/' . $this->foto,
            'uploads/siswa/' . $this->foto,
            'uploads/' . $this->foto,
        ];

        foreach ($paths as $p) {
            if (file_exists(public_path($p))) {
                return asset($p);
            }
        }

        return null;
    }

    public function getDokUrl($field)
    {
        $val = $this->{$field};
        if (empty($val)) return null;

        $paths = [
            'assets/uploads/siswa_dokumen/' . $val,
            'uploads/siswa_dokumen/' . $val,
            'assets/uploads/' . $val,
            'uploads/siswa/' . $val,
            'uploads/' . $val,
        ];

        foreach ($paths as $p) {
            if (file_exists(public_path($p))) {
                return asset($p);
            }
        }

        return null;
    }
}