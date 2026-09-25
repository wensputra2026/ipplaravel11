<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Gtk;
use App\Models\ActivityLog;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['kelas', 'gtk']);

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('username', 'like', "%{$s}%")
                  ->orWhere('nama', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }

        $users = $query->orderBy('level')->orderBy('nama')->paginate(10)->withQueryString();
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $gtkList = Gtk::orderBy('nama')->get();

        return view('account.index', compact('users', 'kelasList', 'gtkList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'nama' => 'required|string|max:100',
            'email' => 'nullable|email|max:100',
            'password' => 'required|string|min:4',
            'level' => 'required|in:admin,wali',
            'kelas_id' => 'nullable|exists:kelas,id',
            'gtk_id' => 'nullable|exists:gtk,id',
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'level' => $validated['level'],
            'kelas_id' => $validated['level'] === 'wali' ? $validated['kelas_id'] : null,
            'gtk_id' => $validated['gtk_id'],
            'is_active' => 1,
        ]);

        ActivityLog::record('create', 'account', "Menambahkan akun pengguna: {$user->username} ({$user->level})");

        return redirect()->route('account.index')->with('success', "Akun {$user->username} berhasil dibuat!");
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'nullable|email|max:100',
            'level' => 'required|in:admin,wali',
            'kelas_id' => 'nullable|exists:kelas,id',
            'gtk_id' => 'nullable|exists:gtk,id',
            'password' => 'nullable|string|min:4',
            'is_active' => 'required|boolean',
        ]);

        $updateData = [
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'level' => $validated['level'],
            'kelas_id' => $validated['level'] === 'wali' ? $validated['kelas_id'] : null,
            'gtk_id' => $validated['gtk_id'],
            'is_active' => $validated['is_active'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = bcrypt($validated['password']);
        }

        $user->update($updateData);

        ActivityLog::record('update', 'account', "Memperbarui akun pengguna: {$user->username}");

        return redirect()->route('account.index')->with('success', "Akun {$user->username} berhasil diperbarui!");
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $username = $user->username;
        $user->delete();

        ActivityLog::record('delete', 'account', "Menghapus akun pengguna: {$username}");

        return redirect()->route('account.index')->with('success', "Akun {$username} berhasil dihapus.");
    }

    public function resetPassword(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $newPassword = $request->input('password', '123456');

        if (strlen($newPassword) < 4) {
            return back()->with('error', 'Kata sandi minimal 4 karakter.');
        }

        $user->update([
            'password' => bcrypt($newPassword),
        ]);

        ActivityLog::record('update', 'account', "Mereset kata sandi akun pengguna: {$user->username}");

        return redirect()->route('account.index')->with('success', "Kata sandi akun {$user->username} berhasil direset menjadi: {$newPassword}");
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $statusStr = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        ActivityLog::record('update', 'account', "Mengubah status akun {$user->username} menjadi: {$statusStr}");

        return redirect()->route('account.index')->with('success', "Akun {$user->username} berhasil {$statusStr}!");
    }
}