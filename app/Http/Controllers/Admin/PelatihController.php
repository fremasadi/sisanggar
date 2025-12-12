<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelatih;
use App\Models\User;
use Illuminate\Http\Request;

class PelatihController extends Controller
{
    /**
     * Tampilkan daftar pelatih
     */
    public function index()
    {
    $pelatihs = \App\Models\Pelatih::with('user')->latest()->paginate(10);
        return view('admin.pelatih.index', compact('pelatihs'));
    }

    /**
     * Form tambah pelatih
     */
    public function create()
    {
        $users = User::where('role', 'pelatih')->get();
        return view('admin.pelatih.create', compact('users'));
    }

    /**
     * Simpan data pelatih baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pelatih' => 'required|exists:users,id|unique:pelatihs,id_pelatih',
            'bidang_tari' => 'required|string|max:255',
            'jadwal_tetap' => 'nullable|string',
        ]);

        Pelatih::create($validated);

        return redirect()->route('admin.pelatih.index')->with('success', 'Data pelatih berhasil ditambahkan.');
    }

    /**
     * Form edit pelatih
     */
    public function edit(Pelatih $pelatih)
    {
        $users = User::where('role', 'pelatih')->get();
        return view('admin.pelatih.edit', compact('pelatih', 'users'));
    }

    /**
     * Update data pelatih
     */
    public function update(Request $request, Pelatih $pelatih)
    {
        $validated = $request->validate([
            'id_pelatih' => 'required|exists:users,id|unique:pelatihs,id_pelatih,' . $pelatih->id_pelatih . ',id_pelatih',
            'bidang_tari' => 'required|string|max:255',
            'jadwal_tetap' => 'nullable|string',
        ]);

        $pelatih->update($validated);

        return redirect()->route('admin.pelatih.index')->with('success', 'Data pelatih berhasil diperbarui.');
    }

    /**
     * Hapus data pelatih
     */
    public function destroy(Pelatih $pelatih)
    {
        $pelatih->delete();
        return redirect()->route('admin.pelatih.index')->with('success', 'Data pelatih berhasil dihapus.');
    }
}
