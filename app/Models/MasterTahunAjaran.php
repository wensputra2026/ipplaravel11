<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterTahunAjaran extends Model
{
    protected $table = 'master_tahun_ajaran';
    protected $primaryKey = 'id_tahun_ajaran';
    public $timestamps = false;
    protected $fillable = ['tahun_ajaran'];
}