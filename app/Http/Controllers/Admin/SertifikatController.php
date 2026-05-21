<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sertifikat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SertifikatController extends Controller
{
    public function index(Request $request)
    {
        $sertifikats = Sertifikat::with(['peserta', 'uploader'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($subQuery) use ($request) {
                    $subQuery->where('nama_sertifikat', 'like', '%' . $request->search . '%')
                        ->orWhereHas('peserta', function ($pesertaQuery) use ($request) {
                            $pesertaQuery->where('name', 'like', '%' . $request->search . '%')
                                ->orWhere('email', 'like', '%' . $request->search . '%');
                        });
                });
            })
            ->when($request->filled('peserta_id'), function ($query) use ($request) {
                $query->where('peserta_id', $request->peserta_id);
            })
            ->latest()
            ->paginate(10);

        $pesertas = User::where('role', 'peserta')
            ->where('status_aktif', true)
            ->orderBy('name')
            ->get();

        return view('admin.sertifikat.index', compact('sertifikats', 'pesertas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'peserta_id' => 'required|exists:users,id',
            'nama_sertifikat' => 'required|string|max:255',
            'tanggal_terbit' => 'nullable|date',
            'file_sertifikat' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
            'catatan' => 'nullable|string',
        ]);

        $peserta = User::where('role', 'peserta')->findOrFail($validated['peserta_id']);
        $file = $request->file('file_sertifikat');
        $path = $file->store('sertifikat', 'local');

        Sertifikat::create([
            'peserta_id' => $peserta->id,
            'uploaded_by' => auth()->id(),
            'nama_sertifikat' => $validated['nama_sertifikat'],
            'tanggal_terbit' => $validated['tanggal_terbit'] ?? null,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'catatan' => $validated['catatan'] ?? null,
        ]);

        return redirect()->route('admin.sertifikat.index')->with('success', 'Sertifikat berhasil diupload.');
    }

    public function download(Sertifikat $sertifikat)
    {
        abort_unless(Storage::disk('local')->exists($sertifikat->file_path), 404, 'File sertifikat tidak ditemukan.');

        return Storage::disk('local')->download($sertifikat->file_path, $sertifikat->file_name);
    }

    public function destroy(Sertifikat $sertifikat)
    {
        Storage::disk('local')->delete($sertifikat->file_path);
        $sertifikat->delete();

        return back()->with('success', 'Sertifikat berhasil dihapus.');
    }
}
