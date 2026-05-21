<x-app-layout>
    @if(!$isAdminDashboard)
        <h1 class="h3 mb-4 text-gray-800">Selamat Datang, {{ Auth::user()->name }}!</h1>
        <p class="mb-4">Anda login sebagai <strong>{{ ucfirst(Auth::user()->role) }}</strong>.</p>
    @else
        <style>
            .dashboard-hero {
                background: linear-gradient(135deg, #fff7f7 0%, #fff 45%, #fff2db 100%);
                border: 1px solid #f2d5d5;
                border-radius: 1.25rem;
                overflow: hidden;
                position: relative;
            }

            .dashboard-hero::after {
                content: "";
                position: absolute;
                inset: auto -70px -70px auto;
                width: 180px;
                height: 180px;
                background: radial-gradient(circle, rgba(220, 53, 69, 0.12) 0%, rgba(220, 53, 69, 0) 72%);
            }

            .metric-card {
                border: 0;
                border-radius: 1rem;
                box-shadow: 0 14px 30px rgba(31, 41, 55, 0.08);
                overflow: hidden;
            }

            .metric-card__accent {
                height: 6px;
                width: 100%;
            }

            .metric-card__value {
                font-size: 2rem;
                font-weight: 800;
                line-height: 1;
                color: #1f2937;
            }

            .metric-card__hint {
                color: #6b7280;
                font-size: 0.92rem;
            }

            .metric-card--danger .metric-card__accent {
                background: linear-gradient(90deg, #dc3545, #ff8a5b);
            }

            .metric-card--warning .metric-card__accent {
                background: linear-gradient(90deg, #f59e0b, #f97316);
            }

            .metric-card--primary .metric-card__accent {
                background: linear-gradient(90deg, #2563eb, #38bdf8);
            }

            .metric-card--success .metric-card__accent {
                background: linear-gradient(90deg, #16a34a, #4ade80);
            }
        </style>

        <div class="dashboard-hero p-4 p-lg-5 mb-4">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <p class="text-uppercase small font-weight-bold text-danger mb-2">Dashboard Admin</p>
                    <h1 class="h2 font-weight-bold text-gray-900 mb-2">Ringkasan Sanggar Hari Ini</h1>
                    <p class="mb-0 text-muted">
                        Pantau pertumbuhan peserta dan pendapatan sanggar dalam satu tampilan yang lebih informatif.
                    </p>
                </div>
                <div class="col-lg-5 mt-4 mt-lg-0">
                    <div class="bg-white rounded-lg shadow-sm border p-4">
                        <p class="mb-2 text-muted">Pendapatan Bulan Ini</p>
                        <div class="h2 mb-0 font-weight-bold text-danger">
                            Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card metric-card metric-card--danger h-100">
                    <div class="metric-card__accent"></div>
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-uppercase text-danger mb-2">Total Peserta</div>
                        <div class="metric-card__value">{{ number_format($totalPeserta) }}</div>
                        <div class="metric-card__hint mt-2">Semua data peserta yang sudah terdaftar.</div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card metric-card metric-card--success h-100">
                    <div class="metric-card__accent"></div>
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-uppercase text-success mb-2">Peserta Aktif</div>
                        <div class="metric-card__value">{{ number_format($pesertaAktif) }}</div>
                        <div class="metric-card__hint mt-2">Peserta dengan status akun aktif.</div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card metric-card metric-card--primary h-100">
                    <div class="metric-card__accent"></div>
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-uppercase text-primary mb-2">Jumlah Pelatih</div>
                        <div class="metric-card__value">{{ number_format($totalPelatih) }}</div>
                        <div class="metric-card__hint mt-2">Total pelatih yang tercatat pada sistem.</div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card metric-card metric-card--warning h-100">
                    <div class="metric-card__accent"></div>
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-uppercase text-warning mb-2">Pendapatan Bulan Ini</div>
                        <div class="metric-card__value" style="font-size: 1.7rem;">
                            Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}
                        </div>
                        <div class="metric-card__hint mt-2">Akumulasi booking dan SPP yang sudah dibayar.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4" style="border-radius: 1rem; overflow: hidden;">
            <div class="card-header py-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center" style="background: linear-gradient(90deg, #fff, #fff7f0);">
                <div>
                    <h6 class="m-0 font-weight-bold text-dark">Grafik Pendapatan 6 Bulan Terakhir</h6>
                    <small class="text-muted">Gabungan dari pembayaran booking kostum dan SPP peserta</small>
                </div>
            </div>
            <div class="card-body">
                <div style="height: 340px;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const canvas = document.getElementById('revenueChart');

                    if (!canvas) {
                        return;
                    }

                    const labels = @json($chartLabels);
                    const data = @json($chartTotals);

                    new Chart(canvas, {
                        type: 'bar',
                        data: {
                            labels,
                            datasets: [{
                                label: 'Pendapatan',
                                data,
                                borderRadius: 12,
                                borderSkipped: false,
                                backgroundColor: [
                                    '#fda4af',
                                    '#fb7185',
                                    '#f97316',
                                    '#f59e0b',
                                    '#ef4444',
                                    '#dc2626'
                                ],
                                hoverBackgroundColor: '#b91c1c'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function (context) {
                                            const value = Number(context.raw || 0);
                                            return ' Rp ' + value.toLocaleString('id-ID');
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function (value) {
                                            return 'Rp ' + Number(value).toLocaleString('id-ID');
                                        }
                                    },
                                    grid: {
                                        color: 'rgba(209, 213, 219, 0.35)'
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    }
                                }
                            }
                        }
                    });
                });
            </script>
        @endpush
    @endif
</x-app-layout>
