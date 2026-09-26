<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'username',
        'activity_type',
        'module',
        'description',
        'ip_address',
        'user_agent',
        'created_at'
    ];

    public static function record($type, $module, $desc = null)
    {
        $user = auth()->user();
        $userId = $user ? $user->id : (\App\Models\User::value('id') ?? 1);
        $username = $user ? $user->username : 'system';

        return static::create([
            'user_id' => $userId,
            'username' => $username,
            'activity_type' => $type,
            'module' => $module,
            'description' => $desc,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }
}