<?php

namespace App\Http\Controllers\Pelatih;

use App\Http\Controllers\Controller;
use App\Models\Kelompok;
use App\Models\Presensi;
use App\Models\PresensiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PresensiController extends Controller
{
    public function index(Request $request)
    {
        $presensis = Presensi::with(['kelompok'])
            ->whereHas('kelompok', function ($query) {
                $query->where('pelatih_id', auth()->id());
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($subQuery) use ($request) {
                    $subQuery->where('judul_pertemuan', 'like', '%' . $request->search . '%')
                        ->orWhereHas('kelompok', function ($kelompokQuery) use ($request) {
                            $kelompokQuery->where('nama_kelompok', 'like', '%' . $request->search . '%');
                        });
                });
            })
            ->latest('tanggal_presensi')
            ->paginate(10);

        $kelompoks = Kelompok::where('pelatih_id', auth()->id())
            ->where('status_aktif', true)
            ->orderBy('level_urutan')
            ->orderBy('nama_kelompok')
            ->get();

        return view('pelatih.presensi.index', compact('presensis', 'kelompoks'));
    }

    public function store(Request $request, Kelompok $kelompok)
    {
        $this->authorizeKelompok($kelompok);
        $presensi = $this->createPresensi($request, $kelompok);
        if (!$presensi instanceof Presensi) {
            return $presensi;
        }

        return redirect()->route('pelatih.presensi.show', $presensi)->with('success', 'Sesi presensi berhasil dibuat.');
    }

    public function storeFromIndex(Request $request)
    {
        $validated = $request->validate([
            'kelompok_id' => 'required|exists:kelompoks,id',
        ]);

        $kelompok = Kelompok::findOrFail($validated['kelompok_id']);
        $this->authorizeKelompok($kelompok);
        $presensi = $this->createPresensi($request, $kelompok);
        if (!$presensi instanceof Presensi) {
            return $presensi;
        }

        return redirect()->route('pelatih.presensi.show', $presensi)->with('success', 'Sesi presensi berhasil dibuat.');
    }

    private function createPresensi(Request $request, Kelompok $kelompok)
    {
        $validated = $request->validate([
            'tanggal_presensi' => 'required|date',
            'judul_pertemuan' => 'nullable|string|max:255',
            'materi' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        $anggotaAktif = $kelompok->anggota()->where('status', 'aktif')->get();

        if ($this->hasActiveLibur($kelompok, $validated['tanggal_presensi'])) {
            return back()->with('error', 'Tanggal ini sudah ditandai libur untuk kelompok tersebut. Presensi tidak dapat dibuat.');
        }

        if ($anggotaAktif->isEmpty()) {
            return back()->with('error', 'Kelompok ini belum memiliki anggota aktif untuk dipresensikan.');
        }

        return DB::transaction(function () use ($validated, $kelompok, $anggotaAktif) {
            $presensi = Presensi::create([
                'kelompok_id' => $kelompok->id,
                'tanggal_presensi' => $validated['tanggal_presensi'],
                'judul_pertemuan' => $validated['judul_pertemuan'] ?? null,
                'materi' => $validated['materi'] ?? null,
                'catatan' => $validated['catatan'] ?? null,
                'dibuat_oleh' => auth()->id(),
            ]);

            foreach ($anggotaAktif as $anggota) {
                PresensiDetail::create([
                    'presensi_id' => $presensi->id,
                    'peserta_id' => $anggota->peserta_id,
                    'status_kehadiran' => 'hadir',
                ]);
            }

            return $presensi;
        });
    }

    public function show(Presensi $presensi)
    {
        $this->authorizePresensi($presensi);

        $presensi->load(['kelompok', 'details.peserta']);

        return view('pelatih.presensi.show', compact('presensi'));
    }

    public function update(Request $request, Presensi $presensi)
    {
        $this->authorizePresensi($presensi);

        $validated = $request->validate([
            'tanggal_presensi' => 'required|date',
            'judul_pertemuan' => 'nullable|string|max:255',
            'materi' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        if ($this->hasActiveLibur($presensi->kelompok, $validated['tanggal_presensi'])) {
            return back()->with('error', 'Tanggal ini sudah ditandai libur untuk kelompok tersebut. Presensi tidak dapat diperbarui.');
        }

        $presensi->update($validated);

        return redirect()->route('pelatih.presensi.show', $presensi)->with('success', 'Informasi presensi berhasil diperbarui.');
    }

    private function authorizeKelompok(Kelompok $kelompok): void
    {
        abort_unless($kelompok->pelatih_id === auth()->id(), 403, 'Anda tidak berhak mengakses kelompok ini.');
    }

    private function authorizePresensi(Presensi $presensi): void
    {
        $presensi->loadMissing('kelompok');
        $this->authorizeKelompok($presensi->kelompok);
    }

    private function hasActiveLibur(Kelompok $kelompok, string $tanggal): bool
    {
        return $kelompok->liburAktifs()
            ->whereDate('tanggal', $tanggal)
            ->exists();
    }
}
