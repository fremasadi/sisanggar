<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-gray-800 mb-0">{{ $ujian->nama_ujian }}</h1>
            <small class="text-muted">{{ $ujian->kelompok->nama_kelompok }} - {{ $ujian->tanggal_ujian->format('d/m/Y') }}</small>
        </div>
        <a href="{{ route('pelatih.ujian.index') }}" class="btn btn-secondary">Kembali</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4"><strong>Lokasi:</strong> {{ $ujian->lokasi ?: '-' }}</div>
                <div class="col-md-4"><strong>Status:</strong> {{ ucfirst($ujian->status) }}</div>
                <div class="col-md-4"><strong>Target:</strong> {{ $ujian->kelompokTujuan->nama_kelompok ?? '-' }}</div>
            </div>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-success text-white">Input Nilai Peserta</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Peserta</th>
                            <th>Hasil</th>
                            <th>Nilai</th>
                            <th>Catatan</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ujian->hasils as $hasil)
                            <tr>
                                <td>{{ $hasil->peserta->name ?? '-' }}</td>
                                <td>
                                    <select name="hasil" form="pelatih-hasil-ujian-{{ $hasil->id }}" class="form-select form-select-sm">
                                        @foreach(['menunggu', 'lulus', 'tidak_lulus'] as $status)
                                            <option value="{{ $status }}" {{ $hasil->hasil === $status ? 'selected' : '' }}>
                                                {{ ucfirst($status) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" min="0" max="100" name="nilai" value="{{ $hasil->nilai }}" form="pelatih-hasil-ujian-{{ $hasil->id }}" class="form-control form-control-sm">
                                </td>
                                <td>
                                    <input type="text" name="catatan" value="{{ $hasil->catatan }}" form="pelatih-hasil-ujian-{{ $hasil->id }}" class="form-control form-control-sm">
                                </td>
                                <td>
                                    <form id="pelatih-hasil-ujian-{{ $hasil->id }}" action="{{ route('pelatih.hasil-ujian.update', $hasil) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn btn-sm btn-success w-100">Simpan</button>
                                    </form>
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
</x-app-layout>
