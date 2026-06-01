<x-app-layout>
    <h1 class="h3 mb-4 text-gray-800">Kelompok Saya</h1>

    @if(!$anggota)
        <div class="alert alert-info">Anda belum terdaftar pada kelompok aktif.</div>
    @else
        <div class="card shadow mb-4">
            <div class="card-body">
                <h3 class="h5">{{ $anggota->kelompok->nama_kelompok }}</h3>
                <p class="mb-1">Pelatih: <strong>{{ $anggota->kelompok->pelatih->name ?? '-' }}</strong></p>
                <p class="mb-0">Tanggal Masuk: {{ $anggota->tanggal_masuk->format('d/m/Y') }}</p>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header">Jadwal Latihan</div>
            <div class="card-body">
                @php
                    $liburMendatang = $anggota->kelompok->liburAktifs
                        ->filter(fn ($libur) => $libur->tanggal->isToday() || $libur->tanggal->isFuture());
                @endphp

                @if($liburMendatang->isNotEmpty())
                    <div class="alert alert-warning">
                        <strong>Info Libur:</strong>
                        <ul class="mb-0">
                            @foreach($liburMendatang as $libur)
                                <li>
                                    {{ $libur->tanggal->format('d/m/Y') }} - {{ $libur->judul }}
                                    @if($libur->jadwal)
                                        ({{ $libur->jadwal->hari }} {{ substr($libur->jadwal->jam_mulai, 0, 5) }})
                                    @endif
                                    @if($libur->alasan)
                                        : {{ $libur->alasan }}
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Hari</th>
                            <th>Jam</th>
                            <th>Lokasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($anggota->kelompok->jadwals as $jadwal)
                            <tr>
                                <td>{{ $jadwal->hari }}</td>
                                <td>{{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}</td>
                                <td>{{ $jadwal->lokasi ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Belum ada jadwal latihan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-header">Jadwal Ujian</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Ujian</th>
                            <th>Tanggal</th>
                            <th>Lokasi</th>
                            <th>Tujuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($anggota->kelompok->ujians as $ujian)
                            <tr>
                                <td>{{ $ujian->nama_ujian }}</td>
                                <td>{{ $ujian->tanggal_ujian->format('d/m/Y') }}</td>
                                <td>{{ $ujian->lokasi ?: '-' }}</td>
                                <td>{{ $ujian->kelompokTujuan->nama_kelompok ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada jadwal ujian.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card shadow mt-4">
            <div class="card-header">Riwayat Presensi Saya</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Pertemuan</th>
                            <th>Status</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $riwayatPresensi = $anggota->presensiDetails
                                ->filter(fn ($detail) => optional($detail->presensi)->kelompok_id === $anggota->kelompok_id)
                                ->sortByDesc(fn ($detail) => optional($detail->presensi)->tanggal_presensi)
                                ->take(10);
                        @endphp
                        @forelse($riwayatPresensi as $detail)
                            <tr>
                                <td>{{ optional($detail->presensi?->tanggal_presensi)->format('d/m/Y') ?? '-' }}</td>
                                <td>{{ $detail->presensi->judul_pertemuan ?? '-' }}</td>
                                <td>{{ ucfirst($detail->status_kehadiran) }}</td>
                                <td>{{ $detail->catatan ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada data presensi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</x-app-layout>
