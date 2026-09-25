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