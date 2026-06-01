<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">{{ $ujian->nama_ujian }}</h1>
            <small class="text-muted">{{ $ujian->kelompok->nama_kelompok }} - {{ $ujian->tanggal_ujian->format('d/m/Y') }}</small>
        </div>
        <a href="{{ route('admin.kelompok.show', $ujian->kelompok) }}" class="btn btn-secondary">Kembali</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4"><strong>Status:</strong> {{ ucfirst($ujian->status) }}</div>
                <div class="col-md-4"><strong>Lokasi:</strong> {{ $ujian->lokasi ?: '-' }}</div>
                <div class="col-md-4"><strong>Kelompok Tujuan:</strong> {{ $ujian->kelompokTujuan->nama_kelompok ?? '-' }}</div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">Hasil Ujian Peserta</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Peserta</th>
                            <th>Hasil</th>
                            <th>Nilai</th>
                            <th>Catatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ujian->hasils as $hasil)
                            <tr>
                                <td>{{ $hasil->peserta->name ?? '-' }}</td>
                                <td>
                                    <select name="hasil" form="hasil-form-{{ $hasil->id }}" class="form-select form-select-sm">
                                        @foreach(\App\Models\HasilUjianKelompok::HASIL_OPTIONS as $status => $label)
                                            <option value="{{ $status }}" {{ $hasil->hasil === $status ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" min="0" max="100" name="nilai" value="{{ $hasil->nilai }}" form="hasil-form-{{ $hasil->id }}" class="form-control form-control-sm">
                                </td>
                                <td>
                                    <input type="text" name="catatan" value="{{ $hasil->catatan }}" form="hasil-form-{{ $hasil->id }}" class="form-control form-control-sm">
                                </td>
                                <td>
                                    <form id="hasil-form-{{ $hasil->id }}" action="{{ route('admin.hasil-ujian-kelompok.update', $hasil) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn btn-sm btn-warning">Simpan</button>
                                    </form>
                                    <div class="small text-muted mt-1">{{ $hasil->promoted_at ? 'Sudah dipromosikan' : 'Belum dipromosikan' }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada peserta ujian.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('admin.ujian-kelompok.promote', $ujian) }}" method="POST">
                @csrf
                <button class="btn btn-success" {{ $ujian->kelompok_tujuan_id ? '' : 'disabled' }}>
                    Proses Kenaikan Tingkat
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
