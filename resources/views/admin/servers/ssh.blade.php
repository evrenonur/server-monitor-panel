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
            <button class="btn btn-primary mr-2 btn-fullscreen" onclick="toggleFullscreen()">
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
    /* Terminal Container */
    #terminal {
        height: calc(100vh - 300px);
        min-height: 500px;
        background: #000;
        padding: 10px;
        border-radius: 0 0 4px 4px;
    }

    /* Tam Ekran Modu */
    #terminal.fullscreen {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        z-index: 9999;
        padding: 20px;
        border-radius: 0;
    }

    /* Terminal Card */
    .terminal-card {
        margin-bottom: 0;
        border: none;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .terminal-card .card-header {
        background-color: #343a40;
        border-bottom: 1px solid #444;
        padding: 0.75rem 1rem;
    }

    .terminal-card .card-body {
        padding: 0;
        background: #000;
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

    /* Bilgi Kutuları */
    .info-box {
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        min-height: 90px;
    }

    .info-box-content {
        padding: 15px;
    }

    .info-box-number {
        font-size: 1.1rem;
        font-weight: 600;
        margin-top: 5px;
    }

    /* SSH Komut Alanı */
    .ssh-command {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        padding: 10px;
        margin-top: 10px;
    }

    .ssh-command .input-group-text {
        background-color: #343a40;
        color: #fff;
        border: none;
    }

    .ssh-command .form-control {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        font-family: monospace;
    }

    /* Uyarı Mesajları */
    #copyAlert {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1050;
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
    const button = document.querySelector('.btn-fullscreen');

    terminal.classList.toggle('fullscreen');

    // Buton ikonunu ve metnini güncelle
    if (terminal.classList.contains('fullscreen')) {
        button.innerHTML = '<i class="fas fa-compress"></i> Küçült';
        button.classList.replace('btn-primary', 'btn-warning');
    } else {
        button.innerHTML = '<i class="fas fa-expand"></i> Tam Ekran';
        button.classList.replace('btn-warning', 'btn-primary');
    }

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

// WebSocket bağlantı yönetimi
class SSHConnection {
    constructor(terminal, config) {
        this.term = terminal;
        this.config = config;
        this.ws = null;
        this.connected = false;
        this.reconnectAttempts = 0;
        this.maxReconnectAttempts = 3;
    }

    connect() {
        const protocol = this.config.secure ? 'wss' : 'ws';
        const url = `${protocol}://${this.config.host}:8090?token=${this.config.token}`;

        this.ws = new WebSocket(url);
        this.setupEventListeners();
    }

    setupEventListeners() {
        // Bağlantı açıldığında
        this.ws.onopen = () => {
            this.connected = true;
            this.reconnectAttempts = 0;
            this.term.write('\r\n\x1b[32mBağlantı kuruldu...\x1b[0m\r\n');

            // SSH bağlantı bilgilerini gönder
            this.ws.send(JSON.stringify({
                type: 'connect',
                host: this.config.sshHost,
                port: this.config.sshPort,
                username: this.config.username,
                password: this.config.password,
                cols: this.term.cols,
                rows: this.term.rows
            }));
        };

        // Mesaj alındığında
        this.ws.onmessage = (event) => {
            try {
                const data = JSON.parse(event.data);
                switch (data.type) {
                    case 'output':
                        this.term.write(data.data);
                        break;
                    case 'error':
                        this.term.write('\r\n\x1b[31m' + data.message + '\x1b[0m\r\n');
                        break;
                    case 'connected':
                        this.term.write('\r\n\x1b[32mSSH bağlantısı hazır...\x1b[0m\r\n');
                        break;
                }
            } catch (error) {
                console.error('Message parsing error:', error);
            }
        };

        // Bağlantı hatası
        this.ws.onerror = (error) => {
            console.error('WebSocket error:', error);
            this.term.write('\r\n\x1b[31mBağlantı hatası!\x1b[0m\r\n');
            this.tryReconnect();
        };

        // Bağlantı kapandığında
        this.ws.onclose = () => {
            this.connected = false;
            this.term.write('\r\n\x1b[31mBağlantı kapandı!\x1b[0m\r\n');
            this.tryReconnect();
        };

        // Terminal input
        this.term.onData(data => {
            if (this.connected && this.ws.readyState === WebSocket.OPEN) {
                this.ws.send(JSON.stringify({
                    type: 'input',
                    input: data
                }));
            }
        });

        // Terminal boyut değişimi
        this.handleResize();
    }

    handleResize() {
        const resizeHandler = () => {
            if (this.connected && this.ws.readyState === WebSocket.OPEN) {
                this.ws.send(JSON.stringify({
                    type: 'resize',
                    cols: this.term.cols,
                    rows: this.term.rows
                }));
            }
        };

        window.addEventListener('resize', () => {
            fitAddon.fit();
            resizeHandler();
        });
    }

    tryReconnect() {
        if (this.reconnectAttempts < this.maxReconnectAttempts) {
            this.reconnectAttempts++;
            this.term.write(`\r\n\x1b[33mYeniden bağlanılıyor (${this.reconnectAttempts}/${this.maxReconnectAttempts})...\x1b[0m\r\n`);
            setTimeout(() => this.connect(), 2000);
        } else {
            this.term.write('\r\n\x1b[31mBağlantı kurulamadı! Sayfayı yenileyin.\x1b[0m\r\n');
        }
    }

    disconnect() {
        if (this.ws) {
            this.ws.close();
        }
    }
}

// Terminal ve bağlantı yapılandırması
const terminalConfig = {
    secure: false,
    host: '{{ config('app.websocket_host', 'localhost') }}',
    port: {{ config('app.websocket_port', 8090) }},
    token: '{{ auth()->user()->api_token }}',
    sshHost: '{{ $server->ip_address }}',
    sshPort: {{ $server->ssh_port ?? 22 }},
    username: '{{ $server->username }}',
    password: '{{ $server->password }}'
};

// Terminal bağlantısını başlat
const sshConnection = new SSHConnection(term, terminalConfig);
sshConnection.connect();

// Sayfa kapanırken bağlantıyı kapat
window.addEventListener('beforeunload', () => {
    sshConnection.disconnect();
});

// ESC tuşu ile tam ekrandan çıkma
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        const terminal = document.getElementById('terminal');
        if (terminal.classList.contains('fullscreen')) {
            toggleFullscreen();
        }
    }
});
</script>
@stop
