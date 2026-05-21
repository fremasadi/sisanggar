<x-app-layout>
    <style>
        .participant-picker {
            border: 1px solid #e3e6f0;
            border-radius: 0.75rem;
            background: linear-gradient(180deg, #fff 0%, #fff5f5 100%);
            overflow: hidden;
        }

        .participant-picker__toolbar {
            padding: 1rem;
            border-bottom: 1px solid #f1f1f1;
            background: rgba(255, 255, 255, 0.85);
        }

        .participant-picker__list {
            max-height: 320px;
            overflow-y: auto;
            padding: 1rem;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 0.75rem;
        }

        .participant-card {
            position: relative;
            display: block;
            margin: 0;
            cursor: pointer;
        }

        .participant-card input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .participant-card__body {
            border: 1px solid #e3e6f0;
            border-radius: 0.9rem;
            background: #fff;
            padding: 0.9rem 1rem;
            min-height: 88px;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            transition: all 0.18s ease;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.04);
        }

        .participant-card__body:hover {
            border-color: #ea4335;
            transform: translateY(-1px);
            box-shadow: 0 10px 24px rgba(234, 67, 53, 0.12);
        }

        .participant-card input:checked + .participant-card__body {
            border-color: #dc3545;
            background: linear-gradient(135deg, #fff 0%, #ffe5e8 100%);
            box-shadow: 0 12px 28px rgba(220, 53, 69, 0.14);
        }

        .participant-card__avatar {
            width: 42px;
            height: 42px;
            border-radius: 999px;
            background: linear-gradient(135deg, #dc3545 0%, #f59e0b 100%);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            flex-shrink: 0;
        }

        .participant-card__check {
            margin-left: auto;
            width: 22px;
            height: 22px;
            border-radius: 999px;
            border: 1px solid #d1d3e2;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: transparent;
            background: #fff;
            transition: all 0.18s ease;
            flex-shrink: 0;
        }

        .participant-card input:checked + .participant-card__body .participant-card__check {
            background: #dc3545;
            border-color: #dc3545;
            color: #fff;
        }

        .participant-picker__empty {
            display: none;
            padding: 1rem;
            text-align: center;
            color: #858796;
        }
    </style>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">{{ $kelompok->nama_kelompok }}</h1>
            <small class="text-muted">
                Level {{ $kelompok->level_urutan }}
                @if($kelompok->jalur_tingkatan)
                    - Tingkatan: <strong>{{ $kelompok->label_tingkatan }}</strong>
                @endif
                - Pelatih: {{ $kelompok->pelatih->name ?? '-' }}
            </small>
        </div>
        <a href="{{ route('admin.kelompok.index') }}" class="btn btn-secondary">Kembali</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($kelompok->jalur_tingkatan && $kelompok->tingkat_nomor)
        <div class="alert alert-info">
            <strong>Skema Kenaikan Kelas:</strong>
            {{ $kelompok->label_tingkatan }}
            @if($nextKelompok)
                akan naik ke <strong>{{ $nextKelompok->label_tingkatan }}</strong>.
            @else
                belum memiliki kelompok tujuan berikutnya.
            @endif
        </div>
    @endif

    <div class="row">
        <div class="col-lg-7 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-danger text-white">Tambah Anggota</div>
                <div class="card-body">
                    <form action="{{ route('admin.kelompok-peserta.store', $kelompok) }}" method="POST" class="row g-2">
                        @csrf
                        <div class="col-md-12">
                            @php $selectedPesertaIds = collect(old('peserta_ids', []))->map(fn ($id) => (string) $id); @endphp

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label mb-0">Pilih Anggota</label>
                                <span class="badge bg-danger px-3 py-2" id="selected-participant-count">0 dipilih</span>
                            </div>

                            <div class="participant-picker">
                                <div class="participant-picker__toolbar">
                                    <div class="row g-2">
                                        <div class="col-md-7">
                                            <input type="text" id="participant-search" class="form-control" placeholder="Cari nama peserta...">
                                        </div>
                                        <div class="col-md-5">
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-outline-danger btn-sm flex-fill" id="select-all-participants">Pilih Semua</button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm flex-fill" id="clear-all-participants">Reset</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="participant-picker__list" id="participant-list">
                                    @foreach($pesertas as $peserta)
                                        @php
                                            $isSelected = $selectedPesertaIds->contains((string) $peserta->id);
                                            $initials = collect(explode(' ', $peserta->name))
                                                ->filter()
                                                ->take(2)
                                                ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
                                                ->implode('');
                                        @endphp
                                        <label class="participant-card" data-participant-name="{{ strtolower($peserta->name) }}">
                                            <input
                                                type="checkbox"
                                                name="peserta_ids[]"
                                                value="{{ $peserta->id }}"
                                                class="participant-checkbox"
                                                {{ $isSelected ? 'checked' : '' }}
                                            >
                                            <span class="participant-card__body">
                                                <span class="participant-card__avatar">{{ $initials }}</span>
                                                <span class="flex-grow-1">
                                                    <span class="d-block font-weight-bold text-dark">{{ $peserta->name }}</span>
                                                    <span class="d-block text-muted small">{{ $peserta->email }}</span>
                                                </span>
                                                <span class="participant-card__check">
                                                    <i class="fas fa-check small"></i>
                                                </span>
                                            </span>
                                        </label>
                                    @endforeach
                                </div>

                                <div class="participant-picker__empty" id="participant-empty-state">
                                    Tidak ada peserta yang cocok dengan pencarian.
                                </div>
                            </div>

                            <small class="text-muted d-block mt-2">Klik kartu peserta untuk memilih lebih dari satu sekaligus.</small>
                            @error('peserta_ids')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            @error('peserta_ids.*')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <input type="date" name="tanggal_masuk" class="form-control" value="{{ old('tanggal_masuk', now()->toDateString()) }}" required>
                        </div>
                        <div class="col-md-6">
                            <select name="status" class="form-select" required>
                                @foreach(['aktif', 'lulus', 'pindah', 'keluar'] as $status)
                                    <option value="{{ $status }}" {{ old('status', 'aktif') === $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan">{{ old('catatan') }}</textarea>
                        </div>
                        <div class="col-md-12">
                            <button class="btn btn-danger">Tambah Anggota</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5 mb-4">
            <div class="card shadow h-100">
                <div class="card-header">Anggota Kelompok</div>
                <div class="card-body">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>Peserta</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kelompok->anggota as $anggota)
                                <tr>
                                    <td>{{ $anggota->peserta->name ?? '-' }}</td>
                                    <td>{{ ucfirst($anggota->status) }}</td>
                                    <td>
                                        <form action="{{ route('admin.kelompok-peserta.update', $anggota) }}" method="POST" class="d-flex gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" class="form-select form-select-sm">
                                                @foreach(['aktif', 'lulus', 'pindah', 'keluar'] as $status)
                                                    <option value="{{ $status }}" {{ $anggota->status === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="catatan" value="{{ $anggota->catatan }}">
                                            <button class="btn btn-sm btn-warning">Update</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Belum ada anggota.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('participant-search');
        const cards = Array.from(document.querySelectorAll('.participant-card'));
        const checkboxes = Array.from(document.querySelectorAll('.participant-checkbox'));
        const countBadge = document.getElementById('selected-participant-count');
        const emptyState = document.getElementById('participant-empty-state');
        const selectAllButton = document.getElementById('select-all-participants');
        const clearAllButton = document.getElementById('clear-all-participants');

        function updateSelectedCount() {
            const totalSelected = checkboxes.filter((checkbox) => checkbox.checked).length;
            countBadge.textContent = `${totalSelected} dipilih`;
        }

        function filterParticipants() {
            const keyword = searchInput.value.trim().toLowerCase();
            let visibleCount = 0;

            cards.forEach((card) => {
                const name = card.dataset.participantName || '';
                const isVisible = !keyword || name.includes(keyword);
                card.style.display = isVisible ? '' : 'none';

                if (isVisible) {
                    visibleCount++;
                }
            });

            emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
        }

        searchInput?.addEventListener('input', filterParticipants);

        checkboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', updateSelectedCount);
        });

        selectAllButton?.addEventListener('click', function () {
            cards.forEach((card) => {
                if (card.style.display === 'none') {
                    return;
                }

                const checkbox = card.querySelector('.participant-checkbox');
                if (checkbox) {
                    checkbox.checked = true;
                }
            });

            updateSelectedCount();
        });

        clearAllButton?.addEventListener('click', function () {
            checkboxes.forEach((checkbox) => {
                checkbox.checked = false;
            });

            updateSelectedCount();
        });

        filterParticipants();
        updateSelectedCount();
    });
</script>
@endpush
