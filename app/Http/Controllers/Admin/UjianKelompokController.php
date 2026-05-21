<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HasilUjianKelompok;
use App\Models\Kelompok;
use App\Models\KelompokPeserta;
use App\Models\UjianKelompok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UjianKelompokController extends Controller
{
    public function index(Request $request)
    {
        $ujians = UjianKelompok::with(['kelompok', 'kelompokTujuan'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($subQuery) use ($request) {
                    $subQuery->where('nama_ujian', 'like', '%' . $request->search . '%')
                        ->orWhereHas('kelompok', function ($kelompokQuery) use ($request) {
                            $kelompokQuery->where('nama_kelompok', 'like', '%' . $request->search . '%');
                        });
                });
            })
            ->when($request->filled('kelompok_id'), function ($query) use ($request) {
                $query->where('kelompok_id', $request->kelompok_id);
            })
            ->latest('tanggal_ujian')
            ->paginate(10);

        $kelompoks = Kelompok::where('status_aktif', true)->orderBy('level_urutan')->orderBy('nama_kelompok')->get();

        return view('admin.ujian-kelompok.index', compact('ujians', 'kelompoks'));
    }

    public function store(Request $request, Kelompok $kelompok)
    {
        $ujian = $this->createUjian($request, $kelompok);
        if (!$ujian instanceof UjianKelompok) {
            return $ujian;
        }

        return redirect()->route('admin.ujian-kelompok.show', $ujian)->with('success', 'Ujian kelompok berhasil dibuat.');
    }

    public function storeFromIndex(Request $request)
    {
        $validated = $request->validate([
            'kelompok_id' => 'required|exists:kelompoks,id',
        ]);

        $kelompok = Kelompok::findOrFail($validated['kelompok_id']);
        $ujian = $this->createUjian($request, $kelompok);
        if (!$ujian instanceof UjianKelompok) {
            return $ujian;
        }

        return redirect()->route('admin.ujian-kelompok.show', $ujian)->with('success', 'Ujian kelompok berhasil dibuat.');
    }

    private function createUjian(Request $request, Kelompok $kelompok)
    {
        $validated = $request->validate([
            'nama_ujian' => 'required|string|max:255',
            'tanggal_ujian' => 'required|date',
            'jam_mulai' => 'nullable',
            'lokasi' => 'nullable|string|max:255',
            'status' => 'required|in:draft,dibuka,selesai',
            'kelompok_tujuan_id' => 'nullable|exists:kelompoks,id',
            'keterangan' => 'nullable|string',
        ]);

        $nextKelompok = $kelompok->nextKelompokInTrack();

        if ($nextKelompok) {
            if ((int) $validated['kelompok_tujuan_id'] !== (int) $nextKelompok->id) {
                return back()->withInput()->with(
                    'error',
                    'Ujian kenaikan untuk ' . $kelompok->label_tingkatan . ' hanya bisa menuju ' . $nextKelompok->label_tingkatan . '.'
                );
            }
        }

        return DB::transaction(function () use ($kelompok, $validated) {
            $ujian = $kelompok->ujians()->create($validated);

            $anggotaAktif = $kelompok->anggota()->where('status', 'aktif')->get();
            foreach ($anggotaAktif as $anggota) {
                HasilUjianKelompok::create([
                    'ujian_kelompok_id' => $ujian->id,
                    'peserta_id' => $anggota->peserta_id,
                    'hasil' => 'menunggu',
                ]);
            }

            return $ujian;
        });
    }

    public function show(UjianKelompok $ujian)
    {
        $ujian->load([
            'kelompok',
            'kelompokTujuan',
            'hasils.peserta',
        ]);

        return view('admin.ujian-kelompok.show', compact('ujian'));
    }

    public function promote(UjianKelompok $ujian)
    {
        if (!$ujian->kelompok_tujuan_id) {
            return back()->with('error', 'Kelompok tujuan belum dipilih.');
        }

        DB::transaction(function () use ($ujian) {
            $ujian->load('hasils');

            foreach ($ujian->hasils as $hasil) {
                if ($hasil->hasil !== 'lulus' || $hasil->promoted_at) {
                    continue;
                }

                KelompokPeserta::where('peserta_id', $hasil->peserta_id)
                    ->where('kelompok_id', $ujian->kelompok_id)
                    ->where('status', 'aktif')
                    ->update(['status' => 'lulus']);

                KelompokPeserta::create([
                    'kelompok_id' => $ujian->kelompok_tujuan_id,
                    'peserta_id' => $hasil->peserta_id,
                    'tanggal_masuk' => now()->toDateString(),
                    'status' => 'aktif',
                    'catatan' => 'Promosi dari ujian ' . $ujian->nama_ujian,
                ]);

                $hasil->update(['promoted_at' => now()]);
            }

            $ujian->update(['status' => 'selesai']);
        });

        return back()->with('success', 'Proses kenaikan tingkat peserta selesai.');
    }
}
