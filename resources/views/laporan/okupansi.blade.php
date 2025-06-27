@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <!-- Header Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h4 class="m-0 font-weight-bold text-primary">Laporan Okupansi Properti</h4>
                <div class="d-flex">
                    <button class="btn btn-danger" onclick="exportPDF()">
                        <i class="fas fa-file-pdf fa-sm"></i> Export PDF
                    </button>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Properti</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ count($data) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-hotel fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Kamar</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $data->sum('total_kamar') }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-door-open fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Kamar Terisi</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $data->sum('kamar_terisi') }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-bed fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Rata-rata Okupansi
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    @php
                                        $totalOccupancy = $data->sum(
                                            fn($item) => floatval(str_replace('%', '', $item['tingkat_okupansi'])),
                                        );
                                        $avgOccupancy = count($data) > 0 ? $totalOccupancy / count($data) : 0;
                                    @endphp
                                    {{ number_format($avgOccupancy, 2) }}%
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-percent fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="row mb-4">
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Grafik Okupansi per Properti</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-bar">
                            <canvas id="occupancyBarChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Distribusi Okupansi</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-pie pt-4 pb-2">
                            <canvas id="occupancyPieChart"></canvas>
                        </div>
                        <div class="mt-4 text-center small">
                            <span class="mr-2"><i class="fas fa-circle text-success"></i> Tinggi (&gt;80%)</span>
                            <span class="mr-2"><i class="fas fa-circle text-info"></i> Menengah (50-80%)</span>
                            <span class="mr-2"><i class="fas fa-circle text-warning"></i> Rendah (&lt;50%)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Data -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Detail Okupansi Properti</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th>Nama Properti</th>
                                <th>Total Kamar</th>
                                <th>Kamar Terisi</th>
                                <th>Kamar Kosong</th>
                                <th>Tingkat Okupansi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $row)
                                @php
                                    $occupancy = floatval(str_replace('%', '', $row['tingkat_okupansi']));
                                    $colorClass =
                                        $occupancy >= 80 ? 'bg-success' : ($occupancy >= 50 ? 'bg-info' : 'bg-warning');
                                @endphp
                                <tr>
                                    <td class="font-weight-bold">{{ $row['nama_properti'] }}</td>
                                    <td>{{ $row['total_kamar'] }}</td>
                                    <td><span class="badge bg-success text-white">{{ $row['kamar_terisi'] }}</span></td>
                                    <td><span class="badge bg-warning text-dark">{{ $row['kamar_kosong'] }}</span></td>


                                    <td>
                                        <div class="occupancy-wrapper">
                                            <div class="occupancy-bar {{ $colorClass }}"
                                                style="width: {{ $occupancy }}%;">
                                                <span class="occupancy-label">{{ $row['tingkat_okupansi'] }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <button class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Detail
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const propertyNames = @json($data->pluck('nama_properti'));
        const occupancyRates = @json($data->map(fn($item) => floatval(str_replace('%', '', $item['tingkat_okupansi']))));

        const highOccupancy = occupancyRates.filter(rate => rate >= 80).length;
        const mediumOccupancy = occupancyRates.filter(rate => rate >= 50 && rate < 80).length;
        const lowOccupancy = occupancyRates.filter(rate => rate < 50).length;

        new Chart(document.getElementById('occupancyBarChart'), {
            type: 'bar',
            data: {
                labels: propertyNames,
                datasets: [{
                    label: 'Tingkat Okupansi (%)',
                    data: occupancyRates,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        title: {
                            display: true,
                            text: 'Persentase Okupansi'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Nama Properti'
                        },
                        ticks: {
                            autoSkip: false,
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                }
            }
        });

        new Chart(document.getElementById('occupancyPieChart'), {
            type: 'pie',
            data: {
                labels: ['Tinggi (>80%)', 'Menengah (50-80%)', 'Rendah (<50%)'],
                datasets: [{
                    data: [highOccupancy, mediumOccupancy, lowOccupancy],
                    backgroundColor: ['#1cc88a', '#36b9cc', '#f6c23e'],
                    hoverBackgroundColor: ['#17a673', '#2c9faf', '#dda20a'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)"
                }]
            },
            options: {
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.raw;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${context.label}: ${value} properti (${percentage}%)`;
                            }
                        }
                    },
                    legend: {
                        display: false
                    }
                },
                cutout: '80%'
            }
        });

        function exportPDF() {
            const barChartImg = document.getElementById('occupancyBarChart').toDataURL('image/png');
            const pieChartImg = document.getElementById('occupancyPieChart').toDataURL('image/png');

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('laporan.okupansi.export.pdf') }}";

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = "{{ csrf_token() }}";
            form.appendChild(csrf);

            const barInput = document.createElement('input');
            barInput.type = 'hidden';
            barInput.name = 'bar_chart';
            barInput.value = barChartImg;
            form.appendChild(barInput);

            const pieInput = document.createElement('input');
            pieInput.type = 'hidden';
            pieInput.name = 'pie_chart';
            pieInput.value = pieChartImg;
            form.appendChild(pieInput);

            document.body.appendChild(form);
            form.submit();
        }
    </script>
@endpush

@push('styles')
    <style>
        .occupancy-wrapper {
            background-color: #f0f2f5;
            border-radius: 1rem;
            height: 36px;
            overflow: hidden;
            position: relative;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .occupancy-bar {
            height: 100%;
            line-height: 36px;
            font-weight: 600;
            font-size: 0.95rem;
            text-align: center;
            color: #fff;
            transition: width 0.6s ease;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Color overrides */
        .occupancy-bar.bg-success {
            background: linear-gradient(to right, #38c172, #28a745);
        }

        .occupancy-bar.bg-info {
            background: linear-gradient(to right, #63b3ed, #4299e1);
        }

        .occupancy-bar.bg-warning {
            background: linear-gradient(to right, #F6BE68, #F09F38);
            color: #212529;
        }

        .occupancy-label {
            padding: 0 10px;
            white-space: nowrap;
        }

        .card {
            border-radius: 0.35rem;
        }

        .progress {
            height: 20px;
        }

        .chart-bar {
            height: 400px;
        }

        .chart-pie {
            height: 300px;
        }

        @media (max-width: 768px) {

            .chart-bar,
            .chart-pie {
                height: 300px;
            }
        }
    </style>
@endpush
