@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <!-- Özet Kartları -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $serverStats['total'] }}</h3>
                    <p>Toplam Sunucu</p>
                </div>
                <div class="icon">
                    <i class="fas fa-server"></i>
                </div>
                <a href="{{ route('admin.servers.index') }}" class="small-box-footer">
                    Detaylar <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $serverStats['active'] }}</h3>
                    <p>Aktif Sunucu</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <a href="{{ route('admin.servers.index') }}" class="small-box-footer">
                    Detaylar <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $serverStats['inactive'] }}</h3>
                    <p>Pasif Sunucu</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <a href="{{ route('admin.servers.index') }}" class="small-box-footer">
                    Detaylar <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $averageUsage->server_count }}</h3>
                    <p>24s İçinde Güncellenen</p>
                </div>
                <div class="icon">
                    <i class="fas fa-sync"></i>
                </div>
                <a href="{{ route('admin.servers.index') }}" class="small-box-footer">
                    Detaylar <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Ortalama Kaynak Kullanımı -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line mr-1"></i>
                        Ortalama Kaynak Kullanımı (24s)
                    </h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <tr>
                            <td>
                                <i class="fas fa-microchip text-success"></i>
                                CPU Kullanımı
                            </td>
                            <td class="text-right">
                                <div class="progress">
                                    <div class="progress-bar bg-success" style="width: {{ $averageUsage->avg_cpu }}%">
                                        {{ number_format($averageUsage->avg_cpu, 1) }}%
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <i class="fas fa-memory text-warning"></i>
                                Bellek Kullanımı
                            </td>
                            <td class="text-right">
                                <div class="progress">
                                    <div class="progress-bar bg-warning" style="width: {{ $averageUsage->avg_memory }}%">
                                        {{ number_format($averageUsage->avg_memory, 1) }}%
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Yüksek Kullanımlı Sunucular -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-exclamation-triangle text-warning mr-1"></i>
                        Yüksek Kaynak Kullanımı
                    </h3>
                </div>
                <div class="card-body p-0">
                    @if($highUsageServers->isEmpty())
                        <div class="text-center p-3">
                            <i class="fas fa-check-circle text-success fa-2x mb-2"></i>
                            <p class="mb-0">Yüksek kullanımlı sunucu yok</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Sunucu</th>
                                        <th>CPU</th>
                                        <th>Bellek</th>
                                        <th>Uyarı</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($highUsageServers as $usage)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.servers.show', $usage->server) }}">
                                                    {{ $usage->server->name }}
                                                </a>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $usage->max_cpu >= 90 ? 'danger' : 'warning' }}">
                                                    {{ number_format($usage->max_cpu, 1) }}%
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $usage->max_memory >= 90 ? 'danger' : 'warning' }}">
                                                    {{ number_format($usage->max_memory, 1) }}%
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-info">
                                                    {{ $usage->alert_count }}x
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Son Güncellenen Sunucular -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history mr-1"></i>
                        Son Güncellenen Sunucular
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <tbody>
                                @forelse($recentUpdates as $server)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.servers.show', $server) }}">
                                                {{ $server->name }}
                                            </a>
                                            @if($server->is_active)
                                                <span class="badge badge-success">Aktif</span>
                                            @else
                                                <span class="badge badge-danger">Pasif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="small text-muted">
                                                <div>
                                                    <i class="fas fa-info-circle"></i>
                                                    Sistem: {{ $server->last_update->format('H:i:s') }}
                                                    <small>({{ $server->last_update->diffForHumans() }})</small>
                                                </div>
                                                @if($server->last_resource_update)
                                                    <div>
                                                        <i class="fas fa-chart-line"></i>
                                                        Metrik: {{ $server->last_resource_update->format('H:i:s') }}
                                                        <small>({{ $server->last_resource_update->diffForHumans() }})</small>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center py-3">
                                            <i class="fas fa-info-circle text-info mr-1"></i>
                                            Son 24 saatte güncelleme yok
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('footer')
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
@stop 