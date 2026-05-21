<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GaleriController extends Controller
{
    public function index(Request $request)
    {
        $galeris = Galeri::with('uploader')
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('judul', 'like', '%' . $request->search . '%');
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status_aktif', $request->status === 'aktif');
            })
            ->orderBy('urutan')
            ->latest()
            ->paginate(12);

        return view('admin.galeri.index', compact('galeris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'nullable|string|max:255',
            'urutan' => 'nullable|integer|min:0',
            'status_aktif' => 'nullable|boolean',
            'images' => 'required|array|min:1',
            'images.*' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        File::ensureDirectoryExists(public_path('galeri'));

        foreach ($request->file('images') as $image) {
            $filename = Str::uuid() . '.' . $image->extension();
            $image->move(public_path('galeri'), $filename);

            Galeri::create([
                'uploaded_by' => auth()->id(),
                'judul' => $validated['judul'] ?? null,
                'image' => $filename,
                'status_aktif' => $request->boolean('status_aktif', true),
                'urutan' => $validated['urutan'] ?? 0,
            ]);
        }

        return redirect()->route('admin.galeri.index')->with('success', 'Galeri berhasil diupload.');
    }

    public function update(Request $request, Galeri $galeri)
    {
        $validated = $request->validate([
            'judul' => 'nullable|string|max:255',
            'urutan' => 'nullable|integer|min:0',
            'status_aktif' => 'nullable|boolean',
        ]);

        $galeri->update([
            'judul' => $validated['judul'] ?? null,
            'urutan' => $validated['urutan'] ?? 0,
            'status_aktif' => $request->boolean('status_aktif'),
        ]);

        return back()->with('success', 'Galeri berhasil diperbarui.');
    }

    public function destroy(Galeri $galeri)
    {
        File::delete(public_path('galeri/' . $galeri->image));
        $galeri->delete();

        return back()->with('success', 'Galeri berhasil dihapus.');
    }
}
