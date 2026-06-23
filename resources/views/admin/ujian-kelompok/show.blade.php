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

    @if(session('error'))
        <div class="alert alert-danger">{!! session('error') !!}</div>
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

    {{-- Satu form untuk semua peserta --}}
    <form id="form-bulk" action="{{ route('admin.hasil-ujian-kelompok.update-bulk', $ujian) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <span>Hasil Ujian Peserta</span>
                @if($ujian->hasils->isNotEmpty())
                    <button type="submit" class="btn btn-warning btn-sm px-4">
                        <i class="fas fa-save me-1"></i> Simpan Semua
                    </button>
                @endif
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>Peserta</th>
                                <th>Hasil</th>
                                <th>Nilai</th>
                                <th>Catatan</th>
                                <th>Status Promosi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ujian->hasils as $i => $hasil)
                                <tr>
                                    {{-- Hidden ID peserta --}}
                                    <input type="hidden" name="hasils[{{ $i }}][id]" value="{{ $hasil->id }}">

                                    <td>{{ $hasil->peserta->name ?? '-' }}</td>
                                    <td>
                                        <select
                                            name="hasils[{{ $i }}][hasil]"
                                            class="form-select form-select-sm select-hasil"
                                            data-target-id="nilai-{{ $hasil->id }}"
                                        >
                                            @foreach(\App\Models\HasilUjianKelompok::HASIL_OPTIONS as $status => $label)
                                                <option value="{{ $status }}" {{ $hasil->hasil === $status ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input
                                            type="number"
                                            id="nilai-{{ $hasil->id }}"
                                            name="hasils[{{ $i }}][nilai]"
                                            value="{{ $hasil->nilai }}"
                                            class="form-control form-control-sm input-nilai"
                                        >
                                    </td>
                                    <td>
                                        <input
                                            type="text"
                                            name="hasils[{{ $i }}][catatan]"
                                            value="{{ $hasil->catatan }}"
                                            class="form-control form-control-sm"
                                        >
                                    </td>
                                    <td>
                                        <span class="small {{ $hasil->promoted_at ? 'text-success' : 'text-muted' }}">
                                            {{ $hasil->promoted_at ? 'Sudah dipromosikan' : 'Belum dipromosikan' }}
                                        </span>
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

                @if($ujian->hasils->isNotEmpty())
                    <div class="text-end mt-2">
                        <button type="submit" class="btn btn-warning px-5">
                            <i class="fas fa-save me-1"></i> Simpan Semua
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </form>

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

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        
        // 1. Mengatur perubahan otomatis saat dropdown diganti
        const selects = document.querySelectorAll('.select-hasil');
        selects.forEach(select => {
            updateLimitNilai(select); // Set awal saat halaman dimuat
            select.addEventListener('change', function() {
                updateLimitNilai(this, true); // Update saat user mengubah pilihan
            });
        });

        // 2. Mengubah bahasa popup validasi bawaan browser ke Bahasa Indonesia
        const inputNilais = document.querySelectorAll('.input-nilai');
        inputNilais.forEach(input => {
            input.addEventListener('invalid', function (e) {
                if (this.validity.rangeUnderflow) {
                    this.setCustomValidity('Nilai tidak boleh kurang dari ' + this.min + '.');
                } else if (this.validity.rangeOverflow) {
                    this.setCustomValidity('Nilai tidak boleh lebih dari ' + this.max + '.');
                } else if (this.validity.valueMissing) {
                    this.setCustomValidity('Nilai wajib diisi.');
                } else {
                    this.setCustomValidity('');
                }
            });

            input.addEventListener('input', function (e) {
                this.setCustomValidity('');
            });
        });

        // 3. Validasi ketat saat tombol "Simpan Semua" ditekan
        const formBulk = document.getElementById('form-bulk');
        if (formBulk) {
            formBulk.addEventListener('submit', function(e) {
                const rows = document.querySelectorAll('tbody tr');
                let pesanErrors = [];

                rows.forEach(row => {
                    const select = row.querySelector('.select-hasil');
                    const input = row.querySelector('.input-nilai');
                    if (!select || !input) return;

                    const hasil = select.value;
                    const nilai = input.value === '' ? '' : parseInt(input.value);
                    const namaPeserta = row.querySelector('td:first-child')?.textContent?.trim() || 'Peserta';

                    if (hasil === 'lulus' && (nilai === '' || nilai < 72 || nilai > 100)) {
                        pesanErrors.push(namaPeserta + ': Status Lulus membutuhkan nilai 72–100.');
                    } else if (hasil === 'mengulang' && (nilai === '' || nilai < 60 || nilai > 71)) {
                        pesanErrors.push(namaPeserta + ': Status Mengulang membutuhkan nilai 60–71.');
                    } else if (hasil === 'tidak_lulus' && (nilai === '' || nilai < 0 || nilai > 59)) {
                        pesanErrors.push(namaPeserta + ': Status Tidak Lulus membutuhkan nilai 0–59.');
                    } else if (hasil === 'menunggu' && nilai !== '') {
                        pesanErrors.push(namaPeserta + ': Status Menunggu harus dikosongkan nilainya.');
                    }
                });

                if (pesanErrors.length > 0) {
                    e.preventDefault();
                    alert('Terdapat kesalahan:\n\n' + pesanErrors.join('\n'));
                }
            });
        }
    });

    // 4. Fungsi Utama untuk mengatur batas minimal, maksimal, dan nilai otomatis
    function updateLimitNilai(selectElement, isUserAction = false) {
        const inputId = selectElement.getAttribute('data-target-id');
        const inputNilai = document.getElementById(inputId);
        if (!inputNilai) return;

        const hasil = selectElement.value;
        let currentValue = parseInt(inputNilai.value);

        if (hasil === 'lulus') {
            inputNilai.min = 72;
            inputNilai.max = 100;
            if (isUserAction || isNaN(currentValue) || currentValue < 72) {
                inputNilai.value = 72;
            }
        } else if (hasil === 'mengulang') {
            inputNilai.min = 60;
            inputNilai.max = 71;
            if (isUserAction || isNaN(currentValue) || currentValue < 60 || currentValue > 71) {
                inputNilai.value = 60;
            }
        } else if (hasil === 'tidak_lulus') {
            inputNilai.min = 0;
            inputNilai.max = 59;
            if (isUserAction || isNaN(currentValue) || currentValue > 59) {
                inputNilai.value = 0;
            }
        } else {
            // Status: menunggu
            inputNilai.min = 0;
            inputNilai.max = 100;
            if (isUserAction) {
                inputNilai.value = '';
            }
        }
        
        // Memastikan reset pesan custom validity ketika nilai atau dropdown berubah
        inputNilai.setCustomValidity('');
    }
</script>

</x-app-layout>