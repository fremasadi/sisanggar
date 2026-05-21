<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelompok;
use App\Models\User;
use Illuminate\Http\Request;

class KelompokController extends Controller
{
    public function index(Request $request)
    {
        $kelompoks = Kelompok::with('pelatih')
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('nama_kelompok', 'like', '%' . $request->search . '%');
            })
            ->orderBy('level_urutan')
            ->paginate(10);

        return view('admin.kelompok.index', compact('kelompoks'));
    }

    public function create()
    {
        $pelatihs = User::where('role', 'pelatih')->where('status_aktif', true)->orderBy('name')->get();

        return view('admin.kelompok.create', compact('pelatihs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kelompok' => 'required|string|max:255',
            'jalur_tingkatan' => 'nullable|string|max:255',
            'tingkat_nomor' => 'nullable|integer|min:1',
            'level_urutan' => 'required|integer|min:1',
            'pelatih_id' => 'nullable|exists:users,id',
            'deskripsi' => 'nullable|string',
            'status_aktif' => 'boolean',
        ]);

        $validated['status_aktif'] = $request->boolean('status_aktif');
        Kelompok::create($validated);

        return redirect()->route('admin.kelompok.index')->with('success', 'Kelompok berhasil ditambahkan.');
    }

    public function show(Kelompok $kelompok)
    {
        $kelompok->load([
            'pelatih',
            'anggota.peserta',
        ]);

        $pesertas = User::where('role', 'peserta')->where('status_aktif', true)->orderBy('name')->get();
        $nextKelompok = $kelompok->nextKelompokInTrack();

        return view('admin.kelompok.show', compact('kelompok', 'pesertas', 'nextKelompok'));
    }

    public function edit(Kelompok $kelompok)
    {
        $pelatihs = User::where('role', 'pelatih')->where('status_aktif', true)->orderBy('name')->get();

        return view('admin.kelompok.edit', compact('kelompok', 'pelatihs'));
    }

    public function update(Request $request, Kelompok $kelompok)
    {
        $validated = $request->validate([
            'nama_kelompok' => 'required|string|max:255',
            'jalur_tingkatan' => 'nullable|string|max:255',
            'tingkat_nomor' => 'nullable|integer|min:1',
            'level_urutan' => 'required|integer|min:1',
            'pelatih_id' => 'nullable|exists:users,id',
            'deskripsi' => 'nullable|string',
            'status_aktif' => 'boolean',
        ]);

        $validated['status_aktif'] = $request->boolean('status_aktif');
        $kelompok->update($validated);

        return redirect()->route('admin.kelompok.show', $kelompok)->with('success', 'Kelompok berhasil diperbarui.');
    }

    public function destroy(Kelompok $kelompok)
    {
        $kelompok->delete();

        return redirect()->route('admin.kelompok.index')->with('success', 'Kelompok berhasil dihapus.');
    }
}
