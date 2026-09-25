<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';
    public $timestamps = false;
    protected $fillable = [
        'nama_kelas',
        'tingkat',
        'wali_kelas_id',
        'tahun_ajaran',
        'semester',
        'jurusan',
        'created_at'
    ];

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'kelas_id');
    }

    public function waliKelasRel()
    {
        return $this->hasOne(Walikelas::class, 'id_kelas');
    }

    public function waliGtk()
    {
        return $this->belongsTo(Gtk::class, 'wali_kelas_id');
    }
}