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
                                    <div class="progress-bar bg-success cpu-progress" style="width: 0%">
                                        <span class="cpu-usage-percent">0</span>
                                    </div>
                                </div>
                                <p class="mt-2 mb-0"><span class="cpu-cores">0</span> Çekirdek</p>
                                <button type="button" class="btn btn-sm btn-outline-success mt-2" data-toggle="modal" data-target="#cpuProcessesModal">
                                    <i class="fas fa-tasks mr-1"></i> CPU Süreçleri
                                </button>
                            </div>
                            <div class="small-box-footer bg-light">
                                <span class="text-muted cpu-model"></span>
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
                                    <div class="progress-bar bg-warning memory-progress" style="width: 0%">
                                        <span class="memory-usage-percent">0</span>
                                    </div>
                                </div>
                                <p class="mt-2 mb-0">
                                    <span class="memory-used">0</span> GB /
                                    <span class="memory-total">0</span> GB
                                </p>
                                <button type="button" class="btn btn-sm btn-outline-warning mt-2" data-toggle="modal" data-target="#memoryProcessesModal">
                                    <i class="fas fa-tasks mr-1"></i> Bellek Süreçleri
                                </button>
                            </div>
                            <div class="small-box-footer bg-light">
                                <span class="text-muted">
                                    <span class="memory-free">0</span> GB Boş
                                </span>
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
                            <table id="disk-usage-table" class="table table-hover mb-0">
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
                                    <!-- Veriler JavaScript ile doldurulacak -->
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
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">
                            <i class="fas fa-tasks mr-1"></i>
                            Çalışan Süreçler
                            <small class="ml-2">
                                <span class="badge badge-success process-count-running">0</span> Çalışıyor
                                <span class="badge badge-info process-count-sleeping ml-2">0</span> Uyuyor
                                <span class="badge badge-warning process-count-stopped ml-2">0</span> Durduruldu
                                <span class="badge badge-danger process-count-zombie ml-2">0</span> Zombi
                                <small class="text-muted ml-2">(Son güncelleme: <span class="last-update-time"></span>)</small>
                            </small>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
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
                                    <!-- Veriler JavaScript ile doldurulacak -->
                            </tbody>
                        </table>
                        </div>
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
    <div class="modal fade" id="cpuProcessesModal" tabindex="-1" role="dialog" aria-labelledby="cpuProcessesModalLabel">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cpuProcessesModalLabel">
                        <i class="fas fa-microchip text-info mr-1"></i>
                        CPU Kullanımına Göre Süreçler (İlk 20)
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Kapat">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="cpu-processes-table" class="table table-bordered table-striped">
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
                                <!-- Veriler JavaScript ile doldurulacak -->
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bellek Süreçleri Modal -->
    <div class="modal fade" id="memoryProcessesModal" tabindex="-1" role="dialog" aria-labelledby="memoryProcessesModalLabel">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="memoryProcessesModalLabel">
                        <i class="fas fa-memory text-info mr-1"></i>
                        Bellek Kullanımına Göre Süreçler (İlk 20)
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Kapat">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="memory-processes-table" class="table table-bordered table-striped">
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
                                <!-- Veriler JavaScript ile doldurulacak -->
                        </tbody>
                    </table>
                    </div>
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
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-cogs mr-1"></i>
                Servisler
                <small class="ml-2">
                    <span class="badge badge-success services-count-active">0</span> Aktif
                    <span class="badge badge-secondary services-count-inactive ml-2">0</span> Pasif
                    <span class="badge badge-danger services-count-failed ml-2">0</span> Başarısız
                    <small class="text-muted ml-2">(Son güncelleme: <span class="last-update-time"></span>)</small>
                </small>
            </h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
            <table id="services-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Servis</th>
                            <th>Durum</th>
                        <th>Alt Durum</th>
                        <th>PID</th>
                        <th>Açıklama</th>
                            <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                        <!-- Veriler JavaScript ile doldurulacak -->
                </tbody>
            </table>
            </div>
        </div>
    </div>

    <!-- Süreç Detay Modal -->
    <div class="modal fade" id="processDetailModal" tabindex="-1" role="dialog" aria-labelledby="processDetailModalLabel">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="processDetailModalLabel">
                        <i class="fas fa-info-circle text-info mr-1"></i>
                        Süreç Detayları
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Kapat">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Temel Bilgiler</h3>
                                </div>
                                <div class="card-body">
                                    <table class="table">
                                        <tr>
                                            <th>PID</th>
                                            <td id="process-pid"></td>
                                        </tr>
                                        <tr>
                                            <th>İsim</th>
                                            <td id="process-name"></td>
                                        </tr>
                                        <tr>
                                            <th>Durum</th>
                                            <td id="process-status"></td>
                                        </tr>
                                        <tr>
                                            <th>Kullanıcı</th>
                                            <td id="process-username"></td>
                                        </tr>
                                        <tr>
                                            <th>CPU %</th>
                                            <td id="process-cpu"></td>
                                        </tr>
                                        <tr>
                                            <th>Bellek %</th>
                                            <td id="process-memory"></td>
                                        </tr>
                                        <tr>
                                            <th>Başlangıç Zamanı</th>
                                            <td id="process-create-time"></td>
                                        </tr>
                                        <tr>
                                            <th>Thread Sayısı</th>
                                            <td id="process-threads"></td>
                                        </tr>
                                        <tr>
                                            <th>Nice Değeri</th>
                                            <td id="process-nice"></td>
                                        </tr>
                                        <tr>
                                            <th>Ebeveyn PID</th>
                                            <td id="process-ppid"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Bellek Bilgileri</h3>
                                </div>
                                <div class="card-body">
                                    <table class="table">
                                        <tr>
                                            <th>RSS</th>
                                            <td id="process-rss"></td>
                                        </tr>
                                        <tr>
                                            <th>VMS</th>
                                            <td id="process-vms"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="card mt-3">
                                <div class="card-header">
                                    <h3 class="card-title">Context Switch Bilgileri</h3>
                                </div>
                                <div class="card-body">
                                    <table class="table">
                                        <tr>
                                            <th>Gönüllü</th>
                                            <td id="process-ctx-voluntary"></td>
                    </tr>
                                        <tr>
                                            <th>Zorunlu</th>
                                            <td id="process-ctx-involuntary"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Komut Satırı</h3>
                                </div>
                                <div class="card-body">
                                    <pre id="process-cmdline" class="bg-light p-2" style="white-space: pre-wrap;"></pre>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Açık Dosyalar</h3>
                                </div>
                                <div class="card-body">
                                    <ul id="process-open-files" class="list-group">
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Ağ Bağlantıları</h3>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>FD</th>
                                                    <th>Tip</th>
                                                    <th>Yerel Adres</th>
                                                    <th>Durum</th>
                                                </tr>
                                            </thead>
                                            <tbody id="process-connections">
                </tbody>
            </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                </div>
            </div>
        </div>
    </div>

@stop

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/xterm/3.14.5/xterm.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
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

    /* Yeni eklenen satırlar için highlight efekti */
    @keyframes highlightNew {
        0% {
            background-color: rgba(0, 123, 255, 0.1);
        }
        100% {
            background-color: transparent;
        }
    }

    .highlight-new {
        animation: highlightNew 1s ease-in-out;
    }

    /* DataTables güncelleme efekti */
    .dataTable tbody tr {
        transition: all 0.3s ease;
    }

    /* Tablo güncelleme göstergesi */
    .last-update-time {
        font-size: 0.875rem;
        color: #6c757d;
        margin-left: 0.5rem;
    }

    /* DataTables güncelleme efekti */
    .dataTable tbody tr {
        transition: all 0.3s ease;
    }

    /* Buton grupları için stil */
    .btn-group {
        box-shadow: 0 1px 3px rgba(0,0,0,0.12);
        border-radius: 4px;
        overflow: hidden;
    }

    .btn-group .btn {
        border: none;
        padding: 0.4rem;
        width: 32px;
        height: 32px;
        margin: 0;
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-group .btn:not(:last-child)::after {
        content: '';
        position: absolute;
        right: 0;
        top: 20%;
        height: 60%;
        width: 1px;
        background-color: rgba(255,255,255,0.3);
    }

    .btn-group .btn i {
        font-size: 0.875rem;
        margin: 0;
    }

    /* Buton hover efektleri */
    .btn-info:hover {
        background-color: #138496;
    }

    .btn-warning:hover {
        background-color: #e0a800;
    }

    .btn-success:hover {
        background-color: #218838;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }

    /* Buton aktif durumu */
    .btn-group .btn:active {
        transform: translateY(1px);
    }

    /* Buton geçiş efekti */
    .btn {
        transition: all 0.2s ease-in-out;
    }

    /* Buton tooltip */
    .btn[data-toggle="tooltip"] {
        position: relative;
    }

    /* Buton disabled durumu */
    .btn:disabled {
        cursor: not-allowed;
        opacity: 0.6;
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
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
                const serverId = $(this).data('server-id');
                window.currentCommand = 'sudo apt-get update && sudo apt-get upgrade -y';
                $('#sshTerminalModal').modal('show');
            });

            $('.update-selected').on('click', function() {
                const serverId = $(this).data('server-id');
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

            // WebSocket bağlantısı ve yönetimi
            function initWebSocket() {
                const protocol = window.location.protocol === 'https:' ? 'wss' : 'ws';
                const url = `${protocol}://{{ $server->ip_address }}:{{ $server->ws_port }}/?token={{ auth()->user()->api_token }}`;
                const ws = new WebSocket(url);

                ws.onopen = function() {
                    console.log('WebSocket bağlantısı açıldı');

                    // İlk verileri al
                    ws.send(JSON.stringify({ command: 'process' }));
                    ws.send(JSON.stringify({ command: 'services' }));
                    ws.send(JSON.stringify({ command: 'resources' }));

                    // Her 5 saniyede bir güncelle
                    setInterval(() => {
                        if (ws.readyState === WebSocket.OPEN) {
                            ws.send(JSON.stringify({ command: 'process' }));
                            ws.send(JSON.stringify({ command: 'services' }));
                            ws.send(JSON.stringify({ command: 'resources' }));
                        }
                    }, 5000);
                };

                ws.onmessage = function(event) {
                    try {
                        const response = JSON.parse(event.data);

                        if (response.command === 'process_info' && response.success) {
                            showProcessDetails(response.data);
                        }
                        else if (response.command === 'service_status' && response.success) {
                            showServiceDetails(response.data);
                        }
                        else if (response.command === 'resources' && response.success) {
                            updateResourceUsage(response.data);
                        }
                        else if (response.success) {
                            switch(response.command) {
                                case 'process':
                                    updateProcessesTable(response.data);
                                    if ($('#cpuProcessesModal').is(':visible')) {
                                        updateCPUProcessesModal(response.data.processes);
                                    }
                                    if ($('#memoryProcessesModal').is(':visible')) {
                                        updateMemoryProcessesModal(response.data.processes);
                                    }
                                    window.lastProcessData = response.data.processes;
                                    break;
                                case 'services':
                                    updateServicesTable(response.data);
                                    break;
                                case 'resources':
                                    updateResourceUsage(response.data);
                                    break;
                                case 'service_start':
                                    Toast.fire({
                                        icon: 'success',
                                        title: 'Servis başlatıldı'
                                    });
                                    setTimeout(() => {
                                        if (ws.readyState === WebSocket.OPEN) {
                                            ws.send(JSON.stringify({ command: 'services' }));
                                        }
                                    }, 1000);
                                    break;
                                case 'service_stop':
                                    Toast.fire({
                                        icon: 'success',
                                        title: 'Servis durduruldu'
                                    });
                                    setTimeout(() => {
                                        if (ws.readyState === WebSocket.OPEN) {
                                            ws.send(JSON.stringify({ command: 'services' }));
                                        }
                                    }, 1000);
                                    break;
                                case 'service_restart':
                                    Toast.fire({
                                        icon: 'success',
                                        title: 'Servis yeniden başlatıldı'
                                    });
                                    setTimeout(() => {
                                        if (ws.readyState === WebSocket.OPEN) {
                                            ws.send(JSON.stringify({ command: 'services' }));
                                        }
                                    }, 1000);
                                    break;
                                case 'kill':
                                    Toast.fire({
                                        icon: 'success',
                                        title: 'Süreç sonlandırıldı'
                                    });
                                    setTimeout(() => {
                                        if (ws.readyState === WebSocket.OPEN) {
                                            ws.send(JSON.stringify({ command: 'process' }));
                                        }
                                    }, 1000);
                                    break;
                                case 'stop':
                                    Toast.fire({
                                        icon: 'success',
                                        title: 'Süreç duraklatıldı'
                                    });
                                    setTimeout(() => {
                                        if (ws.readyState === WebSocket.OPEN) {
                                            ws.send(JSON.stringify({ command: 'process' }));
                                        }
                                    }, 1000);
                                    break;
                                case 'continue':
                                    Toast.fire({
                                        icon: 'success',
                                        title: 'Süreç devam ettiriliyor'
                                    });
                                    setTimeout(() => {
                                        if (ws.readyState === WebSocket.OPEN) {
                                            ws.send(JSON.stringify({ command: 'process' }));
                                        }
                                    }, 1000);
                                    break;
                            }
                        }
                        else {
                            Toast.fire({
                                icon: 'error',
                                title: 'Hata!',
                                text: response.stderr || 'Bir hata oluştu.'
                            });
                        }
                    } catch (e) {
                        console.error('WebSocket mesaj hatası:', e);
                        Toast.fire({
                            icon: 'error',
                            title: 'İşlem başarısız',
                            text: 'Beklenmeyen bir hata oluştu.'
                        });
                    }
                };

                ws.onerror = function(error) {
                    console.error('WebSocket hatası:', error);
                };

                ws.onclose = function() {
                    console.log('WebSocket bağlantısı kapandı');
                    // 5 saniye sonra yeniden bağlan
                    setTimeout(initWebSocket, 5000);
                };

                // Global WebSocket nesnesini sakla
                window.serverWs = ws;
            }

            // Sayfa yüklendiğinde WebSocket bağlantısını başlat
            $(document).ready(function() {
                initWebSocket();
            });

            // Süreç kontrol butonları için event handler
            $(document).on('click', '.process-control', function() {
                const pid = $(this).data('pid');
                const action = $(this).data('action');
                const processName = $(this).data('name');
                let title = '';
                let text = '';
                let icon = 'warning';

                switch(action) {
                    case 'stop':
                        title = 'Süreci Duraklat';
                        text = `"${processName}" (PID: ${pid}) sürecini duraklatmak istediğinize emin misiniz?`;
                        break;
                    case 'continue':
                        title = 'Süreci Devam Ettir';
                        text = `"${processName}" (PID: ${pid}) sürecini devam ettirmek istediğinize emin misiniz?`;
                        icon = 'info';
                        break;
                    case 'kill':
                        title = 'Süreci Sonlandır';
                        text = `"${processName}" (PID: ${pid}) sürecini sonlandırmak istediğinize emin misiniz?`;
                        icon = 'error';
                        break;
                }

                Swal.fire({
                    title: title,
                    text: text,
                    icon: icon,
                    showCancelButton: true,
                    confirmButtonText: 'Evet',
                    cancelButtonText: 'İptal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (window.serverWs && window.serverWs.readyState === WebSocket.OPEN) {
                            window.serverWs.send(JSON.stringify({
                                command: action,
                                pid: pid
                            }));

                            Toast.fire({
                                icon: 'info',
                                title: 'İşlem gerçekleştiriliyor...'
                            });
                        }
                    }
                });
            });

            // Süreç detay butonu için event handler
            $(document).on('click', '.process-info', function() {
                const pid = $(this).data('pid');

                if (window.serverWs && window.serverWs.readyState === WebSocket.OPEN) {
                    window.serverWs.send(JSON.stringify({
                        command: 'process_info',
                        pid: pid
                    }));
                }
            });

            // SweetAlert2 Toast Mixin tanımı
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });

            // Servis kontrol butonları için event handler
            $(document).on('click', '.service-control', function() {
                const service = $(this).data('service');
                const action = $(this).data('action');
                const state = $(this).data('state');
                let title = '';
                let text = '';
                let icon = 'warning';

                switch(action) {
                    case 'start':
                        title = 'Servisi Başlat';
                        text = `"${service}" servisini başlatmak istediğinize emin misiniz?`;
                        break;
                    case 'stop':
                        title = 'Servisi Durdur';
                        text = `"${service}" servisini durdurmak istediğinize emin misiniz?`;
                        break;
                    case 'restart':
                        title = 'Servisi Yeniden Başlat';
                        text = `"${service}" servisini yeniden başlatmak istediğinize emin misiniz?`;
                        break;
                    case 'status':
                        title = 'Servis Durumu';
                        text = `"${service}" servisinin durumunu görüntülemek istediğinize emin misiniz?`;
                        icon = 'info';
                        break;
                }

                Swal.fire({
                    title: title,
                    text: text,
                    icon: icon,
                    showCancelButton: true,
                    confirmButtonText: 'Evet',
                    cancelButtonText: 'İptal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (window.serverWs && window.serverWs.readyState === WebSocket.OPEN) {
                            window.serverWs.send(JSON.stringify({
                                command: `service_${action}`,
                                name: service
                            }));

                            Toast.fire({
                                icon: 'info',
                                title: 'İşlem gerçekleştiriliyor...'
                            });
                        }
                    }
                });
            });

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

            // Süreçleri tabloda güncelle
            function updateProcessesTable(data) {
                // İstatistikleri güncelle
                $('.process-count-running').text(data.stats.running);
                $('.process-count-sleeping').text(data.stats.sleeping);
                $('.process-count-stopped').text(data.stats.stopped);
                $('.process-count-zombie').text(data.stats.zombie);

                // Son güncelleme zamanını güncelle
                const now = new Date();
                const timeString = now.toLocaleTimeString('tr-TR');
                $('.last-update-time').text(timeString);

                // Tabloyu güncelle
                const table = $('#processes-table').DataTable();
                const currentPage = table.page();
                const currentScroll = $(window).scrollTop();

                table.clear();

                data.processes.forEach(process => {
                    const badge = getProcessBadgeClass(process.status);
                    const label = getProcessStatusLabel(process.status);
                    const buttons = getProcessButtons(process);

                    table.row.add([
                        process.pid,
                        process.name,
                        process.username,
                        `<div class="d-flex align-items-center">
                            <div class="progress flex-grow-1" style="height: 6px;">
                                <div class="progress-bar ${getCPUProgressBarClass(process.cpu_percent)}"
                                     role="progressbar"
                                     style="width: ${process.cpu_percent}%;"
                                     aria-valuenow="${process.cpu_percent}"
                                     aria-valuemin="0"
                                     aria-valuemax="100">
                                </div>
                            </div>
                            <span class="ml-2" style="min-width: 45px;">${process.cpu_percent.toFixed(1)}%</span>
                        </div>`,
                        `<div class="d-flex align-items-center">
                            <div class="progress flex-grow-1" style="height: 6px;">
                                <div class="progress-bar ${getMemoryProgressBarClass(process.memory_percent)}"
                                     role="progressbar"
                                     style="width: ${process.memory_percent}%;"
                                     aria-valuenow="${process.memory_percent}"
                                     aria-valuemin="0"
                                     aria-valuemax="100">
                                </div>
                            </div>
                            <span class="ml-2" style="min-width: 45px;">${process.memory_percent.toFixed(1)}%</span>
                        </div>`,
                        `<span class="badge badge-${badge}" data-status="${process.status}">
                            <i class="fas fa-${getProcessStatusIcon(process.status)}"></i>
                            ${label}
                        </span>`,
                        buttons
                    ]);
                });

                // Tabloyu yeniden çiz ve önceki sayfaya dön
                table.draw(false);
                table.page(currentPage).draw('page');

                // Scroll pozisyonunu koru
                $(window).scrollTop(currentScroll);
            }

            // Progress bar renk sınıflarını belirle
            function getCPUProgressBarClass(percent) {
                if (percent >= 90) return 'bg-danger';
                if (percent >= 70) return 'bg-warning';
                if (percent >= 50) return 'bg-info';
                return 'bg-success';
            }

            function getMemoryProgressBarClass(percent) {
                if (percent >= 90) return 'bg-danger';
                if (percent >= 70) return 'bg-warning';
                if (percent >= 50) return 'bg-info';
                return 'bg-success';
            }

            // Servisleri tabloda güncelle
            function updateServicesTable(data) {
                // İstatistikleri güncelle
                $('.services-count-active').text(data.stats.active);
                $('.services-count-inactive').text(data.stats.inactive);
                $('.services-count-failed').text(data.stats.failed);

                // Son güncelleme zamanını güncelle
                const now = new Date();
                const timeString = now.toLocaleTimeString('tr-TR');
                $('.last-update-time').text(timeString);

                // Tabloyu güncelle
                const table = $('#services-table').DataTable();
                const currentPage = table.page();
                const currentScroll = $(window).scrollTop();

                table.clear();

                data.services.forEach(service => {
                    const badge = getServiceBadgeClass(service.active_state);
                    const label = getServiceStatusLabel(service.active_state);
                    const buttons = getServiceButtons(service);

                    table.row.add([
                        service.name,
                        `<span class="badge badge-${badge}" data-status="${service.active_state}">${label}</span>`,
                        service.sub_state,
                        service.main_pid || '-',
                        service.description || '-',
                        buttons
                    ]);
                });

                // Tabloyu yeniden çiz ve önceki sayfaya dön
                table.draw(false);
                table.page(currentPage).draw('page');

                // Scroll pozisyonunu koru
                $(window).scrollTop(currentScroll);
            }

            // Süreç durumu için badge class
            function getProcessBadgeClass(status) {
                switch(status) {
                    case 'running': return 'success';
                    case 'sleeping': return 'info';
                    case 'stopped': return 'warning';
                    case 'zombie': return 'danger';
                    default: return 'secondary';
                }
            }

            // Süreç durumu için etiket
            function getProcessStatusLabel(status) {
                switch(status) {
                    case 'running': return 'Çalışıyor';
                    case 'sleeping': return 'Uyuyor';
                    case 'stopped': return 'Durduruldu';
                    case 'zombie': return 'Zombi';
                    default: return status;
                }
            }

            // Süreç durumu için ikon
            function getProcessStatusIcon(status) {
                switch(status) {
                    case 'running': return 'play';
                    case 'sleeping': return 'pause';
                    case 'stopped': return 'stop';
                    case 'zombie': return 'skull';
                    default: return 'question';
                }
            }

            // Süreç butonlarını oluştur
            function getProcessButtons(process) {
                let buttons = '<div class="btn-group" role="group" aria-label="Süreç Kontrolleri">';

                // Detay butonu
                buttons += `
                    <button type="button" class="btn btn-info btn-sm process-info"
                            data-pid="${process.pid}" data-name="${process.name}"
                            data-toggle="tooltip" title="Süreç Detayları">
                        <i class="fas fa-info-circle"></i>
                    </button>
                `;

                if (process.status === 'running') {
                    buttons += `
                        <button type="button" class="btn btn-warning btn-sm process-control"
                                data-action="stop" data-pid="${process.pid}"
                                data-name="${process.name}"
                                data-toggle="tooltip" title="Süreci Durdur">
                            <i class="fas fa-stop"></i>
                        </button>
                    `;
                }

                if (process.status === 'sleeping' || process.status === 'stopped') {
                    buttons += `
                        <button type="button" class="btn btn-success btn-sm process-control"
                                data-action="continue" data-pid="${process.pid}"
                                data-name="${process.name}"
                                data-toggle="tooltip" title="Süreci Devam Ettir">
                            <i class="fas fa-play"></i>
                        </button>
                    `;
                }

                buttons += `
                    <button type="button" class="btn btn-danger btn-sm process-control"
                            data-action="kill" data-pid="${process.pid}"
                            data-name="${process.name}"
                            data-toggle="tooltip" title="Süreci Sonlandır">
                        <i class="fas fa-times"></i>
                    </button>
                </div>`;

                return buttons;
            }

            // Servis durumu için badge class
            function getServiceBadgeClass(state) {
                switch(state) {
                    case 'active': return 'success';
                    case 'inactive': return 'secondary';
                    case 'failed': return 'danger';
                    default: return 'light';
                }
            }

            // Servis durumu için etiket
            function getServiceStatusLabel(state) {
                switch(state) {
                    case 'active': return 'Aktif';
                    case 'inactive': return 'Pasif';
                    case 'failed': return 'Başarısız';
                    default: return state;
                }
            }

            // Servis butonlarını oluştur
            function getServiceButtons(service) {
                let buttons = '<div class="btn-group" role="group" aria-label="Servis Kontrolleri">';

                if (service.active_state === 'active') {
                    buttons += `
                        <button type="button" class="btn btn-warning btn-sm service-control"
                                data-action="stop" data-service="${service.name}"
                                data-state="${service.active_state}"
                                data-toggle="tooltip" title="Servisi Durdur">
                            <i class="fas fa-stop"></i>
                        </button>
                        <button type="button" class="btn btn-info btn-sm service-control"
                                data-action="restart" data-service="${service.name}"
                                data-state="${service.active_state}"
                                data-toggle="tooltip" title="Servisi Yeniden Başlat">
                            <i class="fas fa-sync"></i>
                        </button>
                        <button type="button" class="btn btn-secondary btn-sm service-control"
                                data-action="status" data-service="${service.name}"
                                data-state="${service.active_state}"
                                data-toggle="tooltip" title="Servis Durumunu Görüntüle">
                            <i class="fas fa-info-circle"></i>
                        </button>
                    `;
                } else {
                    buttons += `
                        <button type="button" class="btn btn-success btn-sm service-control"
                                data-action="start" data-service="${service.name}"
                                data-state="${service.active_state}"
                                data-toggle="tooltip" title="Servisi Başlat">
                            <i class="fas fa-play"></i>
                        </button>
                    `;
                }

                buttons += '</div>';
                return buttons;
            }

            // Süreç detaylarını göster
            function showProcessDetails(data) {
                // Temel bilgiler
                $('#process-pid').text(data.pid);
                $('#process-name').text(data.name);
                $('#process-status').html(`<span class="badge badge-${getProcessBadgeClass(data.status)}">${getProcessStatusLabel(data.status)}</span>`);
                $('#process-username').text(data.username);
                $('#process-cpu').text(data.cpu_percent.toFixed(1) + '%');
                $('#process-memory').text(data.memory_percent.toFixed(1) + '%');
                $('#process-create-time').text(new Date(data.create_time).toLocaleString('tr-TR'));
                $('#process-threads').text(data.num_threads);
                $('#process-nice').text(data.nice);
                $('#process-ppid').text(data.ppid);

                // Bellek bilgileri
                $('#process-rss').text(formatBytes(data.memory_info.rss));
                $('#process-vms').text(formatBytes(data.memory_info.vms));

                // Context switch bilgileri
                $('#process-ctx-voluntary').text(data.num_ctx_switches.voluntary.toLocaleString('tr-TR'));
                $('#process-ctx-involuntary').text(data.num_ctx_switches.involuntary.toLocaleString('tr-TR'));

                // Komut satırı
                $('#process-cmdline').text(data.cmdline.join(' '));

                // Açık dosyalar
                const filesList = $('#process-open-files');
                filesList.empty();
                data.open_files.forEach(file => {
                    filesList.append(`
                        <li class="list-group-item">
                            <i class="fas fa-file text-muted mr-2"></i>${file}
                        </li>
                    `);
                });

                // Ağ bağlantıları
                const connectionsTable = $('#process-connections');
                connectionsTable.empty();
                data.connections.forEach(conn => {
                    const localAddr = conn.laddr ? `${conn.laddr.ip}:${conn.laddr.port}` : '-';
                    connectionsTable.append(`
                        <tr>
                            <td>${conn.fd}</td>
                            <td>${getConnectionType(conn.type)}</td>
                            <td>${localAddr}</td>
                            <td>${conn.status || '-'}</td>
                        </tr>
                    `);
                });

                // Modalı göster
                $('#processDetailModal').modal('show');
            }

            // Bağlantı tipini formatla
            function getConnectionType(type) {
                switch(type) {
                    case 1: return 'TCP';
                    case 2: return 'UDP';
                    default: return type;
                }
            }

            // Byte formatla
            function formatBytes(bytes) {
                if (bytes === 0) return '0 B';
                const k = 1024;
                const sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            // Servis detaylarını göster
            function showServiceDetails(data) {
                Swal.fire({
                    title: `${data.name} Servis Detayları`,
                    html: `
                        <div class="text-left">
                            <div class="mb-3">
                                <h6 class="font-weight-bold">Durum</h6>
                                <span class="badge badge-${data.active ? 'success' : 'danger'} mr-2">
                                    ${data.status}
                                </span>
                            </div>
                            <div class="mb-3">
                                <h6 class="font-weight-bold">Açıklama</h6>
                                <p>${data.description}</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="font-weight-bold">Sistem Bilgileri</h6>
                                <ul class="list-unstyled">
                                    <li><strong>PID:</strong> ${data.pid}</li>
                                    <li><strong>Bellek Kullanımı:</strong> ${data.memory}</li>
                                    <li><strong>CPU Kullanımı:</strong> ${data.cpu}</li>
                                    <li><strong>Başlangıç:</strong> ${data.since}</li>
                                    <li><strong>Thread Sayısı:</strong> ${data.tasks}</li>
                                </ul>
                            </div>
                            <div class="mb-3">
                                <h6 class="font-weight-bold">Son Loglar</h6>
                                <pre class="bg-light p-2" style="max-height: 200px; overflow-y: auto; font-size: 12px;">
${data.log.join('\n')}
                                </pre>
                            </div>
                        </div>
                    `,
                    width: '600px',
                    showCloseButton: true,
                    showConfirmButton: false
                });
            }

            // CPU ve Bellek süreçleri için tablolama fonksiyonları
            function updateCPUProcessesModal(processes) {
                const table = $('#cpu-processes-table').DataTable();
                table.clear();

                // CPU kullanımına göre sırala
                processes.sort((a, b) => b.cpu_percent - a.cpu_percent);

                // İlk 20 süreci al
                processes.slice(0, 20).forEach(process => {
                    const badge = getProcessBadgeClass(process.status);
                    const label = getProcessStatusLabel(process.status);
                    const buttons = getProcessButtons(process);

                    table.row.add([
                        process.pid,
                        process.name,
                        process.username,
                        `${process.cpu_percent.toFixed(1)}%`,
                        `${process.memory_percent.toFixed(1)}%`,
                        `<span class="badge badge-${badge}" data-status="${process.status}">
                            <i class="fas fa-${getProcessStatusIcon(process.status)}"></i>
                            ${label}
                        </span>`,
                        buttons
                    ]);
                });

                table.draw(false);
            }

            function updateMemoryProcessesModal(processes) {
                const table = $('#memory-processes-table').DataTable();
                table.clear();

                // Bellek kullanımına göre sırala
                processes.sort((a, b) => b.memory_percent - a.memory_percent);

                // İlk 20 süreci al
                processes.slice(0, 20).forEach(process => {
                    const badge = getProcessBadgeClass(process.status);
                    const label = getProcessStatusLabel(process.status);
                    const buttons = getProcessButtons(process);

                    table.row.add([
                        process.pid,
                        process.name,
                        process.username,
                        `${process.cpu_percent.toFixed(1)}%`,
                        `${process.memory_percent.toFixed(1)}%`,
                        `<span class="badge badge-${badge}" data-status="${process.status}">
                            <i class="fas fa-${getProcessStatusIcon(process.status)}"></i>
                            ${label}
                        </span>`,
                        buttons
                    ]);
                });

                table.draw(false);
            }

            // Tooltip'leri aktifleştir
            $(document).ready(function() {
                $('body').tooltip({
                    selector: '[data-toggle="tooltip"]',
                    trigger: 'hover',
                    placement: 'top'
            });
        });

            // Modal yönetimi için JavaScript
            $(document).ready(function() {
                const processDetailModal = $('#processDetailModal');

                // Modal açıldığında
                processDetailModal.on('shown.bs.modal', function () {
                    // Modal içindeki ilk odaklanabilir elemana odaklan
                    $(this).find('[autofocus]').focus();
                });

                // Modal kapanmadan önce
                processDetailModal.on('hide.bs.modal', function () {
                    // Modal'ı açan butona geri dön
                    setTimeout(() => {
                        $('.process-info').focus();
                    }, 0);
                });

                // ESC tuşu ile kapatma
                processDetailModal.on('keydown', function(e) {
                    if (e.key === 'Escape') {
                        processDetailModal.modal('hide');
                    }
                });

                // Modal içinde tab tuşu yönetimi
                processDetailModal.on('keydown', function(e) {
                    if (e.key === 'Tab') {
                        const focusableElements = $(this).find('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
                        const firstElement = focusableElements.first();
                        const lastElement = focusableElements.last();

                        if (e.shiftKey) { // Shift + Tab
                            if (document.activeElement === firstElement[0]) {
                                e.preventDefault();
                                lastElement.focus();
                            }
                        } else { // Tab
                            if (document.activeElement === lastElement[0]) {
                                e.preventDefault();
                                firstElement.focus();
                            }
                        }
                    }
                });
            });

            // Processes tablosu
            if (!$.fn.DataTable.isDataTable('#processes-table')) {
                $('#processes-table').DataTable({
    language: {
                        url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/tr.json'
    },
    pageLength: 10,
                    order: [[4, 'desc']], // Bellek kullanımına göre sırala
    columnDefs: [{
                        targets: [5, 6],
                        orderable: false
                    }],
                    drawCallback: function() {
                        // Tablo her güncellendiğinde çalışır
                        $(this).find('tbody tr').addClass('highlight-new');
                        setTimeout(() => {
                            $(this).find('tbody tr').removeClass('highlight-new');
                        }, 1000);
                    },
                    dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                         "<'row'<'col-sm-12'tr>>" +
                         "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"
                });
            }

            // Services tablosu
            if (!$.fn.DataTable.isDataTable('#services-table')) {
                $('#services-table').DataTable({
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/tr.json'
                    },
                    pageLength: 10,
                    order: [[0, 'asc']], // Servis adına göre sırala
                    columnDefs: [{
                        targets: [1, 5],
                        orderable: false
                    }],
                    drawCallback: function() {
                        // Tablo her güncellendiğinde çalışır
                        $(this).find('tbody tr').addClass('highlight-new');
                        setTimeout(() => {
                            $(this).find('tbody tr').removeClass('highlight-new');
                        }, 1000);
                    },
                    dom: "<'row'<'col-sm-12'tr>>" +
                         "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"
                });
            }

            // Modal event listener'ları
            $('#cpuProcessesModal').on('show.bs.modal', function() {
                if (window.lastProcessData) {
                    updateCPUProcessesModal(window.lastProcessData);
                }
            });

            $('#memoryProcessesModal').on('show.bs.modal', function() {
                if (window.lastProcessData) {
                    updateMemoryProcessesModal(window.lastProcessData);
                }
            });

            // Kaynak kullanımını güncelle
            function updateResourceUsage(data) {
                // CPU Kullanımı
                const cpuUsage = data.cpu.usage_percent;
                const cpuCores = data.cpu.cores;
                $('.cpu-usage-percent').text(cpuUsage.toFixed(1) + '%');
                $('.cpu-cores').text(cpuCores);
                $('.cpu-progress').css('width', cpuUsage + '%');

                // Bellek Kullanımı
                const memTotal = data.memory.total_gb.toFixed(2);
                const memUsed = data.memory.used_gb.toFixed(2);
                const memFree = data.memory.free_gb.toFixed(2);
                const memUsage = data.memory.usage_percent;

                $('.memory-total').text(memTotal + ' GB');
                $('.memory-used').text(memUsed + ' GB');
                $('.memory-free').text(memFree + ' GB');
                $('.memory-usage-percent').text(memUsage.toFixed(1) + '%');
                $('.memory-progress').css('width', memUsage + '%');

                // Disk Kullanımı Tablosunu Güncelle
                const diskTableBody = $('#disk-usage-table tbody');
                diskTableBody.empty();

                data.disks.forEach(disk => {
                    const usageClass = disk.usage_percent > 90 ? 'danger' :
                                      disk.usage_percent > 70 ? 'warning' : 'success';

                    diskTableBody.append(`
                        <tr>
                            <td>
                                <i class="fas fa-hdd text-${usageClass} mr-1"></i>
                                ${disk.device}
                            </td>
                            <td>${disk.mountpoint}</td>
                            <td>${disk.total_gb.toFixed(1)} GB</td>
                            <td>${disk.used_gb.toFixed(1)} GB</td>
                            <td>${disk.free_gb.toFixed(1)} GB</td>
                            <td>
                                <div class="progress" style="height: 4px;">
                                    <div class="progress-bar bg-${usageClass}"
                                         style="width: ${disk.usage_percent}%">
                                    </div>
                                </div>
                                <small class="text-muted">${disk.usage_percent.toFixed(1)}% kullanımda</small>
                            </td>
                        </tr>
                    `);
                });
            }
        });
    </script>
@stop
