<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Walikelas extends Model
{
    protected $table = 'walikelas';
    protected $primaryKey = 'id_walikelas';
    public $timestamps = false;
    protected $fillable = [
        'id_gtk',
        'id_kelas',
        'tahun_ajaran',
        'semester',
        'created_at'
    ];

    public function gtk()
    {
        return $this->belongsTo(Gtk::class, 'id_gtk');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }
}