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
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">
                                <i class="fas fa-sync mr-1"></i>
                                Bekleyen Güncellemeler
                                <span class="badge badge-warning ml-1">{{ $lastSystemInfo->updateInfo->count }}</span>
                            </h3>
                            <div class="ml-auto">
                                <button type="button" class="btn btn-primary btn-sm update-selected" data-server-id="{{ $server->id }}" disabled>
                                    <i class="fas fa-download mr-1"></i> Seçilenleri Güncelle
                                </button>
                                <button type="button" class="btn btn-success btn-sm update-server" data-server-id="{{ $server->id }}">
                                    <i class="fas fa-download mr-1"></i> Tümünü Güncelle
                                </button>
                            </div>
                        </div>
                        @if($lastSystemInfo->updateInfo->packages->count() > 0)
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" id="updates-table">
                                        <thead>
                                            <tr>
                                                <th width="40" class="text-center">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="select-all">
                                                        <label class="custom-control-label" for="select-all"></label>
                                                    </div>
                                                </th>
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
                                                    <td class="text-center">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input package-select"
                                                                   id="package-{{ $loop->index }}"
                                                                   value="{{ $package->package }}">
                                                            <label class="custom-control-label" for="package-{{ $loop->index }}"></label>
                                                        </div>
                                                    </td>
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
                            </div>
                        @else
                            <div class="card-body">
                                <p class="text-muted mb-0">Güncelleme paketi bilgisi bulunamadı.</p>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Çalışan Süreçler tablosu -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-tasks mr-1"></i>
                            Çalışan Süreçler
                        </h3>
                    </div>
                    <div class="card-body">
                        <table id="processes-table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>PID</th>
                                    <th>İsim</th>
                                    <th>Kullanıcı</th>
                                    <th>CPU %</th>
                                    <th>Bellek %</th>
                                    <th>Durum</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lastSystemInfo->processInfo->processes as $process)
                                    <tr>
                                        <td>{{ $process->pid }}</td>
                                        <td>{{ $process->name }}</td>
                                        <td>{{ $process->username }}</td>
                                        <td>{{ number_format($process->cpu_percent, 1) }}%</td>
                                        <td>{{ number_format($process->memory_percent, 1) }}%</td>
                                        <td>
                                            <span class="badge badge-{{ App\Helpers\ProcessHelper::getStatusBadgeClass($process->status) }}" data-status="{{ $process->status }}">
                                                <i class="fas fa-{{ App\Helpers\ProcessHelper::getStatusIcon($process->status) }}"></i>
                                                {{ App\Helpers\ProcessHelper::getStatusLabel($process->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                @if($process->status == 'running')
                                                    <button type="button" class="btn btn-warning btn-sm process-control"
                                                            data-action="stop" data-pid="{{ $process->pid }}"
                                                            data-name="{{ $process->name }}">
                                                        <i class="fas fa-stop"></i> Durdur
                                                    </button>
                                                @endif
                                                @if($process->status == 'sleeping' || $process->status == 'stopped')
                                                    <button type="button" class="btn btn-success btn-sm process-control"
                                                            data-action="continue" data-pid="{{ $process->pid }}"
                                                            data-name="{{ $process->name }}">
                                                        <i class="fas fa-play"></i> Devam Et
                                                    </button>
                                                @endif
                                                <button type="button" class="btn btn-danger btn-sm process-control"
                                                        data-action="kill" data-pid="{{ $process->pid }}"
                                                        data-name="{{ $process->name }}">
                                                    <i class="fas fa-times"></i> Sonlandır
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

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

    <!-- SSH Terminal Modal -->
    <div class="modal fade" id="sshTerminalModal" tabindex="-1" role="dialog" aria-labelledby="sshTerminalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-dark text-light">
                    <h5 class="modal-title" id="sshTerminalModalLabel">
                        <i class="fas fa-terminal mr-2"></i>Terminal Oturumu
                    </h5>
                    <div class="card-tools">
                        <div class="btn-group">
                            <button type="button" class="btn btn-tool text-light" onclick="increaseFontSize()">
                                <i class="fas fa-search-plus"></i>
                            </button>
                            <button type="button" class="btn btn-tool text-light" onclick="decreaseFontSize()">
                                <i class="fas fa-search-minus"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <div id="terminal"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Servisler -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-cogs mr-1"></i>
                Servisler
            </h3>
        </div>
        <div class="card-body">
            <table id="services-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Servis</th>
                        <th>
                            Durum
                            <select class="form-control form-control-sm mt-2" id="status-filter-services">
                                <option value="">Tümü</option>
                                <option value="active">Aktif</option>
                                <option value="inactive">Pasif</option>
                                <option value="activating">Etkinleştiriliyor</option>
                                <option value="deactivating">Devre dışı bırakılıyor</option>
                                <option value="failed">Başarısız</option>
                            </select>
                        </th>
                        <th>Alt Durum</th>
                        <th>PID</th>
                        <th>Açıklama</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($services as $service)
                    <tr>
                        <td>{{ $service->name }}</td>
                        <td>
                            @php
                                $badge = match($service->active_state) {
                                    'active' => 'success',
                                    'inactive' => 'secondary',
                                    'activating' => 'info',
                                    'deactivating' => 'warning',
                                    'failed' => 'danger',
                                    default => 'light'
                                };

                                $label = match($service->active_state) {
                                    'active' => 'Aktif',
                                    'inactive' => 'Pasif',
                                    'activating' => 'Etkinleştiriliyor',
                                    'deactivating' => 'Devre Dışı Bırakılıyor',
                                    'failed' => 'Başarısız',
                                    default => $service->active_state
                                };
                            @endphp
                            <span class="badge badge-{{ $badge }}" data-status="{{ $service->active_state }}">{{ $label }}</span>
                        </td>
                        <td>{{ $service->sub_state }}</td>
                        <td>{{ $service->main_pid }}</td>
                        <td>{{ $service->description }}</td>
                        <td>
                            <div class="btn-group">
                                @if($service->active_state == 'active')
                                    <button type="button" class="btn btn-warning btn-sm service-control"
                                            data-action="stop" data-service="{{ $service->name }}"
                                            data-state="{{ $service->active_state }}">
                                        <i class="fas fa-stop"></i> Durdur
                                    </button>
                                    <button type="button" class="btn btn-info btn-sm service-control"
                                            data-action="restart" data-service="{{ $service->name }}"
                                            data-state="{{ $service->active_state }}">
                                        <i class="fas fa-sync"></i> Yeniden Başlat
                                    </button>
                                @endif
                                @if($service->active_state == 'inactive' || $service->active_state == 'failed')
                                    <button type="button" class="btn btn-success btn-sm service-control"
                                            data-action="start" data-service="{{ $service->name }}"
                                            data-state="{{ $service->active_state }}">
                                        <i class="fas fa-play"></i> Başlat
                                    </button>
                                @endif
                                <button type="button" class="btn btn-secondary btn-sm service-control"
                                        data-action="status" data-service="{{ $service->name }}"
                                        data-state="{{ $service->active_state }}">
                                    <i class="fas fa-info-circle"></i> Durum
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@stop

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/xterm/3.14.5/xterm.min.css">
<style>
    /* Terminal Container */
    #terminal {
        height: calc(100vh - 200px);
        min-height: 400px;
        background: #000;
        padding: 10px;
    }

    /* Terminal Araçları */
    .terminal-tools {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .terminal-tools .btn-tool {
        color: #fff;
        padding: 5px 10px;
        background: rgba(255,255,255,0.1);
        border-radius: 4px;
        margin-left: 5px;
        transition: all 0.2s;
    }

    .terminal-tools .btn-tool:hover {
        background: rgba(255,255,255,0.2);
    }

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
    <script src="https://cdn.jsdelivr.net/npm/xterm@4.19.0/lib/xterm.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/xterm-addon-fit@0.5.0/lib/xterm-addon-fit.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/xterm-addon-web-links@0.4.0/lib/xterm-addon-web-links.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/xterm-addon-search@0.8.0/lib/xterm-addon-search.min.js"></script>
    <script>
        $(function() {
            let fontSize = 14;
            let term = null;
            let ws = null;
            let fitAddon = null;
            let webLinksAddon = null;
            let searchAddon = null;

            // Terminal başlatma
            function initTerminal() {
                if (!term) {
                    // Terminal oluştur
                    term = new Terminal({
                        cursorBlink: true,
                        fontSize: fontSize,
                        fontFamily: 'Menlo, Monaco, "Courier New", monospace',
                        theme: {
                            background: '#000000',
                            foreground: '#ffffff',
                            cursor: '#ffffff',
                            selection: '#404040',
                            black: '#000000',
                            red: '#cc0000',
                            green: '#4e9a06',
                            yellow: '#c4a000',
                            blue: '#3465a4',
                            magenta: '#75507b',
                            cyan: '#06989a',
                            white: '#d3d7cf',
                            brightBlack: '#555753',
                            brightRed: '#ef2929',
                            brightGreen: '#8ae234',
                            brightYellow: '#fce94f',
                            brightBlue: '#729fcf',
                            brightMagenta: '#ad7fa8',
                            brightCyan: '#34e2e2',
                            brightWhite: '#eeeeec'
                        },
                        allowTransparency: true,
                        scrollback: 10000,
                        tabStopWidth: 8,
                        convertEol: true,
                        termName: 'xterm-256color'
                    });

                    // Eklentileri yükle
                    fitAddon = new window.FitAddon.FitAddon();
                    term.loadAddon(fitAddon);

                    webLinksAddon = new window.WebLinksAddon.WebLinksAddon();
                    term.loadAddon(webLinksAddon);

                    searchAddon = new window.SearchAddon.SearchAddon();
                    term.loadAddon(searchAddon);
                }
                return term;
            }

            // Font Boyutu Ayarlama
            window.increaseFontSize = function() {
                fontSize = Math.min(fontSize + 2, 24);
                updateTerminalFont();
            }

            window.decreaseFontSize = function() {
                fontSize = Math.max(fontSize - 2, 10);
                updateTerminalFont();
            }

            function updateTerminalFont() {
                if (term) {
                    term.setOption('fontSize', fontSize);
                    fitAddon.fit();
                }
            }

            // Terminal ve WebSocket yönetimi
            class SSHConnection {
                constructor(terminal, config) {
                    this.term = terminal;
                    this.config = config;
                    this.ws = null;
                    this.connected = false;
                    this.reconnectAttempts = 0;
                    this.maxReconnectAttempts = 3;
                    this.reconnectInterval = 2000;
                    this.pingInterval = null;
                    this.lastPingTime = null;
                    this.waitingForPassword = false;
                }

                connect() {
                    const protocol = window.location.protocol === 'https:' ? 'wss' : 'ws';
                    const url = `${protocol}://${window.location.hostname}:8090/?token={{ auth()->user()->api_token }}`;

                    this.ws = new WebSocket(url);
                    this.setupEventListeners();
                    this.setupPing();
                }

                setupPing() {
                    // Her 30 saniyede bir ping gönder
                    this.pingInterval = setInterval(() => {
                        if (this.connected && this.ws.readyState === WebSocket.OPEN) {
                            this.ws.send(JSON.stringify({ type: 'ping' }));
                            this.lastPingTime = Date.now();
                        }
                    }, 30000);
                }

                setupEventListeners() {
                    this.ws.onopen = () => {
                        this.connected = true;
                        this.reconnectAttempts = 0;
                        this.term.write('\r\n\x1b[32mBağlantı kuruldu...\x1b[0m\r\n');

                        this.ws.send(JSON.stringify({
                            type: 'connect',
                            host: this.config.host,
                            port: this.config.port,
                            username: this.config.username,
                            password: this.config.password,
                            cols: this.term.cols,
                            rows: this.term.rows
                        }));
                    };

                    this.ws.onmessage = (event) => {
                        const data = JSON.parse(event.data);
                        switch (data.type) {
                            case 'output':
                                this.term.write(data.data);
                                // Eğer çıktıda "password for" varsa, şifre isteniyor demektir
                                if (data.data.toLowerCase().includes('password for')) {
                                    this.waitingForPassword = true;
                                }
                                // Eğer çıktıda başarılı bir işlem mesajı varsa ve şifre bekleme durumu geçtiyse
                                else if (!this.waitingForPassword &&
                                    (data.data.includes('complete') || data.data.includes('finished'))) {
                                    // 3 saniye sonra sayfayı yenile
                                    setTimeout(() => {
                                        location.reload();
                                    }, 3000);
                                }
                                break;
                            case 'connected':
                                this.term.write('\r\n\x1b[32mSSH bağlantısı hazır...\x1b[0m\r\n');
                                if (this.config.command) {
                                    this.ws.send(JSON.stringify({
                                        type: 'input',
                                        input: this.config.command + '\n'
                                    }));
                                }
                                break;
                            case 'pong':
                                const latency = Date.now() - this.lastPingTime;
                                console.log(`WebSocket latency: ${latency}ms`);
                                break;
                            case 'error':
                                this.term.write(`\r\n\x1b[31mHata: ${data.message}\x1b[0m\r\n`);
                                break;
                        }
                    };

                    this.ws.onerror = (error) => {
                        console.error('WebSocket error:', error);
                        this.term.write('\r\n\x1b[31mBağlantı hatası!\x1b[0m\r\n');
                        this.tryReconnect();
                    };

                    this.ws.onclose = () => {
                        this.connected = false;
                        this.term.write('\r\n\x1b[31mBağlantı kapandı!\x1b[0m\r\n');
                        clearInterval(this.pingInterval);
                        this.tryReconnect();
                    };

                    this.term.onData(data => {
                        if (this.connected && this.ws.readyState === WebSocket.OPEN) {
                            this.ws.send(JSON.stringify({
                                type: 'input',
                                input: data
                            }));
                        }
                    });

                    this.term.onResize(size => {
                        if (this.connected && this.ws.readyState === WebSocket.OPEN) {
                            this.ws.send(JSON.stringify({
                                type: 'resize',
                                cols: size.cols,
                                rows: size.rows
                            }));
                        }
                    });

                    window.addEventListener('resize', () => {
                        fitAddon.fit();
                    });
                }

                tryReconnect() {
                    if (this.reconnectAttempts < this.maxReconnectAttempts) {
                        this.reconnectAttempts++;
                        this.term.write(`\r\n\x1b[33mYeniden bağlanılıyor (${this.reconnectAttempts}/${this.maxReconnectAttempts})...\x1b[0m\r\n`);
                        setTimeout(() => this.connect(), this.reconnectInterval);
                    } else {
                        this.term.write('\r\n\x1b[31mBağlantı kurulamadı! Sayfayı yenileyin.\x1b[0m\r\n');
                    }
                }

                disconnect() {
                    clearInterval(this.pingInterval);
                    if (this.ws) {
                        this.ws.close();
                    }
                }
            }

            // Modal açıldığında terminal başlat
            $('#sshTerminalModal').on('shown.bs.modal', function() {
                if (!term) {
                    term = initTerminal();
                    term.open(document.getElementById('terminal'));
                }

                fitAddon.fit();
                term.clear();

                const config = {
                    host: '{{ $server->ip_address }}',
                    port: {{ $server->ssh_port }},
                    username: '{{ $server->username }}',
                    password: '{{ $server->password }}',
                    command: window.currentCommand
                };

                const sshConnection = new SSHConnection(term, config);
                sshConnection.connect();

                $(this).data('sshConnection', sshConnection);
            });

            // Modal kapandığında sadece bağlantıyı kapat, terminal instance'ını koru
            $('#sshTerminalModal').on('hidden.bs.modal', function() {
                const sshConnection = $(this).data('sshConnection');
                if (sshConnection) {
                    sshConnection.disconnect();
                }
                if (term) {
                    term.clear();
                }
            });

            // Güncelleme butonları için event handler'lar
            $('.update-server').on('click', function() {
                window.currentCommand = 'sudo apt-get update && sudo apt-get upgrade -y';
                $('#sshTerminalModal').modal('show');
            });

            $('.update-selected').on('click', function() {
                const selectedPackages = [];
                $('.package-select:checked').each(function() {
                    selectedPackages.push($(this).val());
                });

                if (selectedPackages.length > 0) {
                    window.currentCommand = `sudo apt-get update && sudo apt-get install -y ${selectedPackages.join(' ')}`;
                    $('#sshTerminalModal').modal('show');
                }
            });

            // DataTables başlatma
            if (!$.fn.DataTable.isDataTable('#updates-table')) {
                $('#updates-table').DataTable({
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/tr.json'
                    },
                    pageLength: 10,
                    order: [[1, 'asc']], // Paket adına göre sırala
                    columnDefs: [
                        { orderable: false, targets: 0 } // İlk sütun (checkbox) sıralanamaz
                    ]
                });
            }

            // Processes tablosu
            if (!$.fn.DataTable.isDataTable('#processes-table')) {
                $('#processes-table').DataTable({
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/tr.json'
                    },
                    pageLength: 10,
                    order: [[4, 'desc']], // Bellek kullanımına göre sırala
                    columnDefs: [
                        {
                            orderable: false,
                            targets: [5],
                            render: function(data, type, row) {
                                if (type === 'filter') {
                                    return $(data).attr('data-status');
                                }
                                return data;
                            }
                        }
                    ]
                });
            }

            // Status filtresi için event listener
            $('#status-filter').on('change', function() {
                let table = $('#processes-table').DataTable();
                let val = $(this).val();
                table.column(5).search(val).draw();
            });

            // Services tablosu
            if (!$.fn.DataTable.isDataTable('#services-table')) {
                let servicesTable = $('#services-table').DataTable({
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/tr.json'
                    },
                    pageLength: 10,
                    order: [[0, 'asc']],
                    columnDefs: [{
                        targets: 1,
                        orderable: false,
                        render: function(data, type, row) {
                            if (type === 'filter') {
                                const tempDiv = document.createElement('div');
                                tempDiv.innerHTML = data;
                                const statusSpan = tempDiv.querySelector('span');
                                return statusSpan ? statusSpan.getAttribute('data-status') : '';
                            }
                            return data;
                        }
                    }]
                });

                // Services status filter event handler
                $('#status-filter-services').on('change', function() {
                    const selectedStatus = $(this).val();
                    if (selectedStatus === '') {
                        servicesTable.column(1).search('').draw();
                    } else {
                        servicesTable.column(1).search('^' + selectedStatus + '$', true, false).draw();
                    }
                });
            }

            // Tüm paketleri seç/kaldır
            $(document).on('change', '#select-all', function() {
                const isChecked = $(this).prop('checked');
                $('.package-select').prop('checked', isChecked);
                updateSelectedButton();
            });

            // Tekil paket seçimi
            $(document).on('change', '.package-select', function() {
                updateSelectedButton();
                // Tüm paketler seçili mi kontrol et
                const allChecked = $('.package-select:checked').length === $('.package-select').length;
                $('#select-all').prop('checked', allChecked);
            });

            // Seçili paketleri güncelle butonunun durumunu güncelle
            function updateSelectedButton() {
                const selectedCount = $('.package-select:checked').length;
                const updateButton = $('.update-selected');

                if (selectedCount > 0) {
                    updateButton.prop('disabled', false);
                    updateButton.html(`<i class="fas fa-download mr-1"></i> Seçilenleri Güncelle (${selectedCount})`);
                } else {
                    updateButton.prop('disabled', true);
                    updateButton.html('<i class="fas fa-download mr-1"></i> Seçilenleri Güncelle');
                }
            }

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

            // Süreç kontrol butonları için event handler
            $('.process-control').on('click', function() {
                const pid = $(this).data('pid');
                const action = $(this).data('action');
                const processName = $(this).data('name');
                let command = '';
                let confirmMessage = '';

                switch(action) {
                    case 'stop':
                        command = `sudo kill -STOP ${pid}`;
                        confirmMessage = `"${processName}" (PID: ${pid}) sürecini duraklatmak istediğinize emin misiniz?`;
                        break;
                    case 'continue':
                        command = `sudo kill -CONT ${pid}`;
                        confirmMessage = `"${processName}" (PID: ${pid}) sürecini devam ettirmek istediğinize emin misiniz?`;
                        break;
                    case 'kill':
                        command = `sudo kill -9 ${pid}`;
                        confirmMessage = `"${processName}" (PID: ${pid}) sürecini sonlandırmak istediğinize emin misiniz?`;
                        break;
                }

                if (confirm(confirmMessage)) {
                    window.currentCommand = command;
                    $('#sshTerminalModal').modal('show');
                }
            });

            // Servis kontrol butonları için event handler
            $('.service-control').on('click', function() {
                const service = $(this).data('service');
                const action = $(this).data('action');
                const state = $(this).data('state');
                let command = '';
                let confirmMessage = '';

                switch(action) {
                    case 'start':
                        command = `sudo systemctl start ${service}`;
                        confirmMessage = `"${service}" servisini başlatmak istediğinize emin misiniz?`;
                        break;
                    case 'stop':
                        command = `sudo systemctl stop ${service}`;
                        confirmMessage = `"${service}" servisini durdurmak istediğinize emin misiniz?`;
                        break;
                    case 'restart':
                        command = `sudo systemctl restart ${service}`;
                        confirmMessage = `"${service}" servisini yeniden başlatmak istediğinize emin misiniz?`;
                        break;
                    case 'status':
                        command = `sudo systemctl status ${service}`;
                        confirmMessage = `"${service}" servisinin durumunu görüntülemek istediğinize emin misiniz?`;
                        break;
                }

                if (confirm(confirmMessage)) {
                    window.currentCommand = command;
                    $('#sshTerminalModal').modal('show');
                }
            });
        });
    </script>
@stop
