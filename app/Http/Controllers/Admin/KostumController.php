<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kostum;
use Illuminate\Http\Request;

class KostumController extends Controller
{
    // INDEX
    public function index(Request $request)
    {
        $query = Kostum::query();

        // Filter pencarian
        if ($request->search) {
            $query->where('nama_kostum', 'like', '%' . $request->search . '%')
                ->orWhere('ukuran', 'like', '%' . $request->search . '%');
        }

        // Filter status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $kostums = $query->paginate(10);

        return view('admin.kostum.index', compact('kostums'));
    }

    // CREATE
    public function create()
    {
        return view('admin.kostum.create');
    }

    // STORE
    public function store(Request $request)
{
    $request->validate([
        'nama_kostum' => 'required|string|max:255',
        'ukuran' => 'required|string|max:50',
        'harga_sewa' => 'required|numeric|min:0',
        'stok' => 'required|integer|min:0',
        'status' => 'required|in:tersedia,tidak',
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $data = $request->all();

    // Upload image
    if ($request->hasFile('image')) {

    $filename = time() . '.' . $request->image->extension();

    // Simpan langsung ke public/kostum
    $request->image->move(public_path('kostum'), $filename);

    $data['image'] = $filename;
}


    Kostum::create($data);

    return redirect()->route('admin.kostum.index')
        ->with('success', 'Kostum berhasil ditambahkan!');
}


    // EDIT
    public function edit(Kostum $kostum)
    {
        return view('admin.kostum.edit', compact('kostum'));
    }

    // UPDATE
   public function update(Request $request, Kostum $kostum)
{
    $request->validate([
        'nama_kostum' => 'required|string|max:255',
        'ukuran' => 'required|string|max:50',
        'harga_sewa' => 'required|numeric|min:0',
        'stok' => 'required|integer|min:0',
        'status' => 'required|in:tersedia,tidak',
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $data = $request->all();

    // Upload image baru jika ada
   if ($request->hasFile('image')) {

    // Hapus file lama
    if ($kostum->image && file_exists(public_path('kostum/' . $kostum->image))) {
        unlink(public_path('kostum/' . $kostum->image));
    }

    $filename = time() . '.' . $request->image->extension();
    $request->image->move(public_path('kostum'), $filename);

    $data['image'] = $filename;
}


    $kostum->update($data);

    return redirect()->route('admin.kostum.index')
        ->with('success', 'Kostum berhasil diperbarui!');
}


    // DELETE
    public function destroy(Kostum $kostum)
    {
        $kostum->delete();

        return redirect()->route('admin.kostum.index')
            ->with('success', 'Kostum berhasil dihapus!');
    }
}
