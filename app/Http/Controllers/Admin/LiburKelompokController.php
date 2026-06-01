<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelompok;
use App\Models\LiburKelompok;
use App\Notifications\LiburKelompokNotification;
use App\Services\FonnteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class LiburKelompokController extends Controller
{
    public function index(Request $request)
    {
        $liburs = LiburKelompok::with(['kelompok', 'jadwal', 'pembuat'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($subQuery) use ($request) {
                    $subQuery->where('judul', 'like', '%' . $request->search . '%')
                        ->orWhere('alasan', 'like', '%' . $request->search . '%')
                        ->orWhereHas('kelompok', function ($kelompokQuery) use ($request) {
                            $kelompokQuery->where('nama_kelompok', 'like', '%' . $request->search . '%');
                        });
                });
            })
            ->when($request->filled('kelompok_id'), fn ($query) => $query->where('kelompok_id', $request->kelompok_id))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->latest('tanggal')
            ->paginate(10)
            ->withQueryString();

        $kelompoks = Kelompok::with('jadwals')
            ->where('status_aktif', true)
            ->orderBy('level_urutan')
            ->orderBy('nama_kelompok')
            ->get();

        return view('admin.libur-kelompok.index', compact('liburs', 'kelompoks'));
    }

    public function store(Request $request, FonnteService $fonnte)
    {
        $validated = $request->validate([
            'kelompok_ids' => 'required|array|min:1',
            'kelompok_ids.*' => 'exists:kelompoks,id',
            'tanggal' => 'required|date',
            'judul' => 'required|string|max:255',
            'alasan' => 'nullable|string',
        ]);

        $created = 0;
        $skipped = 0;

        foreach (array_unique($validated['kelompok_ids']) as $kelompokId) {
            $alreadyExists = LiburKelompok::where('kelompok_id', $kelompokId)
                ->whereDate('tanggal', $validated['tanggal'])
                ->where('status', 'aktif')
                ->whereNull('jadwal_kelompok_id')
                ->exists();

            if ($alreadyExists) {
                $skipped++;
                continue;
            }

            $libur = LiburKelompok::create([
                'kelompok_id' => $kelompokId,
                'tanggal' => $validated['tanggal'],
                'judul' => $validated['judul'],
                'alasan' => $validated['alasan'] ?? null,
                'created_by' => auth()->id(),
            ]);

            $this->notifyRecipients($libur, $fonnte);
            $created++;
        }

        return redirect()->route('admin.libur-kelompok.index')
            ->with('success', "Libur berhasil dibuat untuk {$created} kelompok dan notifikasi dikirim. {$skipped} kelompok dilewati karena sudah punya libur aktif di tanggal tersebut.");
    }

    public function destroy(LiburKelompok $liburKelompok)
    {
        $liburKelompok->update(['status' => 'dibatalkan']);

        return back()->with('success', 'Libur kelompok berhasil dibatalkan.');
    }

    private function notifyRecipients(LiburKelompok $libur, FonnteService $fonnte): void
    {
        $libur->load([
            'kelompok.pelatih',
            'kelompok.anggota' => fn ($query) => $query->where('status', 'aktif')->with('peserta'),
            'jadwal',
        ]);

        $recipients = collect([$libur->kelompok?->pelatih])
            ->merge($libur->kelompok?->anggota?->pluck('peserta') ?? collect())
            ->filter()
            ->unique('id')
            ->values();

        if ($recipients->isEmpty()) {
            return;
        }

        $notification = new LiburKelompokNotification($libur);
        Notification::send($recipients, $notification);

        $fonnte->send(
            $recipients->pluck('no_hp')->all(),
            $notification->message()
        );
    }
}
