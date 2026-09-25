<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterKategoriSiswa extends Model
{
    protected $table = 'master_kategori_siswa';
    protected $primaryKey = 'id_kategori';
    public $timestamps = false;
    protected $fillable = ['nama_kategori'];
}