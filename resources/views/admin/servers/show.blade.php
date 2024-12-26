@extends('adminlte::page')

@section('title', 'Sunucu Detayı')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>
            <i class="fas fa-server mr-2"></i>
            {{ $server->name }}
        </h1>
        <div>
            <a href="{{ route('admin.servers.edit', $server) }}" class="btn btn-info">
                <i class="fas fa-edit"></i> Düzenle
            </a>
            <a href="{{ route('admin.servers.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Geri
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if($lastSystemInfo = $server->systemInfos()->with(['operatingSystem', 'cpuInfo', 'memoryInfo', 'diskInfos', 'updateInfo.packages'])->latest()->first())
                <!-- Sistem Bilgileri -->
                <div class="row">
                   
                    <!-- Sunucu Bilgileri -->
                    <div class="col-md-3">
                        <div class="small-box bg-white">
                            <div class="inner">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-info-circle fa-2x text-primary mr-2"></i>
                                    <h5 class="mb-0">Sunucu Bilgileri</h5>
                                </div>  
                                <p class="mb-1"><strong>IP:</strong> {{ $server->ip_address }}</p>
                                <p class="mb-1"><strong>Port:</strong> {{ $server->ssh_port }}</p>
                                <p class="mb-1"><strong>Kullanıcı:</strong> {{ $server->username }}</p>
                                <p class="mb-1"><strong>Son Güncelleme:</strong> {{ $lastSystemInfo->system_timestamp->format('d.m.Y H:i:s') }}</p>

                                <div class="input-group input-group-sm mt-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">API Key</span>
                                    </div>
                                    <input type="text" class="form-control" value="{{ $server->api_key }}" readonly>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary copy-btn" type="button" data-clipboard-text="{{ $server->api_key }}">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="small-box-footer bg-light">
                                @if($server->is_active)
                                    <span class="text-success"><i class="fas fa-check-circle"></i> Aktif</span>
                                @else
                                    <span class="text-danger"><i class="fas fa-times-circle"></i> Pasif</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- İşletim Sistemi -->
                    <div class="col-md-3">
                        <div class="small-box bg-white">
                            <div class="inner">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fab fa-linux fa-2x text-info mr-2"></i>
                                    <h5 class="mb-0">İşletim Sistemi</h5>
                                </div>
                                <p class="mb-1">{{ $lastSystemInfo->operatingSystem->name }}</p>
                                <p class="mb-1">{{ $lastSystemInfo->operatingSystem->version }}</p>
                                <p class="mb-0">Python: {{ $lastSystemInfo->python_version }}</p>
                            </div>
                            <div class="small-box-footer bg-light">
                                <span class="text-muted">{{ $lastSystemInfo->architecture }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- CPU Kullanımı -->
                    <div class="col-md-3">
                        <div class="small-box bg-white">
                            <div class="inner">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-microchip fa-2x text-success mr-2"></i>
                                    <h5 class="mb-0">CPU Kullanımı</h5>
                                </div>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-success" style="width: {{ $lastSystemInfo->cpuInfo->usage_percent }}%">
                                        {{ number_format($lastSystemInfo->cpuInfo->usage_percent, 1) }}%
                                    </div>
                                </div>
                                <p class="mt-2 mb-0">{{ $lastSystemInfo->cpuInfo->cores }} Çekirdek</p>
                                <button type="button" class="btn btn-sm btn-outline-success mt-2" data-toggle="modal" data-target="#cpuProcessesModal">
                                    <i class="fas fa-tasks mr-1"></i> CPU Süreçleri
                                </button>
                            </div>
                            <div class="small-box-footer bg-light">
                                <span class="text-muted">{{ $lastSystemInfo->processor }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Bellek Kullanımı -->
                    <div class="col-md-3">
                        <div class="small-box bg-white">
                            <div class="inner">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-memory fa-2x text-warning mr-2"></i>
                                    <h5 class="mb-0">Bellek Kullanımı</h5>
                                </div>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-warning" style="width: {{ $lastSystemInfo->memoryInfo->usage_percent }}%">
                                        {{ number_format($lastSystemInfo->memoryInfo->usage_percent, 1) }}%
                                    </div>
                                </div>
                                <p class="mt-2 mb-0">
                                    {{ number_format($lastSystemInfo->memoryInfo->used_gb, 1) }} GB / 
                                    {{ number_format($lastSystemInfo->memoryInfo->total_gb, 1) }} GB
                                </p>
                                <button type="button" class="btn btn-sm btn-outline-warning mt-2" data-toggle="modal" data-target="#memoryProcessesModal">
                                    <i class="fas fa-tasks mr-1"></i> Bellek Süreçleri
                                </button>
                            </div>
                            <div class="small-box-footer bg-light">
                                <span class="text-muted">{{ number_format($lastSystemInfo->memoryInfo->free_gb, 1) }} GB Boş</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Network Arayüzleri -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-network-wired mr-1"></i>
                            Ağ Arayüzleri
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Arayüz</th>
                                        <th>IP Adresi</th>
                                        <th>Alt Ağ Maskesi</th>
                                        <th>MAC Adresi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lastSystemInfo->networkInterfaces as $interface)
                                        <tr>
                                            <td>
                                                <i class="fas fa-ethernet text-info mr-1"></i>
                                                {{ $interface->name }}
                                            </td>
                                            <td>{{ $interface->ip_address }}</td>
                                            <td>{{ $interface->netmask }}</td>
                                            <td>
                                                <span class="text-monospace">{{ $interface->mac_address }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Disk Kullanımı -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-hdd mr-1"></i>
                            Disk Kullanımı
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Disk</th>
                                        <th>Bağlantı Noktası</th>
                                        <th>Toplam</th>
                                        <th>Kullanılan</th>
                                        <th>Boş</th>
                                        <th style="width: 200px;">Kullanım</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lastSystemInfo->diskInfos as $disk)
                                        <tr>
                                            <td>
                                                <i class="fas fa-hdd text-muted mr-1"></i>
                                                {{ $disk->device }}
                                            </td>
                                            <td>{{ $disk->mountpoint }}</td>
                                            <td>{{ number_format($disk->total_gb, 1) }} GB</td>
                                            <td>{{ number_format($disk->used_gb, 1) }} GB</td>
                                            <td>{{ number_format($disk->free_gb, 1) }} GB</td>
                                            <td>
                                                <div class="progress" style="height: 4px;">
                                                    <div class="progress-bar bg-{{ $disk->usage_percent > 90 ? 'danger' : ($disk->usage_percent > 70 ? 'warning' : 'success') }}" 
                                                         style="width: {{ $disk->usage_percent }}%">
                                                    </div>
                                                </div>
                                                <small class="text-muted">{{ number_format($disk->usage_percent, 1) }}% kullanımda</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Güncellemeler -->
                @if($lastSystemInfo->updateInfo)
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-sync mr-1"></i>
                                Bekleyen Güncellemeler
                                <span class="badge badge-warning ml-1">{{ $lastSystemInfo->updateInfo->count }}</span>
                            </h3>
                        </div>
                        @if($lastSystemInfo->updateInfo->packages->count() > 0)
                            <div class="card-body">
                                <table class="table table-bordered table-striped" id="updates-table">
                                    <thead>
                                        <tr>
                                            <th>Paket</th>
                                            <th>Mevcut Versiyon</th>
                                            <th>Yeni Versiyon</th>
                                            <th>Mimari</th>
                                            <th>Dağıtım</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($lastSystemInfo->updateInfo->packages as $package)
                                            <tr>
                                                <td>
                                                    <i class="fas fa-box text-muted mr-1"></i>
                                                    {{ $package->package }}
                                                </td>
                                                <td>{{ $package->current_version }}</td>
                                                <td>{{ $package->new_version }}</td>
                                                <td>{{ $package->architecture }}</td>
                                                <td>{{ $package->distribution }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="card-body">
                                <p class="text-muted mb-0">Güncelleme paketi bilgisi bulunamadı.</p>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Process'ler -->
                @if($lastSystemInfo->processInfo)
                    <div class="card mt-3">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-tasks mr-1"></i>
                                Çalışan Süreçler
                                <span class="badge badge-info ml-1">{{ $lastSystemInfo->processInfo->total_processes }}</span>
                            </h3>
                            <div class="card-tools">
                                <span class="badge badge-success mr-1">
                                    <i class="fas fa-play"></i> Çalışan: {{ $lastSystemInfo->processInfo->running }}
                                </span>
                                <span class="badge badge-secondary mr-1">
                                    <i class="fas fa-pause"></i> Uyuyan: {{ $lastSystemInfo->processInfo->sleeping }}
                                </span>
                                <span class="badge badge-warning mr-1">
                                    <i class="fas fa-stop"></i> Durmuş: {{ $lastSystemInfo->processInfo->stopped }}
                                </span>
                                <span class="badge badge-danger">
                                    <i class="fas fa-skull"></i> Zombi: {{ $lastSystemInfo->processInfo->zombie }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped" id="processes-table">
                                <thead>
                                    <tr>
                                        <th>PID</th>
                                        <th>Süreç</th>
                                        <th>Kullanıcı</th>
                                        <th>CPU %</th>
                                        <th>Bellek %</th>
                                        <th>Durum</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lastSystemInfo->processInfo->processes->sortByDesc('memory_percent') as $process)
                                        <tr>
                                            <td>{{ $process->pid }}</td>
                                            <td>
                                                <i class="fas fa-cog text-muted mr-1"></i>
                                                {{ $process->name }}
                                            </td>
                                            <td>
                                                <i class="fas fa-user text-muted mr-1"></i>
                                                {{ $process->username }}
                                            </td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar bg-info" 
                                                         style="width: {{ $process->cpu_percent }}%"
                                                         title="{{ number_format($process->cpu_percent, 1) }}%">
                                                        {{ number_format($process->cpu_percent, 1) }}%
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar bg-{{ 
                                                        $process->memory_percent > 80 ? 'danger' : 
                                                        ($process->memory_percent > 60 ? 'warning' : 'success') 
                                                    }}" style="width: {{ $process->memory_percent }}%"
                                                    title="{{ number_format($process->memory_percent, 1) }}%">
                                                        {{ number_format($process->memory_percent, 1) }}%
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $process->status }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

            @else
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Henüz sistem bilgisi gönderilmemiş.
                </div>
            @endif
        </div>
    </div>

    <!-- CPU Süreçleri Modal -->
    <div class="modal fade" id="cpuProcessesModal" tabindex="-1" role="dialog" aria-labelledby="cpuProcessesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cpuProcessesModalLabel">
                        <i class="fas fa-microchip text-success mr-1"></i>
                        CPU Kullanımına Göre Süreçler
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-striped" id="cpu-processes-table">
                        <thead>
                            <tr>
                                <th>PID</th>
                                <th>Süreç</th>
                                <th>Kullanıcı</th>
                                <th>CPU %</th>
                                <th>Bellek %</th>
                                <th>Durum</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lastSystemInfo->processInfo->processes->sortByDesc('cpu_percent') as $process)
                                <tr>
                                    <td>{{ $process->pid }}</td>
                                    <td>
                                        <i class="fas fa-cog text-muted mr-1"></i>
                                        {{ $process->name }}
                                    </td>
                                    <td>
                                        <i class="fas fa-user text-muted mr-1"></i>
                                        {{ $process->username }}
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-success" 
                                                 style="width: {{ $process->cpu_percent }}%"
                                                 title="{{ number_format($process->cpu_percent, 1) }}%">
                                                {{ number_format($process->cpu_percent, 1) }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ number_format($process->memory_percent, 1) }}%</td>
                                    <td>{{ $process->status }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bellek Süreçleri Modal -->
    <div class="modal fade" id="memoryProcessesModal" tabindex="-1" role="dialog" aria-labelledby="memoryProcessesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="memoryProcessesModalLabel">
                        <i class="fas fa-memory text-warning mr-1"></i>
                        Bellek Kullanımına Göre Süreçler
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-striped" id="memory-processes-table">
                        <thead>
                            <tr>
                                <th>PID</th>
                                <th>Süreç</th>
                                <th>Kullanıcı</th>
                                <th>CPU %</th>
                                <th>Bellek %</th>
                                <th>Durum</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lastSystemInfo->processInfo->processes->sortByDesc('memory_percent') as $process)
                                <tr>
                                    <td>{{ $process->pid }}</td>
                                    <td>
                                        <i class="fas fa-cog text-muted mr-1"></i>
                                        {{ $process->name }}
                                    </td>
                                    <td>
                                        <i class="fas fa-user text-muted mr-1"></i>
                                        {{ $process->username }}
                                    </td>
                                    <td>{{ number_format($process->cpu_percent, 1) }}%</td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-warning" 
                                                 style="width: {{ $process->memory_percent }}%"
                                                 title="{{ number_format($process->memory_percent, 1) }}%">
                                                {{ number_format($process->memory_percent, 1) }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $process->status }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<style>
    .progress {
        background-color: rgba(0,0,0,0.1);
    }
    .small-box {
        border-radius: 0.25rem;
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        margin-bottom: 20px;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .small-box .inner {
        padding: 15px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .small-box .small-box-footer {
        padding: 3px 0;
        text-align: center;
        background-color: rgba(0,0,0,.1);
    }
    .info-box-content {
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .table td, .table th {
        vertical-align: middle;
    }
    .progress-wrapper {
        margin-top: auto;
    }
    .card-wrapper {
        height: 100%;
        margin-bottom: 20px;
    }
</style>
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script>
        // Clipboard.js
        new ClipboardJS('.copy-btn');
        
        // Kopyalama başarılı bildirimi
        $('.copy-btn').on('click', function() {
            $(this).tooltip({
                title: 'Kopyalandı!',
                trigger: 'manual'
            }).tooltip('show');
            
            setTimeout(() => {
                $(this).tooltip('hide');
            }, 1000);
        });

        // DataTables
        $(document).ready(function() {
            // Updates tablosu
            $('#updates-table').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/tr.json'
                },
                pageLength: 10,
                order: [[0, 'asc']],
                columnDefs: [
                    { orderable: false, targets: [] }
                ]
            });

            // Ana Processes tablosu
            $('#processes-table').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/tr.json'
                },
                pageLength: 10,
                order: [[4, 'desc']], 
                columnDefs: [
                    { orderable: false, targets: [5] }
                ]
            });

            // CPU Processes Modal tablosu
            $('#cpu-processes-table').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/tr.json'
                },
                pageLength: 10,
                order: [[3, 'desc']], // CPU kullanımına göre sırala
                columnDefs: [
                    { orderable: false, targets: [5] }
                ]
            });

            // Memory Processes Modal tablosu
            $('#memory-processes-table').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/tr.json'
                },
                pageLength: 10,
                order: [[4, 'desc']], // Bellek kullanımına göre sırala
                columnDefs: [
                    { orderable: false, targets: [5] }
                ]
            });

            // Modal açıldığında DataTables yeniden çiz
            $('#cpuProcessesModal, #memoryProcessesModal').on('shown.bs.modal', function () {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust()
                    .responsive.recalc();
            });
        });
    </script>
@stop 