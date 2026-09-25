<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterPekerjaan extends Model
{
    protected $table = 'master_pekerjaan';
    protected $primaryKey = 'id_pekerjaan';
    public $timestamps = false;
    protected $fillable = ['nama_pekerjaan'];
}