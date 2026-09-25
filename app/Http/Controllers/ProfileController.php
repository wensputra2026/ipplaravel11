<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;
use App\Services\FileCompressionService;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user) {
            $user->loadMissing(['kelas', 'gtk']);
        }

        return view('profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'nullable|email|max:100',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
            'remove_photo' => 'nullable|boolean',
            'current_password' => 'nullable|string',
            'password' => 'nullable|string|min:4|confirmed',
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'foto.image' => 'Berkas foto harus berupa gambar valid.',
            'foto.mimes' => 'Format foto yang diizinkan: JPG, JPEG, PNG, WEBP.',
            'foto.max' => 'Ukuran berkas foto maksimal 5MB (akan otomatis dikompres oleh sistem).',
            'password.min' => 'Kata sandi baru minimal 4 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        $user->nama = $validated['nama'];
        $user->email = $validated['email'];

        // Handle delete photo request
        if ($request->boolean('remove_photo')) {
            if ($user->photo && file_exists(public_path('uploads/profiles/' . $user->photo))) {
                @unlink(public_path('uploads/profiles/' . $user->photo));
            }
            $user->photo = null;
        }

        // Handle new photo upload with compression
        if ($request->hasFile('foto')) {
            $dest = public_path('uploads/profiles');
            if (!is_dir($dest)) {
                mkdir($dest, 0777, true);
            }

            // Remove old photo if exists
            if ($user->photo && file_exists($dest . '/' . $user->photo)) {
                @unlink($dest . '/' . $user->photo);
            }

            // Compress & upload image (max 600x600 px, quality 82)
            $filename = FileCompressionService::compressAndUploadImage(
                $request->file('foto'),
                $dest,
                'profile_' . $user->id,
                600,
                600,
                82
            );

            $user->photo = $filename;
        }

        // Handle password change
        if (!empty($validated['password'])) {
            if (empty($validated['current_password']) || !password_verify($validated['current_password'], $user->password)) {
                return back()->withInput()->with('error', 'Kata sandi saat ini tidak cocok.');
            }
            $user->password = bcrypt($validated['password']);
        }

        $user->save();

        ActivityLog::record('profile_update', 'profile', "Pengguna {$user->username} memperbarui data profil akun");

        return back()->with('success', 'Profil akun dan foto Anda berhasil diperbarui!');
    }
}