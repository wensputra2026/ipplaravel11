<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gtk;
use App\Models\ActivityLog;
use App\Services\FileCompressionService;

class GtkController extends Controller
{
    public function index(Request $request)
    {
        $query = Gtk::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('nama', 'like', "%{$s}%")
                  ->orWhere('nip', 'like', "%{$s}%")
                  ->orWhere('nuptk', 'like', "%{$s}%");
            });
        }

        $gtkList = $query->orderBy('nama')->paginate(10)->withQueryString();

        return view('gtk.index', compact('gtkList'));
    }

    public function show($id)
    {
        $gtk = Gtk::findOrFail($id);
        return view('gtk.show', compact('gtk'));
    }

    public function create()
    {
        return view('gtk.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:150',
            'nip' => 'nullable|string|max:50',
            'nuptk' => 'nullable|string|max:50',
            'jk' => 'nullable|string|max:10',
            'foto' => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['_token', 'foto']);

        if ($request->hasFile('foto')) {
            $dest = public_path('uploads/gtk');
            $data['foto'] = FileCompressionService::compressAndUploadImage(
                $request->file('foto'),
                $dest,
                'gtk',
                800,
                1000,
                82
            );
        }

        $gtk = Gtk::create($data);

        ActivityLog::record('create', 'gtk', "Menambahkan GTK baru: {$gtk->nama}");

        return redirect()->route('gtk.index')->with('success', "Data GTK {$gtk->nama} berhasil ditambahkan!");
    }

    public function edit($id)
    {
        $gtk = Gtk::findOrFail($id);
        return view('gtk.edit', compact('gtk'));
    }

    public function update(Request $request, $id)
    {
        $gtk = Gtk::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:150',
            'nip' => 'nullable|string|max:50',
            'nuptk' => 'nullable|string|max:50',
            'jk' => 'nullable|string|max:10',
            'foto' => 'nullable|image|max:5120',
        ]);

        $data = $request->except(['_token', '_method', 'foto']);

        if ($request->hasFile('foto')) {
            $dest = public_path('uploads/gtk');
            if ($gtk->foto && file_exists($dest . '/' . $gtk->foto)) {
                @unlink($dest . '/' . $gtk->foto);
            }
            $data['foto'] = FileCompressionService::compressAndUploadImage(
                $request->file('foto'),
                $dest,
                'gtk',
                800,
                1000,
                82
            );
        }

        $gtk->update($data);

        ActivityLog::record('update', 'gtk', "Memperbarui data GTK: {$gtk->nama}");

        return redirect()->route('gtk.index')->with('success', "Data GTK {$gtk->nama} berhasil diperbarui!");
    }

    public function destroy($id)
    {
        $gtk = Gtk::findOrFail($id);
        $nama = $gtk->nama;
        $gtk->delete();

        ActivityLog::record('delete', 'gtk', "Menghapus GTK: {$nama}");

        return redirect()->route('gtk.index')->with('success', "Data GTK {$nama} berhasil dihapus.");
    }
}