@extends('adminlte::page')

@section('title', 'Sunucu Kullanım Detayları')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>
            <i class="fas fa-chart-line mr-2"></i>
            {{ $server->name }} - Kullanım Detayları
        </h1>
        <div class="d-flex align-items-center">
            <!-- Zaman Aralığı -->
            <div class="btn-group mr-2">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-calendar-alt"></i> Zaman Aralığı
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item period-btn" href="#" data-period="1h">Son 1 Saat</a>
                    <a class="dropdown-item period-btn" href="#" data-period="6h">Son 6 Saat</a>
                    <a class="dropdown-item period-btn" href="#" data-period="12h">Son 12 Saat</a>
                    <a class="dropdown-item period-btn active" href="#" data-period="24h">Son 24 Saat</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item period-btn" href="#" data-period="7d">Son 7 Gün</a>
                    <a class="dropdown-item period-btn" href="#" data-period="30d">Son 30 Gün</a>
                </div>
            </div>

            <!-- Güncelleme Aralığı -->
            <div class="btn-group mr-2">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-clock"></i> Güncelleme Aralığı
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item interval-btn" href="#" data-interval="1m">1 Dakika</a>
                    <a class="dropdown-item interval-btn active" href="#" data-interval="5m">5 Dakika</a>
                    <a class="dropdown-item interval-btn" href="#" data-interval="15m">15 Dakika</a>
                    <a class="dropdown-item interval-btn" href="#" data-interval="30m">30 Dakika</a>
                    <a class="dropdown-item interval-btn" href="#" data-interval="1h">1 Saat</a>
                    <a class="dropdown-item interval-btn" href="#" data-interval="all">Tümü</a>
                </div>
            </div>

            <a href="{{ route('admin.servers.show', $server) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Geri
            </a>
        </div>
    </div>
@stop

@section('content')
    <!-- Metrikler -->
    <div class="row">
        <!-- CPU Metrikleri -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-microchip text-success mr-1"></i>
                        CPU Metrikleri (24 Saat)
                    </h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <tr>
                            <td>Ortalama Kullanım</td>
                            <td class="text-right">{{ number_format($metrics['today']['cpu']['avg'], 1) }}%</td>
                        </tr>
                        <tr>
                            <td>En Yüksek Kullanım</td>
                            <td class="text-right">
                                {{ number_format($metrics['today']['cpu']['max'], 1) }}%
                                @if($metrics['today']['cpu']['max_time'])
                                    <small class="d-block text-muted">{{ $metrics['today']['cpu']['max_time']->format('H:i') }}</small>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>En Düşük Kullanım</td>
                            <td class="text-right">
                                {{ number_format($metrics['today']['cpu']['min'], 1) }}%
                                @if($metrics['today']['cpu']['min_time'])
                                    <small class="d-block text-muted">{{ $metrics['today']['cpu']['min_time']->format('H:i') }}</small>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Bellek Metrikleri -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-memory text-warning mr-1"></i>
                        Bellek Metrikleri (24 Saat)
                    </h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <tr>
                            <td>Ortalama Kullanım</td>
                            <td class="text-right">{{ number_format($metrics['today']['memory']['avg'], 1) }}%</td>
                        </tr>
                        <tr>
                            <td>En Yüksek Kullanım</td>
                            <td class="text-right">
                                {{ number_format($metrics['today']['memory']['max'], 1) }}%
                                @if($metrics['today']['memory']['max_time'])
                                    <small class="d-block text-muted">{{ $metrics['today']['memory']['max_time']->format('H:i') }}</small>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>En Düşük Kullanım</td>
                            <td class="text-right">
                                {{ number_format($metrics['today']['memory']['min'], 1) }}%
                                @if($metrics['today']['memory']['min_time'])
                                    <small class="d-block text-muted">{{ $metrics['today']['memory']['min_time']->format('H:i') }}</small>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Saatlik Ortalamalar -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-clock text-info mr-1"></i>
                        Saatlik Ortalamalar
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($metrics['hourly']->take(6) as $hour => $data)
                            <div class="list-group-item py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted" style="width: 60px">
                                        <i class="far fa-clock"></i>
                                        {{ $hour }}
                                    </div>
                                    <div class="d-flex justify-content-around flex-fill mx-2">
                                        <div class="text-center mx-2">
                                            <i class="fas fa-microchip text-success"></i>
                                            <span class="badge badge-{{ $data['cpu'] > 80 ? 'danger' : ($data['cpu'] > 60 ? 'warning' : 'success') }}">
                                                {{ $data['cpu'] }}%
                                            </span>
                                        </div>
                                        <div class="text-center mx-2">
                                            <i class="fas fa-memory text-warning"></i>
                                            <span class="badge badge-{{ $data['memory'] > 80 ? 'danger' : ($data['memory'] > 60 ? 'warning' : 'success') }}">
                                                {{ $data['memory'] }}%
                                            </span>
                                        </div>
                                        <div class="text-center mx-2">
                                            <i class="fas fa-hdd text-info"></i>
                                            <span class="badge badge-{{ $data['disk'] > 80 ? 'danger' : ($data['disk'] > 60 ? 'warning' : 'success') }}">
                                                {{ $data['disk'] }}%
                                            </span>
                                        </div>
                                    </div>
                                    <small class="text-muted" style="width: 60px; text-align: right">
                                        {{ $data['count'] }} veri
                                    </small>
                                </div>
                            </div>
                        @endforeach

                        @if($metrics['hourly']->count() > 6)
                            <div class="text-center py-2">
                                <button class="btn btn-sm btn-link" type="button" data-toggle="collapse" data-target="#moreHours">
                                    Daha Fazla Göster <i class="fas fa-chevron-down ml-1"></i>
                                </button>
                            </div>
                            <div class="collapse" id="moreHours">
                                @foreach($metrics['hourly']->skip(6) as $hour => $data)
                                    <div class="list-group-item py-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="text-muted" style="width: 60px">
                                                <i class="far fa-clock"></i>
                                                {{ $hour }}
                                            </div>
                                            <div class="d-flex justify-content-around flex-fill mx-2">
                                                <div class="text-center mx-2">
                                                    <i class="fas fa-microchip text-success"></i>
                                                    <span class="badge badge-{{ $data['cpu'] > 80 ? 'danger' : ($data['cpu'] > 60 ? 'warning' : 'success') }}">
                                                        {{ $data['cpu'] }}%
                                                    </span>
                                                </div>
                                                <div class="text-center mx-2">
                                                    <i class="fas fa-memory text-warning"></i>
                                                    <span class="badge badge-{{ $data['memory'] > 80 ? 'danger' : ($data['memory'] > 60 ? 'warning' : 'success') }}">
                                                        {{ $data['memory'] }}%
                                                    </span>
                                                </div>
                                                <div class="text-center mx-2">
                                                    <i class="fas fa-hdd text-info"></i>
                                                    <span class="badge badge-{{ $data['disk'] > 80 ? 'danger' : ($data['disk'] > 60 ? 'warning' : 'success') }}">
                                                        {{ $data['disk'] }}%
                                                    </span>
                                                </div>
                                            </div>
                                            <small class="text-muted" style="width: 60px; text-align: right">
                                                {{ $data['count'] }} veri
                                            </small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik -->
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <canvas id="resourceChart" style="min-height: 400px;"></canvas>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <style>
        .period-btn.active {
            background-color: #007bff;
            border-color: #007bff;
        }
        #hourly-metrics_wrapper .row {
            margin: 0;
        }
        #hourly-metrics_filter {
            margin-bottom: 0.5rem;
        }
        #hourly-metrics_length select {
            width: 60px;
        }
        .badge {
            min-width: 45px;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script>
        let chart;
        let currentPeriod = '24h';
        let currentInterval = '5m';
        
        function loadData(period = currentPeriod, interval = currentInterval) {
            currentPeriod = period;
            currentInterval = interval;
            
            fetch(`{{ route('admin.servers.usage.data', $server) }}?period=${period}&interval=${interval}`)
                .then(response => response.json())
                .then(data => {
                    if (chart) {
                        chart.destroy();
                    }
                    
                    chart = new Chart(document.getElementById('resourceChart'), {
                        type: 'line',
                        data: {
                            labels: data.labels,
                            datasets: [
                                {
                                    label: 'CPU Kullanımı (%)',
                                    data: data.cpu,
                                    borderColor: '#28a745',
                                    backgroundColor: '#28a74520',
                                    fill: true,
                                    tension: 0.4
                                },
                                {
                                    label: 'Bellek Kullanımı (%)',
                                    data: data.memory,
                                    borderColor: '#ffc107',
                                    backgroundColor: '#ffc10720',
                                    fill: true,
                                    tension: 0.4
                                },
                                {
                                    label: 'Disk Kullanımı (%)',
                                    data: data.disk,
                                    borderColor: '#17a2b8',
                                    backgroundColor: '#17a2b820',
                                    fill: true,
                                    tension: 0.4
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: {
                                intersect: false,
                                mode: 'index'
                            },
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Sistem Kaynakları Kullanımı'
                                },
                                tooltip: {
                                    enabled: true,
                                    mode: 'index',
                                    intersect: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: 100,
                                    title: {
                                        display: true,
                                        text: 'Kullanım Yüzdesi (%)'
                                    }
                                },
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Zaman'
                                    }
                                }
                            }
                        }
                    });
                });
        }
        
        document.addEventListener('DOMContentLoaded', () => {
            loadData();
            
            // Periyot butonları için event listener
            document.querySelectorAll('.period-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    document.querySelectorAll('.period-btn').forEach(b => b.classList.remove('active'));
                    e.target.classList.add('active');
                    loadData(e.target.dataset.period);
                });
            });

            // Interval butonları için event listener
            document.querySelectorAll('.interval-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    document.querySelectorAll('.interval-btn').forEach(b => b.classList.remove('active'));
                    e.target.classList.add('active');
                    loadData(currentPeriod, e.target.dataset.interval);
                });
            });

            // Saatlik metrikler tablosu
            $('#hourly-metrics').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/tr.json'
                },
                pageLength: 6,
                lengthMenu: [[6, 12, 24, -1], [6, 12, 24, "Tümü"]],
                order: [[0, 'desc']],
                dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                     "<'row'<'col-sm-12'tr>>" +
                     "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                scrollY: '250px',
                scrollCollapse: true,
                paging: true
            });
        });
    </script>
@stop 