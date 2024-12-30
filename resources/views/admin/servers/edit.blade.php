@extends('adminlte::page')

@section('title', 'Sunucu Düzenle')

@section('content_header')
    <h1>Sunucu Düzenle: {{ $server->name }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.servers.update', $server) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Sunucu Adı</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name', $server->name) }}" required>
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="ip_address">IP Adresi</label>
                    <input type="text" class="form-control @error('ip_address') is-invalid @enderror"
                           id="ip_address" name="ip_address" value="{{ old('ip_address', $server->ip_address) }}" required>
                    @error('ip_address')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="ssh_port">SSH Port</label>
                    <input type="number" class="form-control @error('ssh_port') is-invalid @enderror"
                           id="ssh_port" name="ssh_port" value="{{ old('ssh_port', $server->ssh_port) }}" required>
                    @error('ssh_port')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="ws_port">WebSocket Port</label>
                    <input type="number" class="form-control @error('ws_port') is-invalid @enderror"
                           id="ws_port" name="ws_port" value="{{ old('ws_port', $server->ws_port) }}" required>
                    @error('ws_port')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="username">Kullanıcı Adı</label>
                    <input type="text" class="form-control @error('username') is-invalid @enderror"
                           id="username" name="username" value="{{ old('username', $server->username) }}" required>
                    @error('username')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Şifre (Boş bırakırsanız değişmeyecek)</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                           id="password" name="password">
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="is_active"
                               name="is_active" value="1" {{ $server->is_active ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_active">Aktif</label>
                    </div>
                </div>

                <div class="form-group">
                    <label>API Key</label>
                    <div class="input-group">
                        <input type="text" class="form-control" value="{{ $server->api_key }}" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary copy-btn" type="button"
                                    data-clipboard-text="{{ $server->api_key }}">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Güncelle</button>
                <a href="{{ route('admin.servers.index') }}" class="btn btn-secondary">İptal</a>
            </form>
        </div>
    </div>
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
    <script>
        new ClipboardJS('.copy-btn');
    </script>
@stop
