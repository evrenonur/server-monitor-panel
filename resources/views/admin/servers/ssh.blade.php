@extends('adminlte::page')

@section('title', 'SSH Terminal - ' . $server->name)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>
            <i class="fas fa-terminal mr-2"></i>
            <span class="text-muted">SSH Terminal /</span>
            {{ $server->name }}
        </h1>
        <div>
            <button class="btn btn-primary mr-2" onclick="toggleFullscreen()">
                <i class="fas fa-expand"></i> Tam Ekran
            </button>
            <a href="{{ route('admin.servers.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Geri
            </a>
        </div>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- Sunucu Bilgileri -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h3 class="card-title d-flex align-items-center">
                    <i class="fas fa-info-circle mr-2 text-info"></i>
                    Bağlantı Bilgileri
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="info-box">
                            <span class="info-box-icon bg-info">
                                <i class="fas fa-server"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Sunucu</span>
                                <span class="info-box-number">{{ $server->name }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box">
                            <span class="info-box-icon bg-success">
                                <i class="fas fa-network-wired"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">IP Adresi</span>
                                <span class="info-box-number">{{ $server->ip_address }} : {{ $server->ssh_port }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning">
                                <i class="fas fa-user"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">SSH Kullanıcısı</span>
                                <span class="info-box-number">{{ $server->username }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <label class="text-muted mb-2">
                        <i class="fas fa-terminal mr-1"></i>
                        SSH Bağlantı Komutu
                    </label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-dark text-light">
                                <i class="fas fa-code"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control bg-light" id="sshCommand" readonly
                            value="{{ 'ssh '.$server->username.'@'.$server->ip_address.' -p '.$server->ssh_port }}">
                        <div class="input-group-append">
                            <button class="btn btn-dark" type="button" onclick="copySSHCommand()">
                                <i class="fas fa-copy"></i> Kopyala
                            </button>
                        </div>
                    </div>
                    <div id="copyAlert" class="alert alert-success mt-2 d-none">
                        <i class="fas fa-check-circle mr-1"></i> Komut panoya kopyalandı!
                    </div>
                </div>
            </div>
        </div>

        <!-- Terminal -->
        <div class="card">
            <div class="card-header bg-dark text-light">
                <h3 class="card-title">
                    <i class="fas fa-terminal mr-2"></i>Terminal Oturumu
                </h3>
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
            </div>
            <div class="card-body p-0">
                <div id="terminal"></div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/xterm@4.19.0/css/xterm.css">
<style>
    #terminal {
        height: 500px;
        background: #000;
        padding: 10px;
    }
    #terminal.fullscreen {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        z-index: 9999;
        padding: 20px;
    }
    .info-box-number {
        font-size: 1.2rem;
        font-weight: 600;
    }
    .card-header .btn-tool {
        margin-left: 0.5rem;
    }
    .bg-dark {
        background-color: #343a40 !important;
    }
</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/xterm@4.19.0/lib/xterm.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xterm-addon-fit@0.5.0/lib/xterm-addon-fit.js"></script>

<script>
let fontSize = 14;

// Font Boyutu Ayarlama
function increaseFontSize() {
    fontSize = Math.min(fontSize + 2, 24);
    updateTerminalFont();
}

function decreaseFontSize() {
    fontSize = Math.max(fontSize - 2, 10);
    updateTerminalFont();
}

function updateTerminalFont() {
    term.setOption('fontSize', fontSize);
    fitAddon.fit();
}

// SSH Komut Kopyalama
function copySSHCommand() {
    const command = document.getElementById('sshCommand');
    command.select();
    document.execCommand('copy');

    const alert = document.getElementById('copyAlert');
    alert.classList.remove('d-none');
    setTimeout(() => alert.classList.add('d-none'), 2000);
}

// Tam Ekran Modu
function toggleFullscreen() {
    const terminal = document.getElementById('terminal');
    terminal.classList.toggle('fullscreen');
    fitAddon.fit();
}

// Terminal Ayarları
const term = new Terminal({
    cursorBlink: true,
    macOptionIsMeta: true,
    scrollback: 1000,
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
    }
});

const fitAddon = new FitAddon.FitAddon();
term.loadAddon(fitAddon);

term.open(document.getElementById('terminal'));
fitAddon.fit();

// WebSocket Bağlantısı
const protocol = '{{ config('app.env') === 'production' ? 'wss' : 'ws' }}';
const wsHost = '{{ config('app.websocket_host', '127.0.0.1') }}';
const wsPort = {{ config('app.websocket_port', 8090) }};

const ws = new WebSocket(
    `${protocol}://${wsHost}:${wsPort}?token={{ auth()->user()->api_token }}`
);

// Bağlantı durumunu kontrol et
ws.onerror = (error) => {
    term.write('\r\n\x1b[31mWebSocket bağlantı hatası!\x1b[0m\r\n');
    console.error('WebSocket error:', error);
};

ws.onclose = () => {
    term.write('\r\n\x1b[31mBağlantı kapandı!\x1b[0m\r\n');
};

ws.onopen = () => {
    term.write('\r\n\x1b[32mBağlantı kuruldu...\x1b[0m\r\n');

    ws.send(JSON.stringify({
        type: 'connect',
        host: '{{ $server->ip_address }}',
        port: {{ $server->ssh_port ?? 22 }},
        username: '{{ $server->username }}',
        password: '{{ $server->password }}',
        cols: term.cols,
        rows: term.rows
    }));
};

ws.onmessage = (event) => {
    const data = JSON.parse(event.data);

    switch (data.type) {
        case 'output':
            term.write(data.data);
            break;
        case 'error':
            term.write('\r\n\x1b[31m' + data.message + '\x1b[0m\r\n');
            break;
    }
};

term.onData(data => {
    ws.send(JSON.stringify({
        type: 'input',
        input: data
    }));
});

// Terminal Boyutlandırma
window.addEventListener('resize', () => {
    fitAddon.fit();
    ws.send(JSON.stringify({
        type: 'resize',
        cols: term.cols,
        rows: term.rows
    }));
});

// ESC tuşu ile tam ekrandan çık
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && document.getElementById('terminal').classList.contains('fullscreen')) {
        toggleFullscreen();
    }
});
</script>
@stop
