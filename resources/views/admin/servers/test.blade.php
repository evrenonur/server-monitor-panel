@extends('adminlte::page')

@section('title', 'WebSocket Test')

@section('content_header')
    <h1>WebSocket Test</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">WebSocket Komut Testi</h3>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label>API Key</label>
            <input type="text" class="form-control" id="apiKey" value="{{ $server->api_key }}" readonly>
        </div>
        <div class="form-group">
            <label>Komut</label>
            <input type="text" class="form-control" id="command" value="ls" placeholder="Komut girin">
        </div>
        <button class="btn btn-primary" id="sendCommand">
            <i class="fas fa-paper-plane"></i> Komutu Gönder
        </button>
        <hr>
        <div class="form-group">
            <label>Yanıt</label>
            <pre id="response" class="bg-dark text-light p-3" style="min-height: 200px;"></pre>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
$(function() {
    $('#sendCommand').on('click', function() {
        const apiKey = $('#apiKey').val();
        const command = $('#command').val();
        const timestamp = Math.floor(Date.now() / 1000).toString();

        // HMAC-SHA256 ile auth_key oluştur
        const authKey = CryptoJS.HmacSHA256(timestamp, apiKey).toString();

        const protocol = window.location.protocol === 'https:' ? 'wss' : 'ws';
        const url = `${protocol}://{{ $server->ip_address }}:{{ $server->ws_port }}/?token={{ auth()->user()->api_token }}`;
        const ws = new WebSocket(url);
        // WebSocket bağlantısı

        ws.onopen = function() {
            // Önce doğrulama bilgilerini gönder
            ws.send(JSON.stringify({
                api_key: authKey,
                timestamp: timestamp
            }));

            // Sonra komutu gönder
            ws.send(JSON.stringify({
                command: command
            }));
        };

        ws.onmessage = function(event) {
            try {
                const response = JSON.parse(event.data);
                $('#response').text(JSON.stringify(response, null, 2));
            } catch (e) {
                $('#response').text(event.data);
            }
        };

        ws.onerror = function(error) {
            $('#response').text('Hata: ' + error.message);
        };

        ws.onclose = function() {
            $('#response').append('\nBağlantı kapandı');
        };
    });
});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
@stop

@section('css')
<style>
    #response {
        font-family: monospace;
        white-space: pre-wrap;
        word-wrap: break-word;
    }
</style>
@stop
