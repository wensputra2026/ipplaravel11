<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiswaRombel extends Model
{
    use HasFactory;

    protected $table = 'siswa_rombel';

    protected $fillable = [
        'siswa_id',
        'kelas_id',
        'tahun_ajaran',
        'semester',
        'status',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function scopePeriod($query, $tahunAjaran, $semester = null)
    {
        $q = $query->where('tahun_ajaran', $tahunAjaran);
        if ($semester) {
            $q->where('semester', $semester);
        }
        return $q;
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'Aktif');
    }
}
