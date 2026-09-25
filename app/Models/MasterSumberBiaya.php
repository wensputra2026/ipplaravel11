<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterSumberBiaya extends Model
{
    protected $table = 'master_sumber_biaya';
    protected $primaryKey = 'id_sumber_biaya';
    public $timestamps = false;
    protected $fillable = ['nama_sumber_biaya'];
}