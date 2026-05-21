<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-gray-800 mb-0">Kelompok Binaan Saya</h1>
            <small class="text-muted">Lihat peserta, jadwal, presensi, dan ujian pada kelompok yang Anda latih.</small>
        </div>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Cari Kelompok</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari kelompok...">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-danger w-100">Filter</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('pelatih.kelompok.index') }}" class="btn btn-secondary w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        @forelse($kelompoks as $kelompok)
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card shadow h-100 border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <div class="text-xs text-uppercase text-danger font-weight-bold mb-1">Kelompok</div>
                                <h5 class="mb-1">{{ $kelompok->nama_kelompok }}</h5>
                                <small class="text-muted">{{ $kelompok->label_tingkatan }}</small>
                            </div>
                            <span class="badge bg-{{ $kelompok->status_aktif ? 'success' : 'secondary' }}">
                                {{ $kelompok->status_aktif ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>

                        <div class="row text-center g-2 mb-3">
                            <div class="col-3">
                                <div class="border rounded py-2">
                                    <div class="small text-muted">Peserta</div>
                                    <div class="font-weight-bold">{{ $kelompok->anggota_count }}</div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="border rounded py-2">
                                    <div class="small text-muted">Jadwal</div>
                                    <div class="font-weight-bold">{{ $kelompok->jadwals_count }}</div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="border rounded py-2">
                                    <div class="small text-muted">Ujian</div>
                                    <div class="font-weight-bold">{{ $kelompok->ujians_count }}</div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="border rounded py-2">
                                    <div class="small text-muted">Presensi</div>
                                    <div class="font-weight-bold">{{ $kelompok->presensis_count }}</div>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('pelatih.kelompok.show', $kelompok) }}" class="btn btn-danger w-100">Buka Kelompok</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">Belum ada kelompok yang ditugaskan untuk Anda.</div>
            </div>
        @endforelse
    </div>

    {{ $kelompoks->links() }}
</x-app-layout>
