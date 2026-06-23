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
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
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
                                    <select name="hasil" form="pelatih-hasil-ujian-{{ $hasil->id }}" class="form-select form-select-sm select-hasil" data-target-id="nilai-{{ $hasil->id }}">
                                        @foreach(\App\Models\HasilUjianKelompok::HASIL_OPTIONS as $status => $label)
                                            <option value="{{ $status }}" {{ $hasil->hasil === $status ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" id="nilai-{{ $hasil->id }}" name="nilai" value="{{ $hasil->nilai }}" form="pelatih-hasil-ujian-{{ $hasil->id }}" class="form-control form-control-sm input-nilai">
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

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const selects = document.querySelectorAll('.select-hasil');
            selects.forEach(select => {
                updateLimitNilai(select); 
                select.addEventListener('change', function() {
                    updateLimitNilai(this, true); 
                });
            });

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

            const forms = document.querySelectorAll('form[id^="pelatih-hasil-ujian-"]');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const id = this.id.replace('pelatih-hasil-ujian-', '');
                    const select = document.querySelector(`.select-hasil[data-target-id="nilai-${id}"]`);
                    const input = document.getElementById(`nilai-${id}`);

                    if (!select || !input) return;

                    const hasil = select.value;
                    const nilai = input.value === '' ? '' : parseInt(input.value); 

                    let pesanError = '';

                    if (hasil === 'lulus' && (nilai === '' || nilai < 72 || nilai > 100)) {
                        pesanError = 'Untuk status Lulus, rentang nilai harus 72 - 100.';
                    } else if (hasil === 'mengulang' && (nilai === '' || nilai < 60 || nilai > 71)) {
                        pesanError = 'Untuk status Mengulang, rentang nilai harus 60 - 71.';
                    } else if (hasil === 'tidak_lulus' && (nilai === '' || nilai < 0 || nilai > 59)) {
                        pesanError = 'Untuk status Tidak Lulus, rentang nilai harus 0 - 59.';
                    } else if (hasil === 'menunggu' && nilai !== '') {
                        pesanError = 'Untuk status Menunggu, nilai harus dikosongkan.';
                    }

                    if (pesanError !== '') {
                        e.preventDefault(); 
                        alert(pesanError);  
                    }
                });
            });
        });

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
                inputNilai.min = 0;
                inputNilai.max = 100;
                if (isUserAction) {
                    inputNilai.value = '';
                }
            }
            
            inputNilai.setCustomValidity('');
        }
    </script>
</x-app-layout>