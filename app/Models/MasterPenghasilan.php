<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterPenghasilan extends Model
{
    protected $table = 'master_penghasilan';
    protected $primaryKey = 'id_penghasilan';
    public $timestamps = false;
    protected $fillable = ['range_penghasilan'];
}