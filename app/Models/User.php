<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'username',
        'password',
        'nama',
        'email',
        'level',
        'kelas_id',
        'gtk_id',
        'is_active',
        'last_login',
        'photo'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_login' => 'datetime',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function gtk()
    {
        return $this->belongsTo(Gtk::class, 'gtk_id');
    }

    public function isAdmin()
    {
        return strtolower($this->level) === 'admin';
    }

    public function isWali()
    {
        return in_array(strtolower($this->level), ['wali', 'walikelas', 'wali kelas']);
    }

    public function getRoleNameAttribute()
    {
        if ($this->isAdmin()) {
            return 'Administrator';
        }
        if ($this->isWali()) {
            return 'Wali Kelas';
        }
        return ucfirst($this->level ?? 'Pengguna');
    }

    public function getPhotoUrlAttribute()
    {
        if (!empty($this->photo)) {
            $paths = [
                'uploads/profiles/' . $this->photo,
                'assets/uploads/profiles/' . $this->photo,
                'uploads/profile/' . $this->photo,
                'uploads/' . $this->photo,
            ];

            foreach ($paths as $p) {
                if (file_exists(public_path($p))) {
                    return asset($p);
                }
            }
        }

        // Fallback to linked GTK photo if available
        if ($this->gtk && !empty($this->gtk->foto)) {
            $gtkPaths = [
                'uploads/gtk/' . $this->gtk->foto,
                'assets/uploads/gtk/' . $this->gtk->foto,
            ];
            foreach ($gtkPaths as $gp) {
                if (file_exists(public_path($gp))) {
                    return asset($gp);
                }
            }
        }

        return null;
    }

    public function getEffectiveKelasId()
    {
        if ($this->kelas_id) {
            return $this->kelas_id;
        }
        if ($this->gtk_id) {
            $wk = Walikelas::where('id_gtk', $this->gtk_id)->orderBy('id_walikelas', 'desc')->first();
            if ($wk && $wk->id_kelas) {
                return $wk->id_kelas;
            }
        }
        return null;
    }

    public function effectiveKelas()
    {
        $id = $this->getEffectiveKelasId();
        return $id ? Kelas::find($id) : null;
    }
}