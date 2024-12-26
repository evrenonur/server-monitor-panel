@extends('adminlte::page')

@section('title', 'Yeni Sunucu Ekle')

@section('content_header')
    <h1>Yeni Sunucu Ekle</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.servers.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="name">Sunucu Adı</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="ip_address">IP Adresi</label>
                    <input type="text" class="form-control @error('ip_address') is-invalid @enderror" 
                           id="ip_address" name="ip_address" value="{{ old('ip_address') }}" required>
                    @error('ip_address')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="ssh_port">SSH Port</label>
                    <input type="number" class="form-control @error('ssh_port') is-invalid @enderror" 
                           id="ssh_port" name="ssh_port" value="{{ old('ssh_port', 22) }}" required>
                    @error('ssh_port')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="username">Kullanıcı Adı</label>
                    <input type="text" class="form-control @error('username') is-invalid @enderror" 
                           id="username" name="username" value="{{ old('username') }}" required>
                    @error('username')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Şifre</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                           id="password" name="password" required>
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" checked>
                        <label class="custom-control-label" for="is_active">Aktif</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Kaydet</button>
                <a href="{{ route('admin.servers.index') }}" class="btn btn-secondary">İptal</a>
            </form>
        </div>
    </div>
@stop 