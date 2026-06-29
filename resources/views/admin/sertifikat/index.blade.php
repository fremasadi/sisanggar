<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-gray-800 mb-0">Sertifikat Peserta</h1>
            <small class="text-muted">Upload sertifikat untuk peserta dan kelola file yang sudah tersimpan.</small>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header bg-danger text-white">Upload Sertifikat</div>
        <div class="card-body">
            <form action="{{ route('admin.sertifikat.store') }}" method="POST" enctype="multipart/form-data" class="row g-2">
                @csrf
                <div class="col-md-3">
                    <label class="form-label">Peserta</label>
                    <select name="peserta_id" id="select-peserta" class="form-select" required>
                        <option value="">Pilih peserta</option>
                        @foreach($pesertas as $peserta)
                            <option value="{{ $peserta->id }}" {{ (string) old('peserta_id') === (string) $peserta->id ? 'selected' : '' }}>
                                {{ $peserta->name }} - {{ $peserta->email }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Nama Sertifikat</label>
                    <input type="text" name="nama_sertifikat" class="form-control" value="{{ old('nama_sertifikat') }}" placeholder="Contoh: Sertifikat Kelulusan" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tanggal Terbit</label>
                    <input type="date" name="tanggal_terbit" class="form-control" value="{{ old('tanggal_terbit') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">File Sertifikat</label>
                    <input type="file" name="file_sertifikat" class="form-control" accept="image/jpeg,image/png,image/webp" required>
                    <small class="text-muted">Format: JPG, PNG, WEBP. Maksimal 4 MB.</small>
                </div>
                <div class="col-md-9">
                    <label class="form-label">Catatan</label>
                    <input type="text" name="catatan" class="form-control" value="{{ old('catatan') }}" placeholder="Catatan opsional">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-danger w-100">Upload Sertifikat</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Cari Sertifikat / Peserta</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari sertifikat...">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Peserta</label>
                    <select name="peserta_id" id="select-filter-peserta" class="form-select">
                        <option value="">Semua Peserta</option>
                        @foreach($pesertas as $peserta)
                            <option value="{{ $peserta->id }}" {{ (string) request('peserta_id') === (string) $peserta->id ? 'selected' : '' }}>
                                {{ $peserta->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-danger w-100">Filter</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.sertifikat.index') }}" class="btn btn-secondary w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Nama Sertifikat</th>
                            <th>Peserta</th>
                            <th>Tanggal Terbit</th>
                            <th>File</th>
                            <th>Diupload Oleh</th>
                            <th width="170">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sertifikats as $sertifikat)
                            <tr>
                                <td>
                                    <strong>{{ $sertifikat->nama_sertifikat }}</strong>
                                    @if($sertifikat->catatan)
                                        <span class="d-block text-muted small">{{ $sertifikat->catatan }}</span>
                                    @endif
                                </td>
                                <td>{{ $sertifikat->peserta->name ?? '-' }}</td>
                                <td>{{ optional($sertifikat->tanggal_terbit)->format('d/m/Y') ?: '-' }}</td>
                                <td>{{ $sertifikat->file_name }}</td>
                                <td>{{ $sertifikat->uploader->name ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.sertifikat.download', $sertifikat) }}" class="btn btn-sm btn-info text-white">Download</a>
                                    <form action="{{ route('admin.sertifikat.destroy', $sertifikat) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus sertifikat ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Belum ada sertifikat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $sertifikats->links('pagination::bootstrap-5') }}
        </div>
    </div>

    @push('scripts')
    <!-- jQuery (dibutuhkan oleh Select2) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#select-peserta').select2({
                theme: 'bootstrap-5',
                placeholder: 'Pilih atau cari peserta...',
                width: '100%'
            });
            $('#select-filter-peserta').select2({
                theme: 'bootstrap-5',
                placeholder: 'Semua Peserta',
                width: '100%',
                allowClear: true
            });
        });
    </script>
    @endpush
</x-app-layout>
