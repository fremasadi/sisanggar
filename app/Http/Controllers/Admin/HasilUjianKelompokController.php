<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HasilUjianKelompok;
use App\Models\UjianKelompok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HasilUjianKelompokController extends Controller
{
    public function update(Request $request, HasilUjianKelompok $hasil)
    {
        $validated = $request->validate([
            'hasil' => 'required|in:menunggu,lulus,mengulang,tidak_lulus',
            'catatan' => 'nullable|string',
        ]);

        // Cegah pengguna memasukkan nilai jika statusnya 'menunggu'
        if ($request->hasil === 'menunggu' && $request->filled('nilai')) {
            return back()->with('error', 'Untuk status Menunggu, nilai harus dikosongkan.');
        }

        // Tentukan aturan validasi
        $aturanNilai = 'nullable';
        if ($request->hasil === 'lulus') {
            $aturanNilai = 'required|integer|min:72|max:100';
        } elseif ($request->hasil === 'mengulang') {
            $aturanNilai = 'required|integer|min:60|max:71';
        } elseif ($request->hasil === 'tidak_lulus') {
            $aturanNilai = 'required|integer|min:0|max:59';
        }

        $validatedNilai = $request->validate([
            'nilai' => $aturanNilai,
        ], [
            'nilai.required' => 'Nilai wajib diisi untuk status ujian yang dipilih.',
            'nilai.min' => 'Angka yang dimasukkan kurang dari batas minimal status terpilih.',
            'nilai.max' => 'Angka yang dimasukkan melebihi batas maksimal status terpilih.',
        ]);

        // Paksa nilai menjadi null jika statusnya menunggu (untuk keamanan ganda)
        if ($request->hasil === 'menunggu') {
            $validatedNilai['nilai'] = null;
        }

        // Simpan data
        $hasil->update(array_merge($validated, $validatedNilai));

        return back()->with('success', 'Hasil ujian berhasil diperbarui.');
    }

    public function updateBulk(Request $request, UjianKelompok $ujian)
    {
        $request->validate([
            'hasils'                => 'required|array',
            'hasils.*.id'           => 'required|integer|exists:hasil_ujian_kelompoks,id',
            'hasils.*.hasil'        => 'required|in:menunggu,lulus,mengulang,tidak_lulus',
            'hasils.*.nilai'        => 'nullable|integer|min:0|max:100',
            'hasils.*.catatan'      => 'nullable|string',
        ]);

        $errors = [];

        DB::transaction(function () use ($request, &$errors) {
            foreach ($request->hasils as $index => $data) {
                $hasil = HasilUjianKelompok::find($data['id']);

                $statusHasil = $data['hasil'];
                $nilai = isset($data['nilai']) && $data['nilai'] !== '' ? (int) $data['nilai'] : null;
                $catatan = $data['catatan'] ?? null;

                // Validasi aturan nilai berdasarkan status
                if ($statusHasil === 'lulus' && ($nilai === null || $nilai < 72 || $nilai > 100)) {
                    $errors[] = "Peserta #{$hasil->peserta->name}: Untuk status Lulus, nilai harus 72–100.";
                    continue;
                } elseif ($statusHasil === 'mengulang' && ($nilai === null || $nilai < 60 || $nilai > 71)) {
                    $errors[] = "Peserta #{$hasil->peserta->name}: Untuk status Mengulang, nilai harus 60–71.";
                    continue;
                } elseif ($statusHasil === 'tidak_lulus' && ($nilai === null || $nilai < 0 || $nilai > 59)) {
                    $errors[] = "Peserta #{$hasil->peserta->name}: Untuk status Tidak Lulus, nilai harus 0–59.";
                    continue;
                } elseif ($statusHasil === 'menunggu') {
                    $nilai = null;
                }

                $hasil->update([
                    'hasil'   => $statusHasil,
                    'nilai'   => $nilai,
                    'catatan' => $catatan,
                ]);
            }
        });

        if (!empty($errors)) {
            return back()->with('error', implode('<br>', $errors));
        }

        return back()->with('success', 'Semua hasil ujian berhasil disimpan.');
    }
}