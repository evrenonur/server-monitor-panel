@extends('adminlte::page')

@section('title', 'Kullanıcı Düzenle')

@section('content_header')
    <h1>Kullanıcı Düzenle: {{ $user->name }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Ad Soyad</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">E-posta</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                           id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Yeni Şifre (Boş bırakırsanız değişmeyecek)</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                           id="password" name="password">
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Yeni Şifre Tekrar</label>
                    <input type="password" class="form-control"
                           id="password_confirmation" name="password_confirmation">
                </div>

                <button type="submit" class="btn btn-primary">Güncelle</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">İptal</a>
            </form>
        </div>
    </div>
@stop
