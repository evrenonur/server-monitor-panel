@extends('adminlte::page')

@section('title', 'Sunucular')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Sunucular</h1>
        <a href="{{ route('admin.servers.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Yeni Sunucu
        </a>
    </div>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Sunucu Adı</th>
                        <th>IP Adresi</th>
                        <th>SSH Port</th>
                        <th>Kullanıcı Adı</th>
                        <th>API Key</th>
                        <th>Durum</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($servers as $server)
                        <tr>
                            <td>{{ $server->id }}</td>
                            <td>{{ $server->name }}</td>
                            <td>{{ $server->ip_address }}</td>
                            <td>{{ $server->ssh_port }}</td>
                            <td>{{ $server->username }}</td>
                            <td>
                                <div class="input-group">
                                    <input type="text" class="form-control" value="{{ $server->api_key }}" readonly>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary copy-btn" type="button" data-clipboard-text="{{ $server->api_key }}">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($server->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Pasif</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown">
                                        İşlemler
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('admin.servers.show', $server) }}">
                                            <i class="fas fa-eye"></i> Detaylar
                                        </a>
                                        <a class="dropdown-item" href="{{ route('admin.servers.usage', $server) }}">
                                            <i class="fas fa-chart-line"></i> Kullanım Detayları
                                        </a>
                                        <a class="dropdown-item" href="{{ route('admin.servers.edit', $server) }}">
                                            <i class="fas fa-edit"></i> Düzenle
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <form action="{{ route('admin.servers.destroy', $server) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Emin misiniz?')">
                                                <i class="fas fa-trash"></i> Sil
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Henüz sunucu eklenmemiş.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
    <script>
        new ClipboardJS('.copy-btn');
    </script>
@stop 