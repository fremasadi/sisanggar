<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Sertifikat;
use Illuminate\Support\Facades\Storage;

class SertifikatController extends Controller
{
    public function index()
    {
        $sertifikats = Sertifikat::where('peserta_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('peserta.sertifikat.index', compact('sertifikats'));
    }

    public function download(Sertifikat $sertifikat)
    {
        abort_unless((int) $sertifikat->peserta_id === (int) auth()->id(), 403, 'Anda tidak berhak mengakses sertifikat ini.');
        abort_unless(Storage::disk('local')->exists($sertifikat->file_path), 404, 'File sertifikat tidak ditemukan.');

        return Storage::disk('local')->download($sertifikat->file_path, $sertifikat->file_name);
    }
}
